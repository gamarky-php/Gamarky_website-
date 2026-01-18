"""
Serializers for calculation endpoints
مُسلسِلات نقاط نهاية الحسابات
"""

from rest_framework import serializers
from decimal import Decimal, InvalidOperation
from typing import Dict, Any
import uuid

from .models import CalculationJob


class RawMaterialSerializer(serializers.Serializer):
    """Serializer for raw material input"""
    name = serializers.CharField(max_length=255)
    cost_per_unit = serializers.CharField(max_length=20)
    quantity = serializers.CharField(max_length=20)
    unit = serializers.CharField(max_length=50)
    
    def validate_cost_per_unit(self, value):
        """Validate cost_per_unit is a valid decimal"""
        try:
            decimal_value = Decimal(str(value))
            if decimal_value <= 0:
                raise serializers.ValidationError("Cost per unit must be positive")
            return str(decimal_value)
        except (InvalidOperation, ValueError):
            raise serializers.ValidationError("Cost per unit must be a valid decimal number")
    
    def validate_quantity(self, value):
        """Validate quantity is a valid positive decimal"""
        try:
            decimal_value = Decimal(str(value))
            if decimal_value <= 0:
                raise serializers.ValidationError("Quantity must be positive")
            return str(decimal_value)
        except (InvalidOperation, ValueError):
            raise serializers.ValidationError("Quantity must be a valid decimal number")


class ImportCostRequestSerializer(serializers.Serializer):
    """Serializer for import cost calculation request"""
    product_value = serializers.CharField(max_length=20)
    quantity = serializers.IntegerField(min_value=1)
    origin_country = serializers.CharField(max_length=3, min_length=2)
    destination_country = serializers.CharField(max_length=3, min_length=2)
    product_category = serializers.CharField(max_length=100, default='default')
    shipping_method = serializers.ChoiceField(
        choices=['sea', 'air', 'land'], 
        default='sea'
    )
    insurance_required = serializers.BooleanField(default=True)
    estimated_weight_kg = serializers.FloatField(required=False, min_value=0.1)
    
    def validate_product_value(self, value):
        """Validate product_value is a valid positive decimal"""
        try:
            decimal_value = Decimal(str(value))
            if decimal_value <= 0:
                raise serializers.ValidationError("Product value must be positive")
            return str(decimal_value)
        except (InvalidOperation, ValueError):
            raise serializers.ValidationError("Product value must be a valid decimal number")


class ExportCostRequestSerializer(serializers.Serializer):
    """Serializer for export cost calculation request"""
    product_value = serializers.CharField(max_length=20)
    quantity = serializers.IntegerField(min_value=1)
    origin_country = serializers.CharField(max_length=3, min_length=2)
    destination_country = serializers.CharField(max_length=3, min_length=2)
    product_category = serializers.CharField(max_length=100, default='default')
    shipping_method = serializers.ChoiceField(
        choices=['sea', 'air', 'land'], 
        default='sea'
    )
    export_license_required = serializers.BooleanField(default=False)
    estimated_weight_kg = serializers.FloatField(required=False, min_value=0.1)
    
    def validate_product_value(self, value):
        """Validate product_value is a valid positive decimal"""
        try:
            decimal_value = Decimal(str(value))
            if decimal_value <= 0:
                raise serializers.ValidationError("Product value must be positive")
            return str(decimal_value)
        except (InvalidOperation, ValueError):
            raise serializers.ValidationError("Product value must be a valid decimal number")


class ManufacturingCostRequestSerializer(serializers.Serializer):
    """Serializer for manufacturing cost calculation request"""
    raw_materials = RawMaterialSerializer(many=True)
    labor_hours = serializers.CharField(max_length=20)
    facility_type = serializers.ChoiceField(
        choices=['small', 'medium', 'large', 'industrial']
    )
    overhead_percentage = serializers.CharField(max_length=10, default='10.0')
    
    def validate_raw_materials(self, value):
        """Validate raw materials list is not empty"""
        if not value:
            raise serializers.ValidationError("At least one raw material is required")
        return value
    
    def validate_labor_hours(self, value):
        """Validate labor_hours is a valid positive decimal"""
        try:
            decimal_value = Decimal(str(value))
            if decimal_value <= 0:
                raise serializers.ValidationError("Labor hours must be positive")
            return str(decimal_value)
        except (InvalidOperation, ValueError):
            raise serializers.ValidationError("Labor hours must be a valid decimal number")
    
    def validate_overhead_percentage(self, value):
        """Validate overhead_percentage is a valid decimal"""
        try:
            decimal_value = Decimal(str(value))
            if decimal_value < 0:
                raise serializers.ValidationError("Overhead percentage cannot be negative")
            return str(decimal_value)
        except (InvalidOperation, ValueError):
            raise serializers.ValidationError("Overhead percentage must be a valid decimal number")


class JobRequestSerializer(serializers.Serializer):
    """Serializer for job submission request"""
    calculation_type = serializers.ChoiceField(
        choices=['bulk_import', 'bulk_export', 'complex_manufacturing']
    )
    parameters = serializers.JSONField()
    idempotency_key = serializers.UUIDField(required=False)
    
    def validate_idempotency_key(self, value):
        """Generate idempotency key if not provided"""
        if value is None:
            return uuid.uuid4()
        return value
    
    def validate(self, attrs):
        """Validate parameters based on calculation type"""
        calculation_type = attrs.get('calculation_type')
        parameters = attrs.get('parameters', {})
        
        if calculation_type == 'bulk_import':
            if not isinstance(parameters.get('requests'), list):
                raise serializers.ValidationError(
                    "Bulk import requires 'requests' list in parameters"
                )
        elif calculation_type == 'bulk_export':
            if not isinstance(parameters.get('requests'), list):
                raise serializers.ValidationError(
                    "Bulk export requires 'requests' list in parameters"
                )
        elif calculation_type == 'complex_manufacturing':
            if not parameters.get('scenarios'):
                raise serializers.ValidationError(
                    "Complex manufacturing requires 'scenarios' in parameters"
                )
        
        return attrs


class CostBreakdownSerializer(serializers.Serializer):
    """Serializer for cost breakdown response"""
    base_cost = serializers.CharField()
    taxes = serializers.CharField()
    shipping = serializers.CharField()
    insurance = serializers.CharField()
    customs_duties = serializers.CharField(required=False)
    handling_fees = serializers.CharField()
    other_fees = serializers.CharField()


class CostResponseSerializer(serializers.Serializer):
    """Serializer for cost calculation response"""
    total_cost = serializers.CharField()
    currency = serializers.CharField(default='SAR')
    breakdown = CostBreakdownSerializer()
    calculation_id = serializers.UUIDField()
    calculated_at = serializers.DateTimeField()


class JobResponseSerializer(serializers.Serializer):
    """Serializer for job submission response"""
    job_id = serializers.UUIDField()
    status = serializers.ChoiceField(
        choices=['pending', 'processing', 'completed', 'failed']
    )
    submitted_at = serializers.DateTimeField()
    estimated_completion = serializers.DateTimeField(required=False)


class JobStatusResponseSerializer(serializers.Serializer):
    """Serializer for job status response"""
    job_id = serializers.UUIDField()
    status = serializers.ChoiceField(
        choices=['pending', 'processing', 'completed', 'failed']
    )
    submitted_at = serializers.DateTimeField()
    completed_at = serializers.DateTimeField(required=False)
    progress_percentage = serializers.IntegerField(min_value=0, max_value=100)
    result = CostResponseSerializer(required=False)
    error_message = serializers.CharField(required=False)


class CalculationJobSerializer(serializers.ModelSerializer):
    """Serializer for CalculationJob model"""
    
    class Meta:
        model = CalculationJob
        fields = [
            'id', 'calculation_type', 'status', 'submitted_at',
            'started_at', 'completed_at', 'progress_percentage',
            'input_parameters', 'result_data', 'error_message'
        ]
        read_only_fields = ['id', 'submitted_at']