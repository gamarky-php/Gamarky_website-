<div dir="rtl">
    @if($layout === 'grid')
        <div class="grid grid-cols-1 {{ $this->getGridClasses() }} gap-4">
            @foreach($kpis as $kpi)
                @php
                    $colors = $this->getColorClasses($kpi['color'] ?? 'gray');
                    $iconSvg = $this->getIconSvg($kpi['icon'] ?? 'chart');
                @endphp

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 mb-1">{{ $kpi['title'] }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $kpi['value'] }}</p>
                            
                            @if(isset($kpi['change']))
                                <div class="mt-2 flex items-center gap-1">
                                    @if(($kpi['trend'] ?? 'neutral') === 'up')
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                        <span class="text-xs font-medium text-green-600">{{ $kpi['change'] }}</span>
                                    @elseif(($kpi['trend'] ?? 'neutral') === 'down')
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                        </svg>
                                        <span class="text-xs font-medium text-red-600">{{ $kpi['change'] }}</span>
                                    @else
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">{{ $kpi['change'] }}</span>
                                    @endif
                                </div>
                            @endif

                            @if(isset($kpi['description']))
                                <p class="text-xs text-gray-500 mt-1">{{ $kpi['description'] }}</p>
                            @endif
                        </div>

                        <div class="w-12 h-12 {{ $colors['bg'] }} rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $iconSvg !!}
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- List Layout --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-200">
            @foreach($kpis as $kpi)
                @php
                    $colors = $this->getColorClasses($kpi['color'] ?? 'gray');
                    $iconSvg = $this->getIconSvg($kpi['icon'] ?? 'chart');
                @endphp

                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 {{ $colors['bg'] }} rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $iconSvg !!}
                                </svg>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $kpi['title'] }}</p>
                                @if(isset($kpi['description']))
                                    <p class="text-xs text-gray-500">{{ $kpi['description'] }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            @if(isset($kpi['change']))
                                <div class="flex items-center gap-1">
                                    @if(($kpi['trend'] ?? 'neutral') === 'up')
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                        <span class="text-xs font-medium text-green-600">{{ $kpi['change'] }}</span>
                                    @elseif(($kpi['trend'] ?? 'neutral') === 'down')
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                        </svg>
                                        <span class="text-xs font-medium text-red-600">{{ $kpi['change'] }}</span>
                                    @else
                                        <span class="text-xs font-medium text-gray-600">{{ $kpi['change'] }}</span>
                                    @endif
                                </div>
                            @endif

                            <p class="text-lg font-bold text-gray-900">{{ $kpi['value'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
