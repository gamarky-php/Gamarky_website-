"""
ASGI config for compute service
إعداد ASGI لخدمة الحسابات
"""

import os
from django.core.asgi import get_asgi_application

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'compute.settings')

application = get_asgi_application()