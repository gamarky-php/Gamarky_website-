"""
URL patterns for calculation endpoints
أنماط URLs لنقاط نهاية الحسابات
"""

from django.urls import path
from . import views

app_name = 'calc'

urlpatterns = [
    # Synchronous calculation endpoints
    path('import-cost/', views.ImportCostCalculationView.as_view(), name='import_cost'),
    path('export-cost/', views.ExportCostCalculationView.as_view(), name='export_cost'),
    path('manufacturing-cost/', views.ManufacturingCostCalculationView.as_view(), name='manufacturing_cost'),
    
    # Asynchronous job endpoints
    path('jobs/', views.JobSubmissionView.as_view(), name='submit_job'),
    path('jobs/<str:job_id>/', views.JobStatusView.as_view(), name='job_status'),
]