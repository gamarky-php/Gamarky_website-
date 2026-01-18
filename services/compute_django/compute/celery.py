"""
Celery configuration for Gamarky Compute Service
إعدادات Celery لخدمة الحسابات
"""

import os
from celery import Celery
from django.conf import settings

# Set the default Django settings module for the 'celery' program.
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'compute.settings')

app = Celery('compute')

# Using a string here means the worker doesn't have to serialize
# the configuration object to child processes.
app.config_from_object('django.conf:settings', namespace='CELERY')

# Load task modules from all registered Django apps.
app.autodiscover_tasks()

# Optional configuration, see the application user guide.
app.conf.update(
    task_routes={
        'apps.calc.tasks.calculate_import_cost_task': {'queue': 'calculations'},
        'apps.calc.tasks.calculate_export_cost_task': {'queue': 'calculations'},
        'apps.calc.tasks.calculate_manufacturing_cost_task': {'queue': 'calculations'},
        'apps.calc.tasks.bulk_calculation_task': {'queue': 'bulk_calculations'},
    },
    task_annotations={
        'apps.calc.tasks.bulk_calculation_task': {
            'rate_limit': '10/m',  # 10 per minute for bulk tasks
            'time_limit': 1800,    # 30 minutes
        }
    },
    worker_prefetch_multiplier=1,
    task_acks_late=True,
    worker_disable_rate_limits=False,
)


@app.task(bind=True)
def debug_task(self):
    """Debug task for testing Celery functionality"""
    print(f'Request: {self.request!r}')
    return 'Debug task completed successfully'