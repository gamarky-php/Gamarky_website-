<!-- resources/views/components/data-table.blade.php -->
@props([
    'headers' => [],
    'rows' => [],
    'emptyMessage' => 'لا توجد بيانات',
    'sortable' => false,
    'paginator' => null,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg']) }}>
    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                        @if($sortable && isset($header['sortable']) && $header['sortable'])
                            <button type="button" class="group inline-flex items-center hover:text-gray-600 dark:hover:text-gray-300">
                                {{ $header['label'] ?? $header }}
                                <span class="mr-2 flex-none rounded text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                    </svg>
                                </span>
                            </button>
                        @else
                            {{ $header['label'] ?? $header }}
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
            @forelse($rows as $row)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    @foreach($row as $cell)
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {!! $cell !!}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="mb-2 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p>{{ $emptyMessage }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($paginator)
        <div class="border-t border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-900 sm:px-6">
            {{ $paginator->links() }}
        </div>
    @endif
</div>
