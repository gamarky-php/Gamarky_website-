{{-- 
╔═══════════════════════════════════════════════════════════════════════╗
║  Multi-language Font Loading Partial                                  ║
║  Purpose: Centralized font configuration for ar/en/zh                 ║
║  Usage: @include('layouts.partials.fonts')                            ║
║  Version: 1.0.0 | Date: 2026-03-02                                    ║
╚═══════════════════════════════════════════════════════════════════════╝
--}}

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

@localeIs('ar')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
@elselocaleIs('zh')
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
@else
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endlocaleIs

<style>
    [x-cloak] { display: none !important; }
    body {
        font-family: @localeIs('ar') 'Cairo', sans-serif @elselocaleIs('zh') 'Noto Sans SC', sans-serif @else 'Inter', sans-serif @endlocaleIs;
    }
</style>
