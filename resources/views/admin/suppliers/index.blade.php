{{-- Admin > Suppliers (RTL) --}}
<!doctype html>
<html lang="@locale" dir="@dir">
<head>
    <meta charset="utf-8">
    <title>{{ __('dashboard.admin.suppliers.index.browser_title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-bold">{{ __('nav.suppliers') }}</h1>
            <form method="get" class="flex gap-2">
                <input name="q" value="{{ $q ?? '' }}" placeholder="{{ __('dashboard.admin.suppliers.index.search_placeholder') }}"
                       class="border rounded-lg px-3 py-2 w-72" />
                <button class="px-4 py-2 rounded-lg bg-[#0F2E5D] text-white">{{ __('dashboard.admin.suppliers.index.search') }}</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-right">
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">{{ __('dashboard.admin.suppliers.index.columns.name') }}</th>
                        <th class="px-4 py-2">{{ __('dashboard.admin.suppliers.index.columns.country') }}</th>
                        <th class="px-4 py-2">{{ __('dashboard.admin.suppliers.index.columns.city') }}</th>
                        <th class="px-4 py-2">{{ __('dashboard.admin.suppliers.index.columns.phone') }}</th>
                        <th class="px-4 py-2">{{ __('dashboard.admin.suppliers.index.columns.website') }}</th>
                        <th class="px-4 py-2">{{ __('dashboard.admin.suppliers.index.columns.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                @php($collection = isset($suppliers) && ($suppliers instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator || $suppliers instanceof \Illuminate\Support\Collection) ? $suppliers : collect())
                @forelse($collection as $supplier)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $supplier->id }}</td>
                        <td class="px-4 py-2">{{ $supplier->company_name }}</td>
                        <td class="px-4 py-2">{{ $supplier->country_code }}</td>
                        <td class="px-4 py-2">{{ $supplier->city ?? $supplier->province }}</td>
                        <td class="px-4 py-2">{{ $supplier->mobile_phone ?? $supplier->tel }}</td>
                        <td class="px-4 py-2">{{ $supplier->website }}</td>
                        <td class="px-4 py-2">
                            @if(($supplier->status ?? '') === 'approved')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">{{ __('dashboard.admin.suppliers.index.status.approved') }}</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">{{ __('dashboard.admin.suppliers.index.status.pending') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-6 text-center text-gray-500" colspan="7">{{ __('dashboard.admin.suppliers.index.empty') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($suppliers ?? null,'links'))
            <div class="mt-4">{{ $suppliers->links() }}</div>
        @endif
    </div>
</body>
</html>

