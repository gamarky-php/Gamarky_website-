"""
URL patterns for health monitoring endpoints
أنماط URLs لنقاط نهاية مراقبة الحالة الصحية
"""

from django.urls import path
from . import views

app_name = 'health'

urlpatterns = [
    # Main health check endpoint (matches OpenAPI spec)
    path('', views.HealthCheckView.as_view(), name='health_check'),
    
    # Kubernetes/Docker health checks
    path('ready/', views.ReadinessCheckView.as_view(), name='readiness'),
    path('live/', views.LivenessCheckView.as_view(), name='liveness'),
    
    # Service metrics
    path('metrics/', views.ServiceMetricsView.as_view(), name='metrics'),
]