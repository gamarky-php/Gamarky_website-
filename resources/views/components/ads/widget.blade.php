@props(['ads' => collect()])

<div class="rounded-lg shadow-md p-4 bg-white">
    <h3 class="font-semibold text-lg mb-4 text-gray-800">{{ __('إعلانات الخدمات') }}</h3>

    @forelse($ads as $ad)
        <div class="mb-4 last:mb-0">
            <a href="{{ route('ads.click', $ad) }}" class="block group">
                <div class="rounded-lg overflow-hidden shadow-sm border hover:shadow-md transition-shadow">
                    {{-- صورة الإعلان --}}
                    <img 
                        src="{{ $ad->image_path ? asset('storage/' . $ad->image_path) : asset('img/placeholder-ad.svg') }}" 
                        alt="{{ $ad->title }}" 
                        class="w-full h-28 object-cover group-hover:opacity-95 transition-opacity"
                    >
                    
                    {{-- محتوى البطاقة --}}
                    <div class="p-3">
                        <h4 class="font-medium text-gray-900 text-sm mb-1 line-clamp-2">
                            {{ $ad->title }}
                        </h4>
                        
                        @if($ad->supplier)
                            <p class="text-xs text-gray-600">
                                {{ $ad->supplier->name }}
                            </p>
                        @endif
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="text-center py-8 text-gray-500">
            <p class="text-sm">{{ __('لا توجد إعلانات متاحة حالياً') }}</p>
        </div>
    @endforelse
</div>

