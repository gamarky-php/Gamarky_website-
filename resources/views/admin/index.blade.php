{{-- resources/views/admin/index.blade.php --}}
@extends('layouts.front')

@section('title', 'لوحة التحكم')

@section('dashboard')
<div class="min-h-[calc(100vh-200px)] bg-gray-50">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">

    {{-- شريط علوي صغير لزر القائمة في الموبايل --}}
    <div class="md:hidden mb-4 flex justify-between items-center">
      <h1 class="text-lg font-semibold">لوحة التحكم</h1>
      <button x-data @click="$dispatch('toggle-admin-sidebar')" class="px-3 py-1.5 rounded-xl bg-blue-600 text-white">
        القائمة
      </button>
    </div>

    {{-- تخطيط الشبكة: سايدبار + محتوى --}}
    <div class="grid grid-cols-1 md:grid-cols-[18rem_minmax(0,1fr)] gap-6">

      {{-- Sidebar --}}
      <aside x-data="{ open: true }"
             @toggle-admin-sidebar.window="open = !open"
             class="md:sticky md:top-24 md:self-start">
        {{-- في الموبايل: سلايد أوفر --}}
        <div class="md:hidden" x-show="open" x-transition>
          <div class="fixed inset-0 bg-black/30 z-40" @click="open=false"></div>
          <div class="fixed inset-y-0 end-0 w-72 bg-white z-50 shadow-xl p-4 overflow-y-auto">
            <x-admin-sidebar />
          </div>
        </div>

        {{-- على الشاشات المتوسطة فأكبر: صندوق ثابت --}}
        <div class="hidden md:block w-72">
          <x-admin-sidebar />
        </div>
      </aside>

      {{-- Main content --}}
      <main class="min-w-0">
        {{-- عنوان الصفحة --}}
        <div class="mb-6">
          <h2 class="text-xl font-semibold text-gray-800">الأقسام الرئيسية</h2>
          <p class="text-sm text-gray-500 mt-1">اختر قسمًا من القائمة الجانبية، أو استعرض الخدمات التالية.</p>
        </div>

        {{-- الخدمات --}}
        <section class="mt-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-3">الخدمات</h3>
          <div class="overflow-hidden rounded-2xl bg-white shadow">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">الخدمة</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">الوصف</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">‏</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @php
                  $services = [
                    ['name'=>'الموردون','desc'=>'إدارة بيانات الموردين وملفاتهم','route'=>'admin.suppliers.index'],
                    ['name'=>'استيراد الموردين','desc'=>'استيراد بيانات الموردين من ملفات CSV/Excel','route'=>'admin.suppliers.import'],
                    ['name'=>'التعريفة','desc'=>'جداول البنود والرسوم','route'=>'admin.tariffs.index'],
                    ['name'=>'المستخدمون الجمركيون','desc'=>'إدارة حسابات المستخدمين الجمركيين','route'=>'admin.customs.users'],
                    ['name'=>'سيارات المقيمين','desc'=>'طلب/استخدام/إخراج السيارات','route'=>'admin.cars.index'],
                    ['name'=>'الحاويات','desc'=>'إدارة الحاويات وتتبعها','route'=>'admin.containers.board'],
                    ['name'=>'المقالات','desc'=>'إدارة المقالات والمحتوى','route'=>'admin.posts.index'],
                    ['name'=>'الإعلانات','desc'=>'إدارة الإعلانات والتنبيهات','route'=>'admin.ads.index'],
                    ['name'=>'Console / API','desc'=>'وحدة تحكم الـ API','route'=>'admin.console.index'],
                  ];
                @endphp
                @foreach ($services as $s)
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $s['name'] }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $s['desc'] }}</td>
                    <td class="px-4 py-3 text-left">
                      <a href="{{ route($s['route']) }}" class="text-blue-700 hover:underline">فتح</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </div>
</div>

{{-- مكوّن Blade صغير للسايدبار (الأقسام الرئيسية) --}}
@once
  @push('components')
    @verbatim
    @endverbatim
  @endpush
@endonce

{{-- تعريف المكوّن داخل نفس الملف (بدون إنشاء ملف جديد) --}}
@php
  // hack بسيط لتعريف مكوّن Blade inline
@endphp
<?php $__env->startComponent('components.dynamic', ['slot' => 'admin-sidebar']); ?>
<?php $__env->slot('slot'); ?>
  <div class="space-y-3">
    <div class="text-sm text-gray-500 mb-1">الأقسام الرئيسية</div>
    @php
      $items = [
        ['label'=>'الاستيراد', 'route'=>'admin.import.index'],
        ['label'=>'التصدير', 'route'=>'admin.export.index'],
        ['label'=>'التصنيع', 'route'=>'admin.manufacturing.index'],
        ['label'=>'المستخلص الجمركي', 'route'=>'admin.customs.index'],
        ['label'=>'بورصة الحاويات والنقل', 'route'=>'admin.containers.index'],
        ['label'=>'الوكيل', 'route'=>'admin.agents.index'],
      ];
    @endphp
    <nav class="space-y-1">
      @foreach ($items as $it)
        @php $active = request()->routeIs($it['route'].'*'); @endphp
        <a href="{{ route($it['route']) }}"
           class="block rounded-xl px-4 py-2.5 transition
                  {{ $active ? 'bg-blue-600 text-white shadow' : 'bg-white text-gray-800 shadow hover:shadow-md hover:bg-gray-50' }}">
          {{ $it['label'] }}
        </a>
      @endforeach
    </nav>
  </div>
<?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
@endsection
