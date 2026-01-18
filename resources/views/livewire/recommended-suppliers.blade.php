<div class="rounded-xl bg-white shadow-sm p-4 md:p-5" dir="rtl">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold">المورِّدون الموصى بهم</h3>
        @if (Route::has('admin.suppliers.index'))
            <a href="{{ route('admin.suppliers.index') }}" class="text-sm text-blue-600 hover:underline">عرض الكل</a>
        @endif
    </div>

    @if($suppliers->isEmpty())
        <div class="text-sm text-slate-500">لا توجد بيانات مناسبة الآن.</div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($suppliers as $sup)
                @php
                    // prefer mobile_phone then tel for call; build cleaned WA link if possible
                    $raw = $sup->mobile_phone ?: $sup->tel ?: null;
                    $digits = $raw ? preg_replace('/\D+/', '', $raw) : null;
                    // If number starts with 00, strip leading zeros for wa.me; keep leading + removed by preg_replace
                    if ($digits && str_starts_with($digits, '00')) { $digits = ltrim($digits, '0'); }
                    $waLink = $digits ? 'https://wa.me/' . $digits : null;
                    $callNumber = $digits ? $digits : ($raw ?? null);
                @endphp

                <div class="border rounded-lg p-3 flex flex-col justify-between h-full">
                    <div>
                        <div class="font-medium text-slate-800 truncate">{{ $sup->company_name ?? '—' }}</div>
                        <div class="text-sm text-slate-500 mt-1 truncate">{{ $sup->country_code ? strtoupper($sup->country_code) : '' }}{{ $sup->city ? '، ' . $sup->city : '' }}</div>
                    </div>

                    <div class="mt-3 flex items-center gap-2">
                        @if($waLink)
                            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm">
                                {{-- WhatsApp icon (small) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"><path d="M20.5 3.5A11 11 0 0012 1C6.48 1 2 5.48 2 11c0 1.93.56 3.73 1.53 5.26L2 23l6.9-1.78A10.95 10.95 0 0012 23a11 11 0 008.5-19.5zM12 21.5c-.9 0-1.78-.14-2.6-.4l-.18-.06L6 21l.98-3.4-.12-.19A8.5 8.5 0 013.5 11 8.5 8.5 0 0112 2.5 8.5 8.5 0 0120.5 11 8.5 8.5 0 0112 21.5z"/></svg>
                                واتساب
                            </a>
                        @else
                            <span class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded-md bg-slate-50 text-slate-400 border border-slate-100 text-sm">واتساب</span>
                        @endif

                        @if($callNumber)
                            <a href="tel:{{ $callNumber }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-slate-50 text-slate-700 border border-slate-100 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 01.95-.27 11.36 11.36 0 003.55.57 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h2.5a1 1 0 011 1 11.36 11.36 0 00.57 3.55 1 1 0 01-.27.95l-2.18 2.29z"/></svg>
                                اتصال
                            </a>
                        @else
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-slate-50 text-slate-400 border border-slate-100 text-sm">اتصال</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
