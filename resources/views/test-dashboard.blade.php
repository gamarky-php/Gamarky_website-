<!DOCTYPE html>
<html lang="@locale" dir="@dir">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.debug_test_manufacturing_title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100" dir="@dir">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold text-red-600 mb-4">{{ __('common.debug_test_manufacturing_heading') }}</h1>
        <p class="text-lg mb-4">{{ __('common.debug_test_manufacturing_hint') }}</p>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">{{ __('common.debug_test_info') }}</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>Laravel Version: {{ app()->version() }}</li>
                <li>PHP Version: {{ PHP_VERSION }}</li>
                <li>Current Route: {{ Route::currentRouteName() ?? 'N/A' }}</li>
                <li>Request Path: {{ request()->path() }}</li>
            </ul>
        </div>

        <div class="mt-6">
            <a href="/dashboard/manufacturing" class="bg-blue-500 text-white px-4 py-2 rounded">
                {{ __('common.debug_test_manufacturing_link') }}
            </a>
            <a href="/dashboard/manufacturing/quotes" class="bg-green-500 text-white px-4 py-2 rounded ml-2">
                {{ __('common.debug_test_quotes_link') }}
            </a>
        </div>
    </div>
</body>
</html>
