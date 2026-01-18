<div class="container mx-auto px-4 py-8" dir="rtl">
    
    {{-- SEARCH FORM --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form wire:submit.prevent="trackShipment" class="flex gap-4">
            <div class="flex-1">
                <input type="text" wire:model="tracking_number" placeholder="أدخل رقم التتبع (مثلاً: TRK123456)"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right text-lg">
                @error('tracking_number') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>
            <button type="submit" wire:loading.attr="disabled"
                class="px-12 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg font-bold hover:shadow-xl transition-all disabled:opacity-50">
                <i class="fas fa-search ml-2"></i>
                <span wire:loading.remove>تتبع</span>
                <span wire:loading>جاري البحث...</span>
            </button>
        </form>
    </div>

    {{-- TRACKING RESULTS --}}
    @if($searchPerformed && $trackingData)
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            {{-- Status Card --}}
            <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold">الحالة</h3>
                    <i class="fas fa-truck text-2xl opacity-70"></i>
                </div>
                <p class="text-lg font-bold">{{ $trackingData['status'] }}</p>
            </div>

            {{-- Location Card --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-sm font-bold text-gray-800 mb-2">الموقع الحالي</h3>
                <p class="text-sm text-gray-600">{{ $trackingData['current_location'] }}</p>
            </div>

            {{-- Speed Card --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-bold text-gray-800">السرعة</h3>
                    <i class="fas fa-tachometer-alt text-teal-500"></i>
                </div>
                <p class="text-2xl font-bold text-teal-600">{{ $trackingData['speed_kmh'] }} <span class="text-sm">كم/س</span></p>
            </div>

            {{-- ETA Card --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-sm font-bold text-gray-800 mb-2">الوصول المتوقع</h3>
                <p class="text-sm font-semibold text-teal-600">{{ $trackingData['estimated_arrival'] }}</p>
            </div>
        </div>

        {{-- DRIVER INFO & MAP --}}
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            {{-- Driver Info --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-[#0F2E5D] mb-4 flex items-center">
                    <i class="fas fa-user-circle ml-2 text-teal-500"></i>
                    معلومات السائق
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">الاسم:</span>
                        <span class="font-semibold">{{ $trackingData['driver_name'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">الهاتف:</span>
                        <a href="tel:{{ $trackingData['driver_phone'] }}" class="font-semibold text-teal-600 hover:underline">
                            {{ $trackingData['driver_phone'] }}
                        </a>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">لوحة الشاحنة:</span>
                        <span class="font-semibold">{{ $trackingData['truck_plate'] }}</span>
                    </div>
                </div>
            </div>

            {{-- MAP Placeholder --}}
            <div class="md:col-span-2 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-[#0F2E5D] mb-4 flex items-center">
                    <i class="fas fa-map-marked-alt ml-2 text-teal-500"></i>
                    الخريطة المباشرة
                </h3>
                <div id="map" class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <i class="fas fa-map text-4xl mb-2"></i>
                        <p>سيتم إضافة خريطة Google Maps هنا</p>
                        <p class="text-xs">Lat: {{ $trackingData['lat'] }}, Lng: {{ $trackingData['lng'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- PROGRESS BAR --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-[#0F2E5D] mb-4">نسبة الإنجاز</h3>
            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-teal-600 bg-teal-200">
                        {{ $trackingData['progress_percentage'] }}%
                    </span>
                </div>
                <div class="overflow-hidden h-4 mb-4 text-xs flex rounded-full bg-gray-200">
                    <div style="width:{{ $trackingData['progress_percentage'] }}%" 
                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-teal-500 to-teal-600 transition-all duration-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- EVENTS TIMELINE --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-[#0F2E5D] mb-6">سجل الرحلة</h3>
            <div class="space-y-4">
                @foreach($trackingData['events'] as $event)
                    <div class="flex items-center gap-4 bg-gray-50 rounded-lg p-4">
                        <div class="w-16 text-center">
                            <span class="text-sm font-bold text-teal-600">{{ $event['time'] }}</span>
                        </div>
                        <div class="w-1 h-12 bg-teal-500 rounded-full"></div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800">{{ $event['status'] }}</h4>
                            <p class="text-sm text-gray-600">{{ $event['location'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
