<div class="space-y-3">
  <div class="text-sm text-gray-500 mb-1">{{ __('dashboard.admin.main_sections') }}</div>

  @php
    $items = [
      ['label'=>__('nav.import'), 'route'=>'admin.import.index'],
      ['label'=>__('nav.export'), 'route'=>'admin.export.index'],
      ['label'=>__('nav.manufacturing'), 'route'=>'admin.manufacturing.index'],
      ['label'=>__('nav.customs_broker'), 'route'=>'admin.customs.index'],
      ['label'=>__('nav.containers'), 'route'=>'admin.containers.index'],
      ['label'=>__('nav.agent'), 'route'=>'admin.agents.index'],
    ];
  @endphp

  <nav class="space-y-1">
    @foreach ($items as $it)
      @php $active = request()->routeIs($it['route'].'*'); @endphp

      <a href="{{ route($it['route']) }}"
         class="block px-3 py-2 rounded-xl text-sm
         {{ $active ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
        {{ $it['label'] }}
      </a>

    @endforeach
  </nav>
</div>
