"""
URL patterns for chart endpoints
أنماط URLs لنقاط نهاية الرسوم البيانية
"""

from django.urls import path
from . import views

app_name = 'charts'

urlpatterns = [
    # Chart data endpoints
    path('cost-breakdown/', views.CostBreakdownChartView.as_view(), name='cost_breakdown'),
    path('cost-comparison/', views.CostComparisonChartView.as_view(), name='cost_comparison'),
]