<div class="bg-white rounded-2xl border p-5">
  @if(isset($title))
    <div class="text-lg font-semibold mb-3">{{ $title }}</div>
  @endif
  <div class="text-sm text-gray-700">
    {{ $slot }}
  </div>
</div>

