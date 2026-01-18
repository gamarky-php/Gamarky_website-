#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Laravel Livewire/Vite Diagnostic Tool
    أداة تشخيص Laravel Livewire/Vite

.DESCRIPTION
    Performs comprehensive checks for Livewire and Vite asset configuration
    in Laravel applications, especially for subdirectory deployments.
    
    ينفذ فحوصات شاملة لإعداد أصول Livewire و Vite في تطبيقات Laravel،
    خاصة للنشر في مجلدات فرعية.

.PARAMETER BaseUrl
    Base URL of the Laravel application
    الرابط الأساسي لتطبيق Laravel

.EXAMPLE
    .\tools\diagnose.ps1
    .\tools\diagnose.ps1 -BaseUrl "http://localhost/mardini/public"
    .\tools\diagnose.ps1 -BaseUrl "https://example.com/app"

.NOTES
    Author: Gamarky Development Team
    Date: October 9, 2025
    Version: 1.0
#>

Param(
    [string]$BaseUrl = "http://localhost/mardini/public"
)

Write-Host "==================================================" -ForegroundColor Cyan
Write-Host "    Laravel Livewire/Vite Diagnostic Tool" -ForegroundColor Green
Write-Host "    أداة تشخيص Laravel Livewire/Vite" -ForegroundColor Green
Write-Host "==================================================" -ForegroundColor Cyan
Write-Host ""

# Initialize counters
$errorCount = 0
$warningCount = 0

function Write-Check {
    param($Message, $Status, $Details = "")
    
    switch ($Status) {
        "OK" { 
            Write-Host "✅ $Message" -ForegroundColor Green
            if ($Details) { Write-Host "   $Details" -ForegroundColor Gray }
        }
        "WARNING" { 
            Write-Host "⚠️  $Message" -ForegroundColor Yellow
            if ($Details) { Write-Host "   $Details" -ForegroundColor Gray }
            $script:warningCount++
        }
        "ERROR" { 
            Write-Host "❌ $Message" -ForegroundColor Red
            if ($Details) { Write-Host "   $Details" -ForegroundColor Gray }
            $script:errorCount++
        }
        "INFO" {
            Write-Host "ℹ️  $Message" -ForegroundColor Cyan
            if ($Details) { Write-Host "   $Details" -ForegroundColor Gray }
        }
    }
}

# 1. Check Layout File
Write-Host "1. Layout File Checks" -ForegroundColor Yellow
Write-Host "   فحص ملف Layout" -ForegroundColor Gray
Write-Host ""

$layout = "resources/views/layouts/app.blade.php"
if (!(Test-Path $layout)) { 
    Write-Check "Layout file missing" "ERROR" "File: $layout"
    exit 1
} else {
    Write-Check "Layout file exists" "OK" "File: $layout"
}

$content = Get-Content $layout -Raw

# Check @livewireStyles in <head>
if ($content -notmatch '@livewireStyles') { 
    Write-Check "@livewireStyles missing in <head>" "WARNING" "Add @livewireStyles after @vite() in <head> section"
} else {
    Write-Check "@livewireStyles found in layout" "OK"
}

# Check @livewireScripts before </body>
if ($content -notmatch '@livewireScripts') { 
    Write-Check "@livewireScripts missing before </body>" "WARNING" "Add @livewireScripts before closing </body> tag"
} else {
    Write-Check "@livewireScripts found in layout" "OK"
}

# Check for page scripts in layout (should not be there)
if ($content -match 'export-calculator\.js' -or $content -match 'mfg-calculator\.js') { 
    Write-Check "Page scripts found in layout" "WARNING" "Page scripts should be pushed via @stack('scripts'), not in layout"
} else {
    Write-Check "No page scripts in layout" "OK" "Page scripts properly separated"
}

# Check @stack('scripts') exists
if ($content -match "@stack\('scripts'\)") {
    Write-Check "@stack('scripts') found" "OK" "Allows pages to push their specific scripts"
} else {
    Write-Check "@stack('scripts') missing" "WARNING" "Add @stack('scripts') before @livewireScripts"
}

Write-Host ""

# 2. Environment Configuration
Write-Host "2. Environment Configuration" -ForegroundColor Yellow
Write-Host "   إعداد متغيرات البيئة" -ForegroundColor Gray
Write-Host ""

if (!(Test-Path ".env")) {
    Write-Check ".env file missing" "ERROR" "Create .env file from .env.example"
} else {
    $env = Get-Content ".env" -Raw
    
    # Check APP_URL
    if ($env -notmatch 'APP_URL=') { 
        Write-Check "APP_URL missing in .env" "WARNING" "Add APP_URL=http://localhost/mardini/public"
    } else { 
        $appUrl = ($env -split "`n") | Where-Object {$_ -match '^APP_URL='} | ForEach-Object {$_.Trim()}
        Write-Check "APP_URL configured" "OK" "$appUrl"
    }
    
    # Check LIVEWIRE_ASSET_URL
    if ($env -notmatch 'LIVEWIRE_ASSET_URL=') { 
        Write-Check "LIVEWIRE_ASSET_URL missing in .env" "WARNING" "Add LIVEWIRE_ASSET_URL=/mardini/public for subdirectory deployment"
    } else { 
        $livewireUrl = ($env -split "`n") | Where-Object {$_ -match '^LIVEWIRE_ASSET_URL='} | ForEach-Object {$_.Trim()}
        Write-Check "LIVEWIRE_ASSET_URL configured" "OK" "$livewireUrl"
    }
}

Write-Host ""

# 3. Livewire Configuration
Write-Host "3. Livewire Configuration" -ForegroundColor Yellow
Write-Host "   إعداد Livewire" -ForegroundColor Gray
Write-Host ""

$livewireConfig = "config/livewire.php"
if (!(Test-Path $livewireConfig)) {
    Write-Check "Livewire config missing" "WARNING" "Create config/livewire.php or run php artisan vendor:publish --provider='Livewire\LivewireServiceProvider' --tag='config'"
} else {
    $configContent = Get-Content $livewireConfig -Raw
    if ($configContent -match 'asset_url') {
        Write-Check "Livewire config exists with asset_url" "OK" "File: $livewireConfig"
    } else {
        Write-Check "Livewire config missing asset_url" "WARNING" "Add 'asset_url' => env('LIVEWIRE_ASSET_URL', null) to config"
    }
}

Write-Host ""

# 4. Published Assets Check
Write-Host "4. Published Assets Check" -ForegroundColor Yellow
Write-Host "   فحص الأصول المنشورة" -ForegroundColor Gray
Write-Host ""

$livewireAssets = @(
    "public/vendor/livewire/livewire.js",
    "public/vendor/livewire/livewire.min.js",
    "public/vendor/livewire/manifest.json"
)

foreach ($asset in $livewireAssets) {
    if (Test-Path $asset) { 
        $size = (Get-Item $asset).Length
        $sizeKB = [math]::Round($size / 1KB, 1)
        Write-Check "Asset exists: $(Split-Path $asset -Leaf)" "OK" "$asset (${sizeKB} KB)"
    } else { 
        Write-Check "Asset missing: $(Split-Path $asset -Leaf)" "ERROR" "$asset - Run: php artisan livewire:publish --assets"
    }
}

Write-Host ""

# 5. Vite Build Check
Write-Host "5. Vite Build Check" -ForegroundColor Yellow
Write-Host "   فحص بناء Vite" -ForegroundColor Gray
Write-Host ""

$buildManifest = "public/build/manifest.json"
if (!(Test-Path $buildManifest)) {
    Write-Check "Vite build missing" "WARNING" "Run: npm run build"
} else {
    try {
        $manifest = Get-Content $buildManifest -Raw | ConvertFrom-Json
        $assetCount = ($manifest | Get-Member -MemberType NoteProperty).Count
        Write-Check "Vite build exists" "OK" "$assetCount assets in manifest"
        
        # Check for main app assets
        $hasApp = $manifest.PSObject.Properties.Name | Where-Object { $_ -match "resources/js/app.js" }
        if ($hasApp) {
            Write-Check "Main app.js built" "OK" "Entry point exists"
        } else {
            Write-Check "Main app.js missing from build" "WARNING" "Check vite.config.js input configuration"
        }
        
        $hasAppCss = $manifest.PSObject.Properties.Name | Where-Object { $_ -match "resources/css/app.css" }
        if ($hasAppCss) {
            Write-Check "Main app.css built" "OK" "Styles compiled"
        } else {
            Write-Check "Main app.css missing from build" "WARNING" "Check vite.config.js input configuration"
        }
        
    } catch {
        Write-Check "Invalid build manifest" "ERROR" "Manifest JSON is corrupted - Run: npm run build"
    }
}

Write-Host ""

# 6. Network Accessibility Test
Write-Host "6. Network Accessibility Test" -ForegroundColor Yellow
Write-Host "   اختبار إمكانية الوصول للشبكة" -ForegroundColor Gray
Write-Host ""

Write-Check "Testing base URL" "INFO" $BaseUrl

# Test Livewire asset
$livewireUrl = "$BaseUrl/vendor/livewire/livewire.js"
try {
    $res = Invoke-WebRequest -Uri $livewireUrl -UseBasicParsing -Method Head -TimeoutSec 5
    Write-Check "Livewire asset accessible" "OK" "HEAD $livewireUrl -> $($res.StatusCode)"
} catch { 
    Write-Check "Cannot reach Livewire asset" "WARNING" "URL: $livewireUrl - Check if Laravel server is running"
}

# Test main application
try {
    $res = Invoke-WebRequest -Uri $BaseUrl -UseBasicParsing -Method Head -TimeoutSec 5
    Write-Check "Application accessible" "OK" "HEAD $BaseUrl -> $($res.StatusCode)"
} catch { 
    Write-Check "Cannot reach application" "WARNING" "URL: $BaseUrl - Check if Laravel server is running"
}

Write-Host ""

# 7. Page-Specific Script Check
Write-Host "7. Page-Specific Scripts Check" -ForegroundColor Yellow
Write-Host "   فحص سكربتات الصفحات المخصصة" -ForegroundColor Gray
Write-Host ""

$pageScripts = @{
    "Export Calculator" = @{
        "view" = "resources/views/front/export/calculator.blade.php"
        "script" = "resources/js/export-calculator.js"
    }
    "Manufacturing Calculator" = @{
        "view" = "resources/views/front/mfg/calculator.blade.php" 
        "script" = "resources/js/mfg-calculator.js"
    }
}

foreach ($page in $pageScripts.Keys) {
    $viewFile = $pageScripts[$page]["view"]
    $scriptFile = $pageScripts[$page]["script"]
    
    Write-Host "   ${page}:" -ForegroundColor Cyan
    
    # Check if view file exists
    if (Test-Path $viewFile) {
        $viewContent = Get-Content $viewFile -Raw
        
        # Check if it uses @push('scripts')
        if ($viewContent -match "@push\('scripts'\)") {
            Write-Check "Uses @push('scripts')" "OK"
        } else {
            Write-Check "Missing @push('scripts')" "WARNING" "Add @push('scripts') section"
        }
        
        # Check if it references the correct script file
        if ($viewContent -match [regex]::Escape($scriptFile)) {
            Write-Check "References correct script file" "OK"
        } else {
            Write-Check "Missing script reference" "WARNING" "Add @vite('$scriptFile') in @push section"
        }
    } else {
        Write-Check "View file missing" "WARNING" "File: $viewFile"
    }
    
    # Check if script file exists
    if (Test-Path $scriptFile) {
        $size = (Get-Item $scriptFile).Length
        $sizeKB = [math]::Round($size / 1KB, 1)
        Write-Check "Script file exists" "OK" "$scriptFile (${sizeKB} KB)"
    } else {
        Write-Check "Script file missing" "ERROR" "File: $scriptFile"
    }
    
    Write-Host ""
}

# 8. Summary and Recommendations
Write-Host ""
Write-Host "==================================================" -ForegroundColor Cyan
Write-Host "           DIAGNOSTIC SUMMARY" -ForegroundColor White
Write-Host "           ملخص التشخيص" -ForegroundColor White  
Write-Host "==================================================" -ForegroundColor Cyan

if ($errorCount -eq 0 -and $warningCount -eq 0) {
    Write-Host "🎉 ALL CHECKS PASSED!" -ForegroundColor Green
    Write-Host "   جميع الفحوصات تمت بنجاح!" -ForegroundColor Green
    Write-Host ""
    Write-Host "✅ Your Laravel Livewire/Vite setup is correctly configured." -ForegroundColor Green
    Write-Host "✅ إعداد Laravel Livewire/Vite صحيح ومكتمل." -ForegroundColor Green
} else {
    Write-Host "⚠️  ISSUES FOUND:" -ForegroundColor Yellow
    Write-Host "   تم العثور على مشاكل:" -ForegroundColor Yellow
    Write-Host "   - Errors: $errorCount" -ForegroundColor Red
    Write-Host "   - Warnings: $warningCount" -ForegroundColor Yellow
    Write-Host ""
    
    if ($errorCount -gt 0) {
        Write-Host "🔧 REQUIRED ACTIONS:" -ForegroundColor Red
        Write-Host "   إجراءات مطلوبة:" -ForegroundColor Red
        Write-Host "   1. Fix all ERROR items above" -ForegroundColor Red
        Write-Host "   2. Run diagnostic again to verify" -ForegroundColor Red
    }
    
    if ($warningCount -gt 0) {
        Write-Host "💡 RECOMMENDED ACTIONS:" -ForegroundColor Yellow
        Write-Host "   إجراءات مستحسنة:" -ForegroundColor Yellow
        Write-Host "   1. Review WARNING items above" -ForegroundColor Yellow
        Write-Host "   2. Follow the suggested fixes" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "📋 QUICK COMMANDS:" -ForegroundColor Cyan
Write-Host "   أوامر سريعة:" -ForegroundColor Gray
Write-Host "   • Publish Livewire assets: php artisan livewire:publish --assets" -ForegroundColor Gray
Write-Host "   • Build Vite assets: npm run build" -ForegroundColor Gray
Write-Host "   • Clear cache: php artisan config:clear && php artisan view:clear" -ForegroundColor Gray
Write-Host "   • Start server: php artisan serve" -ForegroundColor Gray

Write-Host ""
Write-Host "🔗 TEST URLS:" -ForegroundColor Cyan
Write-Host "   روابط الاختبار:" -ForegroundColor Gray
Write-Host "   • Application: $BaseUrl" -ForegroundColor Gray
Write-Host "   • Livewire Asset: $BaseUrl/vendor/livewire/livewire.js" -ForegroundColor Gray
Write-Host "   • Export Calculator: $BaseUrl/export/calculator" -ForegroundColor Gray
Write-Host "   • Manufacturing Calculator: $BaseUrl/manufacturing/calculator" -ForegroundColor Gray

Write-Host ""
Write-Host "==================================================" -ForegroundColor Cyan

# Exit with appropriate code
if ($errorCount -gt 0) {
    exit 1
} elseif ($warningCount -gt 0) {
    exit 2
} else {
    exit 0
}