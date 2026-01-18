# Laravel Livewire/Vite Diagnostic Tool

## Overview | نظرة عامة

A comprehensive diagnostic tool for Laravel applications using Livewire v3 and Vite, especially useful for subdirectory deployments and troubleshooting asset loading issues.

أداة تشخيص شاملة لتطبيقات Laravel التي تستخدم Livewire v3 و Vite، مفيدة خاصة للنشر في مجلدات فرعية واستكشاف مشاكل تحميل الأصول.

## Features | الميزات

✅ **Layout File Analysis** - Checks for proper @livewireStyles and @livewireScripts placement  
✅ **Environment Configuration** - Validates APP_URL and LIVEWIRE_ASSET_URL settings  
✅ **Livewire Configuration** - Verifies config/livewire.php setup  
✅ **Published Assets Check** - Ensures Livewire assets are properly published  
✅ **Vite Build Verification** - Confirms Vite assets are built correctly  
✅ **Network Accessibility** - Tests HTTP access to assets and application  
✅ **Page-Specific Scripts** - Validates individual page script loading via @push  

## Usage | الاستخدام

### Basic Usage | الاستخدام الأساسي
```powershell
# Run with default localhost URL
.\tools\diagnose.ps1

# تشغيل مع الرابط المحلي الافتراضي
.\tools\diagnose.ps1
```

### Custom URL | رابط مخصص
```powershell
# For subdirectory deployment
.\tools\diagnose.ps1 -BaseUrl "http://localhost/mardini/public"

# For production environment  
.\tools\diagnose.ps1 -BaseUrl "https://yourdomain.com/app"

# للنشر في مجلد فرعي
.\tools\diagnose.ps1 -BaseUrl "http://localhost/mardini/public"

# لبيئة الإنتاج
.\tools\diagnose.ps1 -BaseUrl "https://yourdomain.com/app"
```

## Exit Codes | رموز الخروج

- **0** - All checks passed (success) | جميع الفحوصات نجحت
- **1** - Errors found (requires action) | أخطاء موجودة (تتطلب إجراء)  
- **2** - Warnings found (recommended fixes) | تحذيرات موجودة (إصلاحات مستحسنة)

## Checks Performed | الفحوصات المنفذة

### 1. Layout File Checks | فحص ملف Layout
- ✅ Layout file exists (`resources/views/layouts/app.blade.php`)
- ✅ `@livewireStyles` present in `<head>`
- ✅ `@livewireScripts` present before `</body>`
- ✅ No page scripts directly in layout
- ✅ `@stack('scripts')` available for page-specific scripts

### 2. Environment Configuration | إعداد البيئة
- ✅ `.env` file exists
- ✅ `APP_URL` is configured
- ✅ `LIVEWIRE_ASSET_URL` is set (for subdirectory deployments)

### 3. Livewire Configuration | إعداد Livewire
- ✅ `config/livewire.php` exists
- ✅ `asset_url` configuration is present

### 4. Published Assets | الأصول المنشورة
- ✅ `public/vendor/livewire/livewire.js`
- ✅ `public/vendor/livewire/livewire.min.js`
- ✅ `public/vendor/livewire/manifest.json`

### 5. Vite Build Check | فحص بناء Vite
- ✅ `public/build/manifest.json` exists
- ✅ Main `app.js` is built
- ✅ Main `app.css` is built
- ✅ Build manifest is valid JSON

### 6. Network Accessibility | إمكانية الوصول للشبكة
- ✅ Application responds to HTTP requests
- ✅ Livewire assets are accessible via HTTP
- ⚠️ Graceful handling of network errors

### 7. Page-Specific Scripts | سكربتات الصفحات المخصصة
- ✅ Export calculator view and script files
- ✅ Manufacturing calculator view and script files  
- ✅ Proper usage of `@push('scripts')`
- ✅ Correct `@vite()` references

## Common Issues & Solutions | المشاكل الشائعة والحلول

### ❌ Layout file missing | ملف Layout مفقود
**Solution:** Ensure `resources/views/layouts/app.blade.php` exists  
**الحل:** تأكد من وجود `resources/views/layouts/app.blade.php`

### ⚠️ Missing @livewireStyles | مفقود @livewireStyles
**Solution:** Add `@livewireStyles` in the `<head>` section after `@vite()`  
**الحل:** أضف `@livewireStyles` في قسم `<head>` بعد `@vite()`

### ⚠️ Missing @livewireScripts | مفقود @livewireScripts
**Solution:** Add `@livewireScripts` before the closing `</body>` tag  
**الحل:** أضف `@livewireScripts` قبل إغلاق العلامة `</body>`

### ❌ Livewire assets missing | أصول Livewire مفقودة
**Solution:**
```bash
php artisan livewire:publish --assets
```

### ⚠️ Vite build missing | بناء Vite مفقود
**Solution:**
```bash
npm install
npm run build
```

### ⚠️ Network accessibility issues | مشاكل إمكانية الوصول للشبكة
**Solutions:**
- Ensure Laravel server is running: `php artisan serve`
- Check firewall/proxy settings
- Verify the BaseUrl parameter is correct

**الحلول:**
- تأكد من تشغيل خادم Laravel: `php artisan serve`
- فحص إعدادات الجدار الناري/البروكسي
- تحقق من صحة معامل BaseUrl

## Integration with CI/CD | التكامل مع CI/CD

```yaml
# GitHub Actions example
- name: Run Laravel Diagnostics
  run: |
    pwsh ./tools/diagnose.ps1 -BaseUrl "http://localhost:8000"
  shell: pwsh
```

```yaml
# Azure DevOps example
- task: PowerShell@2
  displayName: 'Laravel Diagnostics'
  inputs:
    targetType: 'filePath'
    filePath: './tools/diagnose.ps1'
    arguments: '-BaseUrl "$(app.url)"'
```

## Requirements | المتطلبات

- PowerShell 5.1+ or PowerShell Core 6+
- Laravel 8+ with Livewire v3
- Vite for asset compilation
- Network access for HTTP tests (optional)

## Example Output | مثال على الإخراج

```
==================================================
    Laravel Livewire/Vite Diagnostic Tool
==================================================

✅ Layout file exists
✅ @livewireStyles found in layout  
✅ @livewireScripts found in layout
✅ No page scripts in layout
✅ APP_URL configured
✅ LIVEWIRE_ASSET_URL configured
✅ Livewire config exists with asset_url
✅ Asset exists: livewire.js (339.4 KB)
✅ Vite build exists (4 assets in manifest)
✅ Livewire asset accessible (HTTP 200)
✅ Application accessible (HTTP 200)

🎉 ALL CHECKS PASSED!
✅ Your Laravel Livewire/Vite setup is correctly configured.
```

## Support | الدعم

If you encounter issues or need assistance:  
إذا واجهت مشاكل أو تحتاج مساعدة:

1. Run the diagnostic tool and review the output
2. Check the common issues section above
3. Ensure all requirements are met
4. Verify your Laravel and Livewire versions

## License | الترخيص

This tool is part of the Gamarky platform and is licensed under the same terms as the main application.

---

**Created by:** Gamarky Development Team  
**Date:** October 9, 2025  
**Version:** 1.0