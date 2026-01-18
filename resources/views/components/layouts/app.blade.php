{{--
╔═══════════════════════════════════════════════════════════════════════╗
║  Livewire Layout Bridge                                               ║
║  Purpose: يربط Livewire components مع Dashboard Layout الأساسي       ║
║  Path: components.layouts.app → layouts.dashboard                    ║
║  Created: 2026-01-14                                                  ║
╚═══════════════════════════════════════════════════════════════════════╝
--}}

@extends('layouts.dashboard')

@section('content')
    {{ $slot }}
@endsection
