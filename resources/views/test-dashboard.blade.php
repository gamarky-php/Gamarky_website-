<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Manufacturing Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold text-red-600 mb-4">اختبار صفحة التصنيع</h1>
        <p class="text-lg mb-4">إذا ظهرت هذه الصفحة، فالمشكلة في Livewire Component أو Routes</p>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">معلومات الاختبار:</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>Laravel Version: {{ app()->version() }}</li>
                <li>PHP Version: {{ PHP_VERSION }}</li>
                <li>Current Route: {{ Route::currentRouteName() ?? 'N/A' }}</li>
                <li>Request Path: {{ request()->path() }}</li>
            </ul>
        </div>

        <div class="mt-6">
            <a href="/dashboard/manufacturing" class="bg-blue-500 text-white px-4 py-2 rounded">
                اختبار Manufacturing Dashboard
            </a>
            <a href="/dashboard/manufacturing/quotes" class="bg-green-500 text-white px-4 py-2 rounded ml-2">
                اختبار Manufacturing Quotes
            </a>
        </div>
    </div>
</body>
</html>
