#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Laravel Development Setup Script
    سكريبت إعداد التطوير لـ Laravel

.DESCRIPTION
    Performs standard Laravel cache clearing operations and starts development servers.
    This script ensures a clean development environment by clearing all caches and 
    provides options to start various development services.
    
    ينفذ عمليات تنظيف cache القياسية لـ Laravel ويبدأ خوادم التطوير.
    هذا السكريبت يضمن بيئة تطوير نظيفة عبر مسح جميع الكاش ويوفر
    خيارات لبدء خدمات التطوير المختلفة.

.PARAMETER Action
    The action to perform: 'clear', 'dev', 'build', or 'all'
    العملية المطلوب تنفيذها

.PARAMETER SkipDiagnostic
    Skip the diagnostic check after clearing caches
    تخطي فحص التشخيص بعد مسح الكاش

.EXAMPLE
    .\tools\setup.ps1 clear
    .\tools\setup.ps1 dev
    .\tools\setup.ps1 all

.NOTES
    Author: Gamarky Development Team
    Date: October 9, 2025
    Version: 1.0
#>

Param(
    [ValidateSet('clear', 'dev', 'build', 'all')]
    [string]$Action = 'all',
    [switch]$SkipDiagnostic
)

Write-Host "==================================================" -ForegroundColor Cyan
Write-Host "    Laravel Development Setup Script" -ForegroundColor Green
Write-Host "    سكريبت إعداد التطوير لـ Laravel" -ForegroundColor Green
Write-Host "==================================================" -ForegroundColor Cyan
Write-Host ""

# Function to run command with status reporting
function Invoke-SetupCommand {
    param(
        [string]$Command,
        [string]$Description,
        [string]$DescriptionAR = "",
        [switch]$Background
    )
    
    Write-Host "🔄 $Description" -ForegroundColor Yellow
    if ($DescriptionAR) {
        Write-Host "   $DescriptionAR" -ForegroundColor Gray
    }
    
    try {
        if ($Background) {
            $process = Start-Process -FilePath "cmd" -ArgumentList "/c", $Command -NoNewWindow -PassThru
            Write-Host "✅ Started in background (PID: $($process.Id))" -ForegroundColor Green
            return $process
        } else {
            $result = Invoke-Expression $Command
            if ($LASTEXITCODE -eq 0) {
                Write-Host "✅ Completed successfully" -ForegroundColor Green
            } else {
                Write-Host "❌ Failed with exit code $LASTEXITCODE" -ForegroundColor Red
                return $false
            }
        }
    } catch {
        Write-Host "❌ Error: $($_.Exception.Message)" -ForegroundColor Red
        return $false
    }
    
    Write-Host ""
    return $true
}

# Clear caches function
function Clear-LaravelCaches {
    Write-Host "1. Cache Clearing Operations" -ForegroundColor Yellow
    Write-Host "   عمليات مسح الكاش" -ForegroundColor Gray
    Write-Host ""
    
    $commands = @(
        @{
            Command = "php artisan view:clear"
            Description = "Clearing compiled views"
            DescriptionAR = "مسح المشاهدات المترجمة"
        },
        @{
            Command = "php artisan route:clear"
            Description = "Clearing route cache"
            DescriptionAR = "مسح كاش الراوتات"
        },
        @{
            Command = "php artisan config:clear"
            Description = "Clearing configuration cache"
            DescriptionAR = "مسح كاش الإعدادات"
        },
        @{
            Command = "php artisan optimize:clear"
            Description = "Clearing all optimization caches"
            DescriptionAR = "مسح جميع كاش التحسين"
        }
    )
    
    $allSucceeded = $true
    foreach ($cmd in $commands) {
        $result = Invoke-SetupCommand -Command $cmd.Command -Description $cmd.Description -DescriptionAR $cmd.DescriptionAR
        if (-not $result) {
            $allSucceeded = $false
        }
    }
    
    if ($allSucceeded) {
        Write-Host "🎉 All cache clearing operations completed successfully!" -ForegroundColor Green
        Write-Host "   جميع عمليات مسح الكاش تمت بنجاح!" -ForegroundColor Green
    } else {
        Write-Host "⚠️ Some cache clearing operations failed." -ForegroundColor Yellow
        Write-Host "   بعض عمليات مسح الكاش فشلت." -ForegroundColor Yellow
    }
    
    Write-Host ""
    return $allSucceeded
}

# Start development servers
function Start-DevelopmentServers {
    Write-Host "2. Starting Development Servers" -ForegroundColor Yellow
    Write-Host "   بدء خوادم التطوير" -ForegroundColor Gray
    Write-Host ""
    
    # Check if npm is available
    try {
        $null = Get-Command npm -ErrorAction Stop
    } catch {
        Write-Host "❌ npm is not installed or not in PATH" -ForegroundColor Red
        Write-Host "   npm غير مثبت أو غير موجود في PATH" -ForegroundColor Red
        return $false
    }
    
    # Start Vite dev server
    $viteResult = Invoke-SetupCommand -Command "npm run dev" -Description "Starting Vite development server" -DescriptionAR "بدء خادم التطوير Vite" -Background
    
    if ($viteResult) {
        Write-Host "🌐 Development servers started:" -ForegroundColor Green
        Write-Host "   خوادم التطوير بدأت:" -ForegroundColor Green
        Write-Host "   • Vite: http://localhost:5173/" -ForegroundColor Cyan
        Write-Host "   • Laravel: Run 'php artisan serve' in another terminal" -ForegroundColor Cyan
        Write-Host "   • Laravel: شغل 'php artisan serve' في terminal آخر" -ForegroundColor Gray
    }
    
    Write-Host ""
    return $viteResult
}

# Build for production
function Build-Production {
    Write-Host "2. Building for Production" -ForegroundColor Yellow
    Write-Host "   بناء للإنتاج" -ForegroundColor Gray
    Write-Host ""
    
    $result = Invoke-SetupCommand -Command "npm run build" -Description "Building assets for production" -DescriptionAR "بناء الأصول للإنتاج"
    
    if ($result) {
        Write-Host "🎯 Production build completed!" -ForegroundColor Green
        Write-Host "   بناء الإنتاج اكتمل!" -ForegroundColor Green
        Write-Host "   Assets are ready for deployment in public/build/" -ForegroundColor Cyan
        Write-Host "   الأصول جاهزة للنشر في public/build/" -ForegroundColor Gray
    }
    
    Write-Host ""
    return $result
}

# Run diagnostic
function Start-Diagnostic {
    Write-Host "3. Running System Diagnostic" -ForegroundColor Yellow
    Write-Host "   تشغيل تشخيص النظام" -ForegroundColor Gray
    Write-Host ""
    
    if (Test-Path ".\tools\diagnose.ps1") {
        try {
            $result = & ".\tools\diagnose.ps1"
            if ($LASTEXITCODE -eq 0) {
                Write-Host "✅ System diagnostic completed - All checks passed!" -ForegroundColor Green
                Write-Host "   تشخيص النظام اكتمل - جميع الفحوصات نجحت!" -ForegroundColor Green
            } elseif ($LASTEXITCODE -eq 2) {
                Write-Host "⚠️ System diagnostic completed with warnings" -ForegroundColor Yellow
                Write-Host "   تشخيص النظام اكتمل مع تحذيرات" -ForegroundColor Yellow
            } else {
                Write-Host "❌ System diagnostic found errors" -ForegroundColor Red
                Write-Host "   تشخيص النظام وجد أخطاء" -ForegroundColor Red
            }
        } catch {
            Write-Host "❌ Error running diagnostic: $($_.Exception.Message)" -ForegroundColor Red
        }
    } else {
        Write-Host "⚠️ Diagnostic tool not found at .\tools\diagnose.ps1" -ForegroundColor Yellow
        Write-Host "   أداة التشخيص غير موجودة في .\tools\diagnose.ps1" -ForegroundColor Yellow
    }
    
    Write-Host ""
}

# Main execution
switch ($Action) {
    'clear' {
        $clearResult = Clear-LaravelCaches
        if (-not $SkipDiagnostic -and $clearResult) {
            Start-Diagnostic
        }
    }
    'dev' {
        $clearResult = Clear-LaravelCaches
        if ($clearResult) {
            Start-DevelopmentServers
        }
        if (-not $SkipDiagnostic -and $clearResult) {
            Start-Diagnostic
        }
    }
    'build' {
        $clearResult = Clear-LaravelCaches
        if ($clearResult) {
            Build-Production
        }
        if (-not $SkipDiagnostic -and $clearResult) {
            Start-Diagnostic
        }
    }
    'all' {
        $clearResult = Clear-LaravelCaches
        if ($clearResult) {
            Write-Host "Choose next action:" -ForegroundColor Cyan
            Write-Host "اختر الإجراء التالي:" -ForegroundColor Gray
            Write-Host "  [D] Development mode (npm run dev)" -ForegroundColor Yellow
            Write-Host "  [B] Build for production (npm run build)" -ForegroundColor Yellow
            Write-Host "  [S] Skip and run diagnostic only" -ForegroundColor Yellow
            Write-Host ""
            
            $choice = Read-Host "Enter choice (D/B/S)"
            
            switch ($choice.ToUpper()) {
                'D' { Start-DevelopmentServers }
                'B' { Build-Production }
                'S' { Write-Host "Skipping build step..." -ForegroundColor Gray }
                default { 
                    Write-Host "Invalid choice, skipping build step..." -ForegroundColor Yellow
                    Write-Host "اختيار غير صحيح، تخطي خطوة البناء..." -ForegroundColor Gray
                }
            }
        }
        
        if (-not $SkipDiagnostic -and $clearResult) {
            Start-Diagnostic
        }
    }
}

Write-Host "==================================================" -ForegroundColor Cyan
Write-Host "           SETUP COMPLETE" -ForegroundColor White
Write-Host "           اكتمل الإعداد" -ForegroundColor White
Write-Host "==================================================" -ForegroundColor Cyan

Write-Host ""
Write-Host "📋 Next Steps:" -ForegroundColor Cyan
Write-Host "   الخطوات التالية:" -ForegroundColor Gray

if ($Action -eq 'dev') {
    Write-Host "   1. Vite dev server is running on http://localhost:5173/" -ForegroundColor Green
    Write-Host "   2. Start Laravel: php artisan serve" -ForegroundColor Yellow
    Write-Host "   3. Visit: http://localhost/mardini/public" -ForegroundColor Cyan
} elseif ($Action -eq 'build') {
    Write-Host "   1. Assets built and ready for production" -ForegroundColor Green
    Write-Host "   2. Deploy the public/build folder to your server" -ForegroundColor Yellow
    Write-Host "   3. Ensure APP_ENV=production in .env" -ForegroundColor Cyan
} else {
    Write-Host "   1. Run 'php artisan serve' to start Laravel" -ForegroundColor Yellow
    Write-Host "   2. Choose dev or build mode as needed" -ForegroundColor Yellow
    Write-Host "   3. Visit application URLs to test" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "🔧 Common Commands:" -ForegroundColor Cyan
Write-Host "   أوامر شائعة:" -ForegroundColor Gray
Write-Host "   • Clear only: .\tools\setup.ps1 clear" -ForegroundColor Gray
Write-Host "   • Development: .\tools\setup.ps1 dev" -ForegroundColor Gray
Write-Host "   • Production build: .\tools\setup.ps1 build" -ForegroundColor Gray
Write-Host "   • Full setup: .\tools\setup.ps1 all" -ForegroundColor Gray

Write-Host ""
Write-Host "==================================================" -ForegroundColor Cyan