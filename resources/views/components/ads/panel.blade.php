@props(['cards'=>[]])

@if(!empty($cards))
<div class="rounded-2xl shadow-xl overflow-hidden bg-gradient-to-b from-[#6E7CF2] to-[#6A42C1] reveal">
  <div class="flex items-center justify-between px-4 py-3 text-white">
    <h3 class="text-sm font-semibold">إعلانات الخدمات</h3>
    <span class="opacity-90">📣</span>
  </div>

  <div class="p-3 space-y-3">
    @foreach($cards as $card)
      <div class="rounded-2xl p-3 bg-gradient-to-b from-[#7E7AF5] to-[#6B3FB4] shadow-lg">
        <div class="bg-white/95 rounded-xl p-3 h-[154px] flex flex-col">
          <div class="flex items-center justify-between mb-2">
            <div class="text-slate-800 font-semibold line-clamp-1">{{ $card['title'] }}</div>
            <span class="text-{{ $card['color'] ?? 'indigo' }}-600 text-base">◆</span>
          </div>

          <div class="text-[13px] leading-5 text-slate-700 grow overflow-hidden">
            @foreach(array_slice($card['lines'] ?? [],0,3) as $line)
              <div class="line-clamp-1">• {{ $line }}</div>
            @endforeach
          </div>

          <div class="pt-2">
            <a href="{{ $card['cta']['url'] ?? '#' }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-white
                      bg-indigo-600 hover:bg-indigo-700 transition">
              {{ $card['cta']['text'] ?? 'تفاصيل' }}
            </a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endif

<style>
/* دعم clamp بدون إضافة plugin */
.line-clamp-1{display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden}
</style>