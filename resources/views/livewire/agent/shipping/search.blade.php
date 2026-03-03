<div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
    <div class="mb-4">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-0"
            placeholder="Search by company / city / country"
        >
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($agents as $agent)
            <div class="rounded-xl border border-slate-200 p-4 bg-slate-50/60">
                <div class="font-semibold text-slate-900">{{ $agent->company_name }}</div>
                <div class="text-sm text-slate-600 mt-1">{{ $agent->city }} @if($agent->country) - {{ $agent->country }} @endif</div>
                <div class="text-xs text-slate-500 mt-2">{{ $agent->contact_name }}</div>
                <div class="text-xs text-slate-500">{{ $agent->phone }}</div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-slate-200 p-6 text-center text-slate-500">
                No agents found.
            </div>
        @endforelse
    </div>
</div>
