"""
Celery tasks for async calculations
مهام Celery للحسابات غير المتزامنة
"""

from celery import shared_task, current_task
from django.utils import timezone
from datetime import timedelta
import logging
import traceback
import hashlib
import json
from typing import Dict, Any, List

from compute_core import ImportCostCalculator, ExportCostCalculator, ManufacturingCostCalculator
from .models import CalculationJob, CalculationCache


logger = logging.getLogger(__name__)


def generate_cache_key(calculation_type: str, parameters: Dict[str, Any]) -> str:
    """Generate cache key from calculation parameters"""
    # Create a deterministic hash of the parameters
    param_str = json.dumps(parameters, sort_keys=True)
    param_hash = hashlib.sha256(param_str.encode()).hexdigest()[:32]
    return f"{calculation_type}_{param_hash}"


def get_cached_result(calculation_type: str, parameters: Dict[str, Any]) -> Dict[str, Any]:
    """Get cached calculation result if available"""
    cache_key = generate_cache_key(calculation_type, parameters)
    
    try:
        cache_entry = CalculationCache.objects.get(
            cache_key=cache_key,
            expires_at__gt=timezone.now()
        )
        cache_entry.increment_access()
        logger.info(f"Cache hit for {calculation_type}: {cache_key}")
        return cache_entry.result_data
    except CalculationCache.DoesNotExist:
        logger.debug(f"Cache miss for {calculation_type}: {cache_key}")
        return None


def cache_result(calculation_type: str, parameters: Dict[str, Any], result: Dict[str, Any]):
    """Cache calculation result"""
    cache_key = generate_cache_key(calculation_type, parameters)
    input_hash = hashlib.sha256(json.dumps(parameters, sort_keys=True).encode()).hexdigest()
    
    # Cache for 1 hour by default
    expires_at = timezone.now() + timedelta(hours=1)
    
    CalculationCache.objects.update_or_create(
        cache_key=cache_key,
        defaults={
            'calculation_type': calculation_type,
            'input_hash': input_hash,
            'result_data': result,
            'expires_at': expires_at
        }
    )
    logger.info(f"Cached result for {calculation_type}: {cache_key}")


@shared_task(bind=True, name='calculate_import_cost_task')
def calculate_import_cost_task(self, job_id: str, parameters: Dict[str, Any]):
    """
    Async task to calculate import cost
    مهمة غير متزامنة لحساب تكلفة الاستيراد
    """
    try:
        job = CalculationJob.objects.get(id=job_id)
        job.set_processing(task_id=self.request.id)
        
        logger.info(f"Starting import cost calculation for job {job_id}")
        
        # Check cache first
        cached_result = get_cached_result('import_cost', parameters)
        if cached_result:
            job.set_completed(cached_result)
            return cached_result
        
        # Perform calculation
        calculator = ImportCostCalculator()
        result = calculator.calculate_import_cost(**parameters)
        
        # Convert to dict for JSON serialization
        result_data = {
            'total_cost': str(result.total_cost),
            'currency': 'SAR',
            'breakdown': result.to_dict(),
            'calculation_id': job_id,
            'calculated_at': timezone.now().isoformat()
        }
        
        # Cache and save result
        cache_result('import_cost', parameters, result_data)
        job.set_completed(result_data)
        
        logger.info(f"Completed import cost calculation for job {job_id}")
        return result_data
        
    except Exception as e:
        logger.error(f"Import cost calculation failed for job {job_id}: {e}")
        logger.error(traceback.format_exc())
        
        try:
            job = CalculationJob.objects.get(id=job_id)
            job.set_failed(str(e))
        except CalculationJob.DoesNotExist:
            pass
        
        raise


@shared_task(bind=True, name='calculate_export_cost_task')
def calculate_export_cost_task(self, job_id: str, parameters: Dict[str, Any]):
    """
    Async task to calculate export cost
    مهمة غير متزامنة لحساب تكلفة التصدير
    """
    try:
        job = CalculationJob.objects.get(id=job_id)
        job.set_processing(task_id=self.request.id)
        
        logger.info(f"Starting export cost calculation for job {job_id}")
        
        # Check cache first
        cached_result = get_cached_result('export_cost', parameters)
        if cached_result:
            job.set_completed(cached_result)
            return cached_result
        
        # Perform calculation
        calculator = ExportCostCalculator()
        result = calculator.calculate_export_cost(**parameters)
        
        # Convert to dict for JSON serialization  
        result_data = {
            'total_cost': str(result.total_cost),
            'currency': 'SAR',
            'breakdown': result.to_dict(),
            'calculation_id': job_id,
            'calculated_at': timezone.now().isoformat()
        }
        
        # Cache and save result
        cache_result('export_cost', parameters, result_data)
        job.set_completed(result_data)
        
        logger.info(f"Completed export cost calculation for job {job_id}")
        return result_data
        
    except Exception as e:
        logger.error(f"Export cost calculation failed for job {job_id}: {e}")
        logger.error(traceback.format_exc())
        
        try:
            job = CalculationJob.objects.get(id=job_id)
            job.set_failed(str(e))
        except CalculationJob.DoesNotExist:
            pass
        
        raise


@shared_task(bind=True, name='calculate_manufacturing_cost_task')
def calculate_manufacturing_cost_task(self, job_id: str, parameters: Dict[str, Any]):
    """
    Async task to calculate manufacturing cost
    مهمة غير متزامنة لحساب تكلفة التصنيع
    """
    try:
        job = CalculationJob.objects.get(id=job_id)
        job.set_processing(task_id=self.request.id)
        
        logger.info(f"Starting manufacturing cost calculation for job {job_id}")
        
        # Check cache first
        cached_result = get_cached_result('manufacturing_cost', parameters)
        if cached_result:
            job.set_completed(cached_result)
            return cached_result
        
        # Perform calculation
        calculator = ManufacturingCostCalculator()
        result = calculator.calculate_manufacturing_cost(**parameters)
        
        # Convert to dict for JSON serialization
        result_data = {
            'total_cost': str(result.total_cost),
            'currency': 'SAR',
            'breakdown': result.to_dict(),
            'calculation_id': job_id,
            'calculated_at': timezone.now().isoformat()
        }
        
        # Cache and save result
        cache_result('manufacturing_cost', parameters, result_data)
        job.set_completed(result_data)
        
        logger.info(f"Completed manufacturing cost calculation for job {job_id}")
        return result_data
        
    except Exception as e:
        logger.error(f"Manufacturing cost calculation failed for job {job_id}: {e}")
        logger.error(traceback.format_exc())
        
        try:
            job = CalculationJob.objects.get(id=job_id)
            job.set_failed(str(e))
        except CalculationJob.DoesNotExist:
            pass
        
        raise


@shared_task(bind=True, name='bulk_calculation_task')
def bulk_calculation_task(self, job_id: str, calculation_type: str, requests: List[Dict[str, Any]]):
    """
    Async task for bulk calculations
    مهمة غير متزامنة للحسابات المتعددة
    """
    try:
        job = CalculationJob.objects.get(id=job_id)
        job.set_processing(task_id=self.request.id)
        
        logger.info(f"Starting bulk {calculation_type} calculation for job {job_id} with {len(requests)} requests")
        
        results = []
        total_requests = len(requests)
        
        # Select appropriate calculator
        if calculation_type == 'bulk_import':
            calculator = ImportCostCalculator()
            calc_method = calculator.calculate_import_cost
        elif calculation_type == 'bulk_export':
            calculator = ExportCostCalculator()
            calc_method = calculator.calculate_export_cost
        else:
            raise ValueError(f"Unsupported bulk calculation type: {calculation_type}")
        
        # Process each request
        for i, request_params in enumerate(requests):
            try:
                # Update progress
                progress = int((i / total_requests) * 100)
                job.update_progress(progress)
                
                # Check cache first
                cache_key = f"{calculation_type.replace('bulk_', '')}"
                cached_result = get_cached_result(cache_key, request_params)
                
                if cached_result:
                    results.append(cached_result)
                else:
                    # Perform calculation
                    result = calc_method(**request_params)
                    
                    # Convert to dict
                    result_data = {
                        'total_cost': str(result.total_cost),
                        'currency': 'SAR',
                        'breakdown': result.to_dict(),
                        'calculation_id': str(job.id),
                        'calculated_at': timezone.now().isoformat()
                    }
                    
                    # Cache individual result
                    cache_result(cache_key, request_params, result_data)
                    results.append(result_data)
                
                logger.debug(f"Completed request {i+1}/{total_requests} for job {job_id}")
                
            except Exception as e:
                logger.error(f"Failed to process request {i+1} in job {job_id}: {e}")
                # Add error result but continue processing
                results.append({
                    'error': str(e),
                    'request_index': i,
                    'calculation_id': str(job.id)
                })
        
        # Prepare final result
        bulk_result_data = {
            'calculation_type': calculation_type,
            'total_requests': total_requests,
            'successful_calculations': len([r for r in results if 'error' not in r]),
            'failed_calculations': len([r for r in results if 'error' in r]),
            'results': results,
            'job_id': job_id,
            'calculated_at': timezone.now().isoformat()
        }
        
        job.set_completed(bulk_result_data)
        
        logger.info(f"Completed bulk {calculation_type} calculation for job {job_id}")
        return bulk_result_data
        
    except Exception as e:
        logger.error(f"Bulk calculation failed for job {job_id}: {e}")
        logger.error(traceback.format_exc())
        
        try:
            job = CalculationJob.objects.get(id=job_id)
            job.set_failed(str(e))
        except CalculationJob.DoesNotExist:
            pass
        
        raise


@shared_task(name='cleanup_expired_data')
def cleanup_expired_data():
    """
    Cleanup expired cache entries and idempotency keys
    تنظيف البيانات المنتهية الصلاحية
    """
    try:
        from .models import IdempotencyKey
        
        # Cleanup expired cache
        cache_deleted = CalculationCache.cleanup_expired()
        
        # Cleanup expired idempotency keys
        idempotency_deleted = IdempotencyKey.cleanup_expired()
        
        # Cleanup old completed jobs (older than 30 days)
        old_jobs_threshold = timezone.now() - timedelta(days=30)
        old_jobs_deleted = CalculationJob.objects.filter(
            status__in=['completed', 'failed'],
            completed_at__lt=old_jobs_threshold
        ).delete()[0]
        
        logger.info(f"Cleanup completed: {cache_deleted} cache entries, "
                   f"{idempotency_deleted} idempotency keys, {old_jobs_deleted} old jobs")
        
        return {
            'cache_deleted': cache_deleted,
            'idempotency_deleted': idempotency_deleted,
            'old_jobs_deleted': old_jobs_deleted
        }
        
    except Exception as e:
        logger.error(f"Cleanup task failed: {e}")
        raise