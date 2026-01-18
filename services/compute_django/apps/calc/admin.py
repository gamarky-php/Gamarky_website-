"""
Admin configuration for calculation models
تكوين الإدارة لنماذج الحسابات
"""

from django.contrib import admin
from django.utils.html import format_html
from django.urls import reverse
from django.utils import timezone
from .models import CalculationJob, IdempotencyKey, CalculationCache


@admin.register(CalculationJob)
class CalculationJobAdmin(admin.ModelAdmin):
    """Admin interface for CalculationJob model"""
    
    list_display = [
        'id', 'calculation_type', 'status', 'submitted_at', 
        'duration_display', 'progress_percentage', 'user'
    ]
    list_filter = ['calculation_type', 'status', 'submitted_at']
    search_fields = ['id', 'user__username', 'celery_task_id']
    readonly_fields = ['id', 'submitted_at', 'started_at', 'completed_at', 'duration_display']
    ordering = ['-submitted_at']
    
    fieldsets = (
        ('Job Information', {
            'fields': ('id', 'calculation_type', 'status', 'progress_percentage')
        }),
        ('Timing', {
            'fields': ('submitted_at', 'started_at', 'completed_at', 'duration_display')
        }),
        ('Data', {
            'fields': ('input_parameters', 'result_data', 'error_message'),
            'classes': ('collapse',)
        }),
        ('Tracking', {
            'fields': ('user', 'ip_address', 'user_agent', 'celery_task_id'),
            'classes': ('collapse',)
        }),
    )
    
    def duration_display(self, obj):
        """Display job duration in human readable format"""
        duration = obj.duration
        if duration:
            total_seconds = int(duration.total_seconds())
            hours = total_seconds // 3600
            minutes = (total_seconds % 3600) // 60
            seconds = total_seconds % 60
            
            if hours > 0:
                return f"{hours}h {minutes}m {seconds}s"
            elif minutes > 0:
                return f"{minutes}m {seconds}s"
            else:
                return f"{seconds}s"
        return "-"
    duration_display.short_description = "Duration"
    
    def get_queryset(self, request):
        """Optimize queryset with select_related"""
        return super().get_queryset(request).select_related('user')


@admin.register(IdempotencyKey)
class IdempotencyKeyAdmin(admin.ModelAdmin):
    """Admin interface for IdempotencyKey model"""
    
    list_display = ['key', 'calculation_job_link', 'created_at', 'expires_at', 'is_expired_display']
    list_filter = ['created_at', 'expires_at']
    search_fields = ['key', 'calculation_job__id']
    readonly_fields = ['created_at', 'is_expired_display']
    ordering = ['-created_at']
    
    def calculation_job_link(self, obj):
        """Create link to related calculation job"""
        if obj.calculation_job:
            url = reverse('admin:calc_calculationjob_change', args=[obj.calculation_job.id])
            return format_html('<a href="{}">{}</a>', url, obj.calculation_job.id)
        return "-"
    calculation_job_link.short_description = "Calculation Job"
    
    def is_expired_display(self, obj):
        """Display if idempotency key is expired"""
        if obj.is_expired:
            return format_html('<span style="color: red;">Expired</span>')
        else:
            return format_html('<span style="color: green;">Active</span>')
    is_expired_display.short_description = "Status"
    
    def get_queryset(self, request):
        """Optimize queryset with select_related"""
        return super().get_queryset(request).select_related('calculation_job')


@admin.register(CalculationCache)
class CalculationCacheAdmin(admin.ModelAdmin):
    """Admin interface for CalculationCache model"""
    
    list_display = [
        'cache_key', 'calculation_type', 'access_count', 
        'created_at', 'accessed_at', 'expires_at', 'is_expired_display'
    ]
    list_filter = ['calculation_type', 'created_at', 'expires_at']
    search_fields = ['cache_key', 'calculation_type', 'input_hash']
    readonly_fields = ['created_at', 'accessed_at', 'access_count', 'is_expired_display']
    ordering = ['-accessed_at']
    
    fieldsets = (
        ('Cache Information', {
            'fields': ('cache_key', 'calculation_type', 'input_hash')
        }),
        ('Usage Statistics', {
            'fields': ('access_count', 'created_at', 'accessed_at')
        }),
        ('Expiration', {
            'fields': ('expires_at', 'is_expired_display')
        }),
        ('Cached Data', {
            'fields': ('result_data',),
            'classes': ('collapse',)
        }),
    )
    
    def is_expired_display(self, obj):
        """Display if cache entry is expired"""
        if obj.is_expired:
            return format_html('<span style="color: red;">Expired</span>')
        else:
            return format_html('<span style="color: green;">Active</span>')
    is_expired_display.short_description = "Status"
    
    actions = ['clear_expired_cache']
    
    def clear_expired_cache(self, request, queryset):
        """Admin action to clear expired cache entries"""
        deleted_count = CalculationCache.cleanup_expired()
        self.message_user(request, f"Cleared {deleted_count} expired cache entries.")
    clear_expired_cache.short_description = "Clear expired cache entries"


# Additional admin customizations
admin.site.site_header = "Gamarky Compute Service Admin"
admin.site.site_title = "Gamarky Compute Admin"
admin.site.index_title = "Gamarky Compute Administration"