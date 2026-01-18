"""
Serializers for chart data endpoints
مُسلسِلات نقاط نهاية بيانات الرسوم البيانية
"""

from rest_framework import serializers
import uuid


class ChartDataRequestSerializer(serializers.Serializer):
    """Serializer for chart data request"""
    calculation_id = serializers.UUIDField()
    chart_type = serializers.ChoiceField(
        choices=['pie', 'bar', 'line'],
        default='pie'
    )


class ComparisonChartRequestSerializer(serializers.Serializer):
    """Serializer for comparison chart request"""
    calculation_ids = serializers.ListField(
        child=serializers.UUIDField(),
        min_length=2,
        max_length=10
    )
    chart_type = serializers.ChoiceField(
        choices=['bar', 'line'],
        default='bar'
    )


class ChartSeriesSerializer(serializers.Serializer):
    """Serializer for chart series data"""
    name = serializers.CharField()
    data = serializers.ListField(child=serializers.CharField())
    color = serializers.CharField(required=False)
    colors = serializers.ListField(
        child=serializers.CharField(),
        required=False
    )


class ChartDataSerializer(serializers.Serializer):
    """Serializer for chart data structure"""
    labels = serializers.ListField(child=serializers.CharField())
    series = ChartSeriesSerializer(many=True)


class ChartResponseSerializer(serializers.Serializer):
    """Serializer for chart response"""
    chart_type = serializers.ChoiceField(choices=['pie', 'bar', 'line'])
    data = ChartDataSerializer()
    title = serializers.CharField()
    subtitle = serializers.CharField(required=False)