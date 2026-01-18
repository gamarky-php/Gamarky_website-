"""
WSGI config for compute service
إعداد WSGI لخدمة الحسابات
"""

import os
from django.core.wsgi import get_wsgi_application

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'compute.settings')

application = get_wsgi_application()