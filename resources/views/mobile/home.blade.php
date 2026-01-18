{{-- resources/views/mobile/home.blade.php --}}
@extends('layouts.front')

@section('title', __('جمــــاركي'))

@section('content')
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    @php
        use Illuminate\Support\Facades\View;
        use Illuminate\Support\Str;

        $cards = [
            ['id' => 'import',     'title' => __('استيراد'),                'icon' => 'icon-import'],
            ['id' => 'export',     'title' => __('تصدير'),                  'icon' => 'icon-export'],
            ['id' => 'broker',     'title' => __('مستخلص جمركي'),          'icon' => 'icon-broker'],
            ['id' => 'manufact',   'title' => __('تصنيع'),                  'icon' => 'icon-factory'],
            ['id' => 'agent',      'title' => __('وكيل'),                   'icon' => 'icon-agent'],
            ['id' => 'containers', 'title' => __('بورصة أسعار الحاويات'),  'icon' => 'icon-containers'],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-4">
        @foreach ($cards as $c)
            @php
                // اسم ملف الـ view للأيقونة:
                $viewName = 'components.' . $c['icon'];           // مثال: components.icon-import
                // اسم المكوّن (alias) الذي يتوقعه x-dynamic-component:
                $componentAlias = Str::after($viewName, 'components.'); // مثال: icon-import
            @endphp

            <a id="{{ $c['id'] }}" href="#{{ $c['id'] }}"
               class="group bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition flex flex-col items-center justify-center text-center">

                {{-- الأيقونة --}}
                @if (View::exists($viewName))
                    <x-dynamic-component :component="$componentAlias" class="w-8 h-8 text-[#143B6E] mb-2" />
                @else
                    <span class="w-8 h-8 mb-2 rounded-full bg-white text-[#143B6E] grid place-items-center border">★</span>
                @endif

                {{-- العنوان --}}
                <span class="text-slate-800 font-medium leading-snug text-sm">{{ $c['title'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Placeholder للخريطة --}}
    <section class="mt-6">
        <div class="h-60 bg-slate-100 border rounded-2xl shadow-inner grid place-items-center text-slate-400">
            {{ __('خريطة (Placeholder)') }}
        </div>
    </section>
</section>
@endsection
