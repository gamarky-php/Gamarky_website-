"""
Views for calculation endpoints
عروض نقاط نهاية الحسابات
"""

from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from rest_framework.decorators import api_view
from django.shortcuts import get_object_or_404
from django.utils import timezone
from datetime import timedelta
import uuid
import logging

from compute_core import ImportCostCalculator, ExportCostCalculator, ManufacturingCostCalculator
from .models import CalculationJob, IdempotencyKey, CalculationCache
from .serializers import (
    ImportCostRequestSerializer, ExportCostRequestSerializer,
    ManufacturingCostRequestSerializer, JobRequestSerializer,
    CostResponseSerializer, JobResponseSerializer, JobStatusResponseSerializer
)
from .tasks import (
    calculate_import_cost_task, calculate_export_cost_task,
    calculate_manufacturing_cost_task, bulk_calculation_task
)


logger = logging.getLogger(__name__)


def get_client_ip(request):
    """Get client IP address from request"""
    x_forwarded_for = request.META.get('HTTP_X_FORWARDED_FOR')
    if x_forwarded_for:
        ip = x_forwarded_for.split(',')[0]
    else:
        ip = request.META.get('REMOTE_ADDR')
    return ip


class ImportCostCalculationView(APIView):
    """
    Calculate import cost synchronously
    حساب تكلفة الاستيراد بشكل متزامن
    """
    
    def post(self, request):
        serializer = ImportCostRequestSerializer(data=request.data)
        if serializer.is_valid():
            try:
                # Perform synchronous calculation
                calculator = ImportCostCalculator()
                result = calculator.calculate_import_cost(**serializer.validated_data)
                
                # Create a job record for tracking
                job = CalculationJob.objects.create(
                    calculation_type='import_cost',
                    status='completed',
                    input_parameters=serializer.validated_data,
                    user=request.user if request.user.is_authenticated else None,
                    ip_address=get_client_ip(request),
                    user_agent=request.META.get('HTTP_USER_AGENT', ''),
                    started_at=timezone.now(),
                    completed_at=timezone.now(),
                    progress_percentage=100
                )
                
                # Prepare response data
                response_data = {
                    'total_cost': str(result.total_cost),
                    'currency': 'SAR',
                    'breakdown': result.to_dict(),
                    'calculation_id': str(job.id),
                    'calculated_at': job.completed_at.isoformat()
                }
                
                # Save result to job
                job.result_data = response_data
                job.save()
                
                logger.info(f"Import cost calculation completed: {job.id}")
                return Response(response_data, status=status.HTTP_200_OK)
                
            except Exception as e:
                logger.error(f"Import cost calculation failed: {e}")
                return Response(
                    {'error': 'calculation_error', 'message': str(e)},
                    status=status.HTTP_500_INTERNAL_SERVER_ERROR
                )
        else:
            return Response(
                {'error': 'validation_error', 'message': 'Invalid input parameters', 'details': serializer.errors},
                status=status.HTTP_422_UNPROCESSABLE_ENTITY
            )


class ExportCostCalculationView(APIView):
    """
    Calculate export cost synchronously
    حساب تكلفة التصدير بشكل متزامن
    """
    
    def post(self, request):
        serializer = ExportCostRequestSerializer(data=request.data)
        if serializer.is_valid():
            try:
                # Perform synchronous calculation
                calculator = ExportCostCalculator()
                result = calculator.calculate_export_cost(**serializer.validated_data)
                
                # Create a job record for tracking
                job = CalculationJob.objects.create(
                    calculation_type='export_cost',
                    status='completed',
                    input_parameters=serializer.validated_data,
                    user=request.user if request.user.is_authenticated else None,
                    ip_address=get_client_ip(request),
                    user_agent=request.META.get('HTTP_USER_AGENT', ''),
                    started_at=timezone.now(),
                    completed_at=timezone.now(),
                    progress_percentage=100
                )
                
                # Prepare response data
                response_data = {
                    'total_cost': str(result.total_cost),
                    'currency': 'SAR',
                    'breakdown': result.to_dict(),
                    'calculation_id': str(job.id),
                    'calculated_at': job.completed_at.isoformat()
                }
                
                # Save result to job
                job.result_data = response_data
                job.save()
                
                logger.info(f"Export cost calculation completed: {job.id}")
                return Response(response_data, status=status.HTTP_200_OK)
                
            except Exception as e:
                logger.error(f"Export cost calculation failed: {e}")
                return Response(
                    {'error': 'calculation_error', 'message': str(e)},
                    status=status.HTTP_500_INTERNAL_SERVER_ERROR
                )
        else:
            return Response(
                {'error': 'validation_error', 'message': 'Invalid input parameters', 'details': serializer.errors},
                status=status.HTTP_422_UNPROCESSABLE_ENTITY
            )


class ManufacturingCostCalculationView(APIView):
    """
    Calculate manufacturing cost synchronously
    حساب تكلفة التصنيع بشكل متزامن
    """
    
    def post(self, request):
        serializer = ManufacturingCostRequestSerializer(data=request.data)
        if serializer.is_valid():
            try:
                # Perform synchronous calculation
                calculator = ManufacturingCostCalculator()
                result = calculator.calculate_manufacturing_cost(**serializer.validated_data)
                
                # Create a job record for tracking
                job = CalculationJob.objects.create(
                    calculation_type='manufacturing_cost',
                    status='completed',
                    input_parameters=serializer.validated_data,
                    user=request.user if request.user.is_authenticated else None,
                    ip_address=get_client_ip(request),
                    user_agent=request.META.get('HTTP_USER_AGENT', ''),
                    started_at=timezone.now(),
                    completed_at=timezone.now(),
                    progress_percentage=100
                )
                
                # Prepare response data
                response_data = {
                    'total_cost': str(result.total_cost),
                    'currency': 'SAR',
                    'breakdown': result.to_dict(),
                    'calculation_id': str(job.id),
                    'calculated_at': job.completed_at.isoformat()
                }
                
                # Save result to job
                job.result_data = response_data
                job.save()
                
                logger.info(f"Manufacturing cost calculation completed: {job.id}")
                return Response(response_data, status=status.HTTP_200_OK)
                
            except Exception as e:
                logger.error(f"Manufacturing cost calculation failed: {e}")
                return Response(
                    {'error': 'calculation_error', 'message': str(e)},
                    status=status.HTTP_500_INTERNAL_SERVER_ERROR
                )
        else:
            return Response(
                {'error': 'validation_error', 'message': 'Invalid input parameters', 'details': serializer.errors},
                status=status.HTTP_422_UNPROCESSABLE_ENTITY
            )


class JobSubmissionView(APIView):
    """
    Submit calculation jobs for async processing
    إرسال وظائف الحسابات للمعالجة غير المتزامنة
    """
    
    def post(self, request):
        serializer = JobRequestSerializer(data=request.data)
        if serializer.is_valid():
            try:
                calculation_type = serializer.validated_data['calculation_type']
                parameters = serializer.validated_data['parameters']
                idempotency_key = serializer.validated_data['idempotency_key']
                
                # Check for existing job with same idempotency key
                try:
                    existing_key = IdempotencyKey.objects.get(
                        key=str(idempotency_key),
                        expires_at__gt=timezone.now()
                    )
                    # Return existing job
                    existing_job = existing_key.calculation_job
                    response_data = {
                        'job_id': existing_job.id,
                        'status': existing_job.status,
                        'submitted_at': existing_job.submitted_at,
                    }
                    return Response(response_data, status=status.HTTP_202_ACCEPTED)
                    
                except IdempotencyKey.DoesNotExist:
                    pass
                
                # Create new job
                job = CalculationJob.objects.create(
                    calculation_type=calculation_type,
                    input_parameters=parameters,
                    user=request.user if request.user.is_authenticated else None,
                    ip_address=get_client_ip(request),
                    user_agent=request.META.get('HTTP_USER_AGENT', '')
                )
                
                # Create idempotency key (expires in 24 hours)
                IdempotencyKey.objects.create(
                    key=str(idempotency_key),
                    calculation_job=job,
                    expires_at=timezone.now() + timedelta(hours=24)
                )
                
                # Submit async task based on calculation type
                if calculation_type == 'bulk_import':
                    task = bulk_calculation_task.delay(
                        str(job.id), 'bulk_import', parameters['requests']
                    )
                elif calculation_type == 'bulk_export':
                    task = bulk_calculation_task.delay(
                        str(job.id), 'bulk_export', parameters['requests']
                    )
                elif calculation_type == 'complex_manufacturing':
                    # For complex manufacturing, process each scenario
                    scenarios = parameters['scenarios']
                    task = bulk_calculation_task.delay(
                        str(job.id), 'complex_manufacturing', scenarios
                    )
                else:
                    return Response(
                        {'error': 'invalid_calculation_type', 'message': f'Unsupported calculation type: {calculation_type}'},
                        status=status.HTTP_400_BAD_REQUEST
                    )
                
                # Update job with task ID
                job.celery_task_id = task.id
                job.save()
                
                # Prepare response
                response_data = {
                    'job_id': job.id,
                    'status': job.status,
                    'submitted_at': job.submitted_at,
                    'estimated_completion': job.submitted_at + timedelta(minutes=5)  # Rough estimate
                }
                
                logger.info(f"Job {job.id} submitted for {calculation_type}")
                return Response(response_data, status=status.HTTP_202_ACCEPTED)
                
            except Exception as e:
                logger.error(f"Job submission failed: {e}")
                return Response(
                    {'error': 'submission_error', 'message': str(e)},
                    status=status.HTTP_500_INTERNAL_SERVER_ERROR
                )
        else:
            return Response(
                {'error': 'validation_error', 'message': 'Invalid input parameters', 'details': serializer.errors},
                status=status.HTTP_422_UNPROCESSABLE_ENTITY
            )


class JobStatusView(APIView):
    """
    Get job status and results
    الحصول على حالة الوظيفة والنتائج
    """
    
    def get(self, request, job_id):
        try:
            job_uuid = uuid.UUID(job_id)
            job = get_object_or_404(CalculationJob, id=job_uuid)
            
            response_data = {
                'job_id': job.id,
                'status': job.status,
                'submitted_at': job.submitted_at,
                'progress_percentage': job.progress_percentage
            }
            
            # Add completion time if finished
            if job.completed_at:
                response_data['completed_at'] = job.completed_at
            
            # Add result if completed successfully
            if job.status == 'completed' and job.result_data:
                response_data['result'] = job.result_data
            
            # Add error message if failed
            if job.status == 'failed' and job.error_message:
                response_data['error_message'] = job.error_message
            
            logger.debug(f"Retrieved status for job {job_id}")
            return Response(response_data, status=status.HTTP_200_OK)
            
        except ValueError:
            return Response(
                {'error': 'invalid_job_id', 'message': 'Job ID must be a valid UUID'},
                status=status.HTTP_400_BAD_REQUEST
            )
        except Exception as e:
            logger.error(f"Failed to get job status for {job_id}: {e}")
            return Response(
                {'error': 'status_error', 'message': str(e)},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )