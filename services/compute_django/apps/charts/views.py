"""
Views for chart data generation
عروض إنشاء بيانات الرسوم البيانية
"""

from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from django.shortcuts import get_object_or_404
import uuid
import logging
from typing import Dict, List, Any

from apps.calc.models import CalculationJob
from .serializers import ChartDataRequestSerializer, ComparisonChartRequestSerializer


logger = logging.getLogger(__name__)


class CostBreakdownChartView(APIView):
    """
    Generate cost breakdown chart data
    إنشاء بيانات رسم تفصيل التكاليف
    """
    
    def post(self, request):
        serializer = ChartDataRequestSerializer(data=request.data)
        if serializer.is_valid():
            try:
                calculation_id = serializer.validated_data['calculation_id']
                chart_type = serializer.validated_data.get('chart_type', 'pie')
                
                # Get calculation job
                job = get_object_or_404(CalculationJob, id=calculation_id, status='completed')
                
                if not job.result_data or 'breakdown' not in job.result_data:
                    return Response(
                        {'error': 'no_breakdown_data', 'message': 'No breakdown data available for this calculation'},
                        status=status.HTTP_404_NOT_FOUND
                    )
                
                # Extract breakdown data
                breakdown = job.result_data['breakdown']
                
                # Generate chart data based on type
                if chart_type == 'pie':
                    chart_data = self._generate_pie_chart_data(breakdown, job.calculation_type)
                elif chart_type == 'bar':
                    chart_data = self._generate_bar_chart_data(breakdown, job.calculation_type)
                else:
                    return Response(
                        {'error': 'unsupported_chart_type', 'message': f'Chart type {chart_type} not supported for breakdown'},
                        status=status.HTTP_400_BAD_REQUEST
                    )
                
                response_data = {
                    'chart_type': chart_type,
                    'data': chart_data,
                    'title': self._get_chart_title(job.calculation_type),
                    'subtitle': f"Calculation ID: {calculation_id}"
                }
                
                logger.info(f"Generated {chart_type} chart for calculation {calculation_id}")
                return Response(response_data, status=status.HTTP_200_OK)
                
            except Exception as e:
                logger.error(f"Chart generation failed: {e}")
                return Response(
                    {'error': 'chart_generation_error', 'message': str(e)},
                    status=status.HTTP_500_INTERNAL_SERVER_ERROR
                )
        else:
            return Response(
                {'error': 'validation_error', 'message': 'Invalid input parameters', 'details': serializer.errors},
                status=status.HTTP_422_UNPROCESSABLE_ENTITY
            )
    
    def _generate_pie_chart_data(self, breakdown: Dict[str, str], calculation_type: str) -> Dict[str, Any]:
        """Generate pie chart data from breakdown"""
        
        # Define labels based on calculation type
        label_mapping = self._get_label_mapping(calculation_type)
        
        labels = []
        data = []
        colors = [
            "#FF6B6B", "#4ECDC4", "#45B7D1", "#96CEB4", 
            "#FFEAA7", "#DDA0DD", "#98D8C8", "#F7DC6F"
        ]
        
        for key, value in breakdown.items():
            if key in label_mapping and float(value) > 0:
                labels.append(label_mapping[key])
                data.append(value)
        
        return {
            'labels': labels,
            'series': [{
                'name': 'Cost Components',
                'data': data,
                'colors': colors[:len(data)]
            }]
        }
    
    def _generate_bar_chart_data(self, breakdown: Dict[str, str], calculation_type: str) -> Dict[str, Any]:
        """Generate bar chart data from breakdown"""
        
        label_mapping = self._get_label_mapping(calculation_type)
        
        labels = []
        data = []
        
        for key, value in breakdown.items():
            if key in label_mapping and float(value) > 0:
                labels.append(label_mapping[key])
                data.append(value)
        
        return {
            'labels': labels,
            'series': [{
                'name': 'Cost Amount (SAR)',
                'data': data,
                'color': "#4ECDC4"
            }]
        }
    
    def _get_label_mapping(self, calculation_type: str) -> Dict[str, str]:
        """Get human-readable labels for breakdown components"""
        
        if calculation_type == 'import_cost':
            return {
                'base_cost': 'Base Cost',
                'customs_duties': 'Customs Duties', 
                'taxes': 'Taxes (VAT)',
                'shipping': 'Shipping',
                'insurance': 'Insurance',
                'handling_fees': 'Handling Fees',
                'other_fees': 'Other Fees'
            }
        elif calculation_type == 'export_cost':
            return {
                'base_cost': 'FOB Value',
                'export_license': 'Export License',
                'documentation_fees': 'Documentation',
                'inspection_fees': 'Inspection',
                'shipping': 'Shipping',
                'insurance': 'Insurance',
                'handling_fees': 'Handling Fees',
                'other_fees': 'Other Fees'
            }
        elif calculation_type == 'manufacturing_cost':
            return {
                'raw_materials_cost': 'Raw Materials',
                'labor_cost': 'Labor Cost',
                'facility_cost': 'Facility Cost',
                'overhead_cost': 'Overhead',
                'quality_control_cost': 'Quality Control',
                'utilities_cost': 'Utilities',
                'equipment_depreciation': 'Equipment Depreciation',
                'other_costs': 'Other Costs'
            }
        else:
            # Generic mapping
            return {key: key.replace('_', ' ').title() for key in ['base_cost', 'taxes', 'shipping', 'other_fees']}
    
    def _get_chart_title(self, calculation_type: str) -> str:
        """Get chart title based on calculation type"""
        
        titles = {
            'import_cost': 'Import Cost Breakdown',
            'export_cost': 'Export Cost Breakdown', 
            'manufacturing_cost': 'Manufacturing Cost Breakdown'
        }
        return titles.get(calculation_type, 'Cost Breakdown')


class CostComparisonChartView(APIView):
    """
    Generate cost comparison chart data
    إنشاء بيانات رسم مقارنة التكاليف
    """
    
    def post(self, request):
        serializer = ComparisonChartRequestSerializer(data=request.data)
        if serializer.is_valid():
            try:
                calculation_ids = serializer.validated_data['calculation_ids']
                chart_type = serializer.validated_data.get('chart_type', 'bar')
                
                # Get all calculation jobs
                jobs = []
                for calc_id in calculation_ids:
                    job = get_object_or_404(CalculationJob, id=calc_id, status='completed')
                    jobs.append(job)
                
                # Validate that all jobs have the same calculation type
                calc_types = {job.calculation_type for job in jobs}
                if len(calc_types) > 1:
                    return Response(
                        {'error': 'mixed_calculation_types', 'message': 'All calculations must be of the same type for comparison'},
                        status=status.HTTP_400_BAD_REQUEST
                    )
                
                calculation_type = jobs[0].calculation_type
                
                # Generate comparison chart
                if chart_type == 'bar':
                    chart_data = self._generate_comparison_bar_data(jobs)
                elif chart_type == 'line':
                    chart_data = self._generate_comparison_line_data(jobs)
                else:
                    return Response(
                        {'error': 'unsupported_chart_type', 'message': f'Chart type {chart_type} not supported for comparison'},
                        status=status.HTTP_400_BAD_REQUEST
                    )
                
                response_data = {
                    'chart_type': chart_type,
                    'data': chart_data,
                    'title': f'{calculation_type.replace("_", " ").title()} Comparison',
                    'subtitle': f'Comparing {len(jobs)} calculations'
                }
                
                logger.info(f"Generated comparison {chart_type} chart for {len(jobs)} calculations")
                return Response(response_data, status=status.HTTP_200_OK)
                
            except Exception as e:
                logger.error(f"Comparison chart generation failed: {e}")
                return Response(
                    {'error': 'chart_generation_error', 'message': str(e)},
                    status=status.HTTP_500_INTERNAL_SERVER_ERROR
                )
        else:
            return Response(
                {'error': 'validation_error', 'message': 'Invalid input parameters', 'details': serializer.errors},
                status=status.HTTP_422_UNPROCESSABLE_ENTITY
            )
    
    def _generate_comparison_bar_data(self, jobs: List[CalculationJob]) -> Dict[str, Any]:
        """Generate bar chart data for comparing multiple calculations"""
        
        labels = [f"Calc {i+1}" for i in range(len(jobs))]
        total_costs = [job.result_data['total_cost'] for job in jobs]
        
        # Also include breakdown comparison if available
        series = [{
            'name': 'Total Cost (SAR)',
            'data': total_costs,
            'color': "#4ECDC4"
        }]
        
        # Add breakdown series if all jobs have breakdown data
        if all('breakdown' in job.result_data for job in jobs):
            breakdown_keys = self._get_common_breakdown_keys(jobs)
            colors = ["#FF6B6B", "#45B7D1", "#96CEB4", "#FFEAA7", "#DDA0DD"]
            
            for i, key in enumerate(breakdown_keys[:5]):  # Limit to 5 components
                breakdown_data = []
                for job in jobs:
                    breakdown = job.result_data['breakdown']
                    breakdown_data.append(breakdown.get(key, '0'))
                
                series.append({
                    'name': key.replace('_', ' ').title(),
                    'data': breakdown_data,
                    'color': colors[i % len(colors)]
                })
        
        return {
            'labels': labels,
            'series': series
        }
    
    def _generate_comparison_line_data(self, jobs: List[CalculationJob]) -> Dict[str, Any]:
        """Generate line chart data for trend analysis"""
        
        # Sort jobs by submission time
        sorted_jobs = sorted(jobs, key=lambda j: j.submitted_at)
        
        labels = [f"Calc {i+1}" for i in range(len(sorted_jobs))]
        total_costs = [job.result_data['total_cost'] for job in sorted_jobs]
        
        series = [{
            'name': 'Total Cost Trend (SAR)',
            'data': total_costs,
            'color': "#4ECDC4"
        }]
        
        return {
            'labels': labels,
            'series': series
        }
    
    def _get_common_breakdown_keys(self, jobs: List[CalculationJob]) -> List[str]:
        """Get breakdown keys common to all jobs"""
        
        if not jobs:
            return []
        
        # Start with keys from first job
        common_keys = set(jobs[0].result_data['breakdown'].keys())
        
        # Intersect with keys from all other jobs
        for job in jobs[1:]:
            if 'breakdown' in job.result_data:
                job_keys = set(job.result_data['breakdown'].keys())
                common_keys = common_keys.intersection(job_keys)
        
        # Filter out zero values and sort by importance
        important_keys = ['base_cost', 'total_cost', 'taxes', 'shipping', 'customs_duties']
        
        # Sort by importance, then alphabetically
        def sort_key(key):
            if key in important_keys:
                return (0, important_keys.index(key))
            else:
                return (1, key)
        
        return sorted(common_keys, key=sort_key)