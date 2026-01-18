"""
Models for calculation jobs and idempotency
نماذج وظائف الحسابات وضمان عدم التكرار
"""

import uuid
from django.db import models
from django.contrib.auth.models import User
from django.utils import timezone
from django.core.serializers.json import DjangoJSONEncoder
import json


class CalculationJob(models.Model):
    """
    Model to track calculation jobs
    نموذج تتبع وظائف الحسابات
    """
    
    STATUS_CHOICES = [
        ('pending', 'Pending'),
        ('processing', 'Processing'), 
        ('completed', 'Completed'),
        ('failed', 'Failed'),
    ]
    
    CALCULATION_TYPES = [
        ('import_cost', 'Import Cost'),
        ('export_cost', 'Export Cost'),
        ('manufacturing_cost', 'Manufacturing Cost'),
        ('bulk_import', 'Bulk Import'),
        ('bulk_export', 'Bulk Export'),
        ('complex_manufacturing', 'Complex Manufacturing'),
    ]
    
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    calculation_type = models.CharField(max_length=50, choices=CALCULATION_TYPES)
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default='pending')
    
    # Job metadata
    submitted_at = models.DateTimeField(auto_now_add=True)
    started_at = models.DateTimeField(null=True, blank=True)
    completed_at = models.DateTimeField(null=True, blank=True)
    progress_percentage = models.IntegerField(default=0)
    
    # Job data
    input_parameters = models.JSONField(encoder=DjangoJSONEncoder)
    result_data = models.JSONField(null=True, blank=True, encoder=DjangoJSONEncoder)
    error_message = models.TextField(null=True, blank=True)
    
    # Tracking
    user = models.ForeignKey(User, on_delete=models.CASCADE, null=True, blank=True)
    ip_address = models.GenericIPAddressField(null=True, blank=True)
    user_agent = models.TextField(null=True, blank=True)
    
    # Celery task tracking
    celery_task_id = models.CharField(max_length=255, null=True, blank=True)
    
    class Meta:
        db_table = 'calc_calculation_jobs'
        ordering = ['-submitted_at']
        indexes = [
            models.Index(fields=['status']),
            models.Index(fields=['calculation_type']),
            models.Index(fields=['submitted_at']),
            models.Index(fields=['celery_task_id']),
        ]
    
    def __str__(self):
        return f"{self.calculation_type} Job {self.id}"
    
    def set_processing(self, task_id=None):
        """Mark job as processing"""
        self.status = 'processing'
        self.started_at = timezone.now()
        if task_id:
            self.celery_task_id = task_id
        self.save(update_fields=['status', 'started_at', 'celery_task_id'])
    
    def set_completed(self, result_data):
        """Mark job as completed with result"""
        self.status = 'completed'
        self.completed_at = timezone.now()
        self.progress_percentage = 100
        self.result_data = result_data
        self.save(update_fields=['status', 'completed_at', 'progress_percentage', 'result_data'])
    
    def set_failed(self, error_message):
        """Mark job as failed with error"""
        self.status = 'failed'
        self.completed_at = timezone.now()
        self.error_message = str(error_message)
        self.save(update_fields=['status', 'completed_at', 'error_message'])
    
    def update_progress(self, percentage):
        """Update job progress percentage"""
        self.progress_percentage = min(max(percentage, 0), 100)
        self.save(update_fields=['progress_percentage'])
    
    @property
    def duration(self):
        """Calculate job duration"""
        if self.started_at and self.completed_at:
            return self.completed_at - self.started_at
        elif self.started_at:
            return timezone.now() - self.started_at
        return None
    
    @property
    def is_finished(self):
        """Check if job is finished (completed or failed)"""
        return self.status in ['completed', 'failed']


class IdempotencyKey(models.Model):
    """
    Model to ensure idempotent operations
    نموذج ضمان عدم تكرار العمليات
    """
    
    key = models.CharField(max_length=255, unique=True, db_index=True)
    calculation_job = models.ForeignKey(CalculationJob, on_delete=models.CASCADE)
    created_at = models.DateTimeField(auto_now_add=True)
    expires_at = models.DateTimeField()
    
    class Meta:
        db_table = 'calc_idempotency_keys'
        ordering = ['-created_at']
        indexes = [
            models.Index(fields=['expires_at']),
        ]
    
    def __str__(self):
        return f"Idempotency Key {self.key}"
    
    @classmethod
    def cleanup_expired(cls):
        """Remove expired idempotency keys"""
        expired_count = cls.objects.filter(expires_at__lt=timezone.now()).delete()[0]
        return expired_count
    
    @property
    def is_expired(self):
        """Check if idempotency key is expired"""
        return timezone.now() > self.expires_at


class CalculationCache(models.Model):
    """
    Model to cache frequent calculations
    نموذج تخزين مؤقت للحسابات المتكررة
    """
    
    cache_key = models.CharField(max_length=255, unique=True, db_index=True)
    calculation_type = models.CharField(max_length=50)
    input_hash = models.CharField(max_length=64)  # SHA256 hash of input parameters
    result_data = models.JSONField(encoder=DjangoJSONEncoder)
    
    created_at = models.DateTimeField(auto_now_add=True)
    accessed_at = models.DateTimeField(auto_now=True)
    access_count = models.PositiveIntegerField(default=1)
    expires_at = models.DateTimeField()
    
    class Meta:
        db_table = 'calc_calculation_cache'
        ordering = ['-accessed_at']
        indexes = [
            models.Index(fields=['calculation_type']),
            models.Index(fields=['input_hash']),
            models.Index(fields=['expires_at']),
            models.Index(fields=['access_count']),
        ]
    
    def __str__(self):
        return f"Cache {self.cache_key} ({self.calculation_type})"
    
    def increment_access(self):
        """Increment access count and update accessed_at"""
        self.access_count += 1
        self.accessed_at = timezone.now()
        self.save(update_fields=['access_count', 'accessed_at'])
    
    @classmethod
    def cleanup_expired(cls):
        """Remove expired cache entries"""
        expired_count = cls.objects.filter(expires_at__lt=timezone.now()).delete()[0]
        return expired_count
    
    @property
    def is_expired(self):
        """Check if cache entry is expired"""
        return timezone.now() > self.expires_at