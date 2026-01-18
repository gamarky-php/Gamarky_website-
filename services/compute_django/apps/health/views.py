"""
Views for health monitoring
عروض مراقبة الحالة الصحية للخدمة
"""

from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from django.utils import timezone
from django.db import connection
from django.core.cache import cache
import redis
import logging
from celery import current_app as celery_app


logger = logging.getLogger(__name__)


class HealthCheckView(APIView):
    """
    Comprehensive health check endpoint
    نقطة نهاية فحص الحالة الصحية الشاملة
    """
    
    def get(self, request):
        """
        Perform health checks on all system components
        إجراء فحوصات صحية على جميع مكونات النظام
        """
        health_status = {
            'status': 'healthy',
            'timestamp': timezone.now().isoformat(),
            'services': {}
        }
        
        overall_healthy = True
        
        # Check database connection
        db_status = self._check_database()
        health_status['services']['database'] = db_status
        if db_status != 'up':
            overall_healthy = False
        
        # Check Redis connection
        redis_status = self._check_redis()
        health_status['services']['redis'] = redis_status
        if redis_status != 'up':
            overall_healthy = False
        
        # Check Celery workers
        celery_status = self._check_celery()
        health_status['services']['celery'] = celery_status
        if celery_status != 'up':
            overall_healthy = False
        
        # Set overall status
        if not overall_healthy:
            health_status['status'] = 'degraded'
        
        # Return appropriate HTTP status code
        http_status = status.HTTP_200_OK if overall_healthy else status.HTTP_503_SERVICE_UNAVAILABLE
        
        logger.info(f"Health check completed: {health_status['status']}")
        return Response(health_status, status=http_status)
    
    def _check_database(self):
        """Check database connectivity"""
        try:
            with connection.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
            logger.debug("Database health check: UP")
            return 'up'
        except Exception as e:
            logger.error(f"Database health check failed: {e}")
            return 'down'
    
    def _check_redis(self):
        """Check Redis connectivity"""
        try:
            # Test cache connection
            test_key = 'health_check_test'
            test_value = 'test_value'
            cache.set(test_key, test_value, timeout=60)
            retrieved_value = cache.get(test_key)
            
            if retrieved_value == test_value:
                cache.delete(test_key)
                logger.debug("Redis health check: UP")
                return 'up'
            else:
                logger.error("Redis health check: Cache set/get mismatch")
                return 'down'
                
        except Exception as e:
            logger.error(f"Redis health check failed: {e}")
            return 'down'
    
    def _check_celery(self):
        """Check Celery worker connectivity"""
        try:
            # Get active workers
            inspect = celery_app.control.inspect()
            active_workers = inspect.active()
            
            if active_workers:
                # Check if any workers are available
                worker_count = len(active_workers)
                logger.debug(f"Celery health check: UP ({worker_count} workers)")
                return 'up'
            else:
                logger.warning("Celery health check: No active workers found")
                return 'down'
                
        except Exception as e:
            logger.error(f"Celery health check failed: {e}")
            return 'down'


class ReadinessCheckView(APIView):
    """
    Readiness check for load balancers
    فحص الجاهزية لموازنات التحميل
    """
    
    def get(self, request):
        """
        Check if service is ready to handle requests
        فحص ما إذا كانت الخدمة جاهزة للتعامل مع الطلبات
        """
        try:
            # Basic checks for readiness
            
            # Check if we can connect to database
            with connection.cursor() as cursor:
                cursor.execute("SELECT 1")
            
            # Check if we can connect to cache
            cache.get('readiness_check')
            
            logger.debug("Readiness check: READY")
            return Response(
                {
                    'status': 'ready',
                    'timestamp': timezone.now().isoformat()
                },
                status=status.HTTP_200_OK
            )
            
        except Exception as e:
            logger.error(f"Readiness check failed: {e}")
            return Response(
                {
                    'status': 'not_ready',
                    'timestamp': timezone.now().isoformat(),
                    'error': str(e)
                },
                status=status.HTTP_503_SERVICE_UNAVAILABLE
            )


class LivenessCheckView(APIView):
    """
    Liveness check for container orchestrators
    فحص النشاط لمنظمات الحاويات
    """
    
    def get(self, request):
        """
        Simple liveness check - just return 200 if service is running
        فحص نشاط بسيط - إرجاع 200 فقط إذا كانت الخدمة تعمل
        """
        return Response(
            {
                'status': 'alive',
                'timestamp': timezone.now().isoformat()
            },
            status=status.HTTP_200_OK
        )


class ServiceMetricsView(APIView):
    """
    Service metrics and statistics
    مقاييس وإحصائيات الخدمة
    """
    
    def get(self, request):
        """
        Return service metrics and statistics
        إرجاع مقاييس وإحصائيات الخدمة
        """
        try:
            from apps.calc.models import CalculationJob
            
            # Calculate basic metrics
            total_jobs = CalculationJob.objects.count()
            completed_jobs = CalculationJob.objects.filter(status='completed').count()
            failed_jobs = CalculationJob.objects.filter(status='failed').count()
            pending_jobs = CalculationJob.objects.filter(status='pending').count()
            processing_jobs = CalculationJob.objects.filter(status='processing').count()
            
            # Calculate success rate
            success_rate = (completed_jobs / total_jobs * 100) if total_jobs > 0 else 0
            
            # Get recent job stats (last 24 hours)
            from django.utils import timezone
            from datetime import timedelta
            
            yesterday = timezone.now() - timedelta(days=1)
            recent_jobs = CalculationJob.objects.filter(submitted_at__gte=yesterday).count()
            
            # Job type breakdown
            job_types = CalculationJob.objects.values('calculation_type').distinct()
            type_counts = {}
            for job_type in job_types:
                calc_type = job_type['calculation_type']
                count = CalculationJob.objects.filter(calculation_type=calc_type).count()
                type_counts[calc_type] = count
            
            metrics = {
                'timestamp': timezone.now().isoformat(),
                'jobs': {
                    'total': total_jobs,
                    'completed': completed_jobs,
                    'failed': failed_jobs,
                    'pending': pending_jobs,
                    'processing': processing_jobs,
                    'success_rate_percent': round(success_rate, 2),
                    'recent_24h': recent_jobs
                },
                'job_types': type_counts,
                'system': {
                    'version': '1.0.0',
                    'environment': 'development' if hasattr(request, 'DEBUG') else 'production'
                }
            }
            
            logger.debug("Service metrics retrieved successfully")
            return Response(metrics, status=status.HTTP_200_OK)
            
        except Exception as e:
            logger.error(f"Failed to retrieve service metrics: {e}")
            return Response(
                {
                    'error': 'metrics_error',
                    'message': str(e),
                    'timestamp': timezone.now().isoformat()
                },
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )