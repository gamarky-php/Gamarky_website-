{{-- admin/dashboard/index.blade.php --}}
@extends('layouts.dashboard')

@section('dashboard')
  <h1 class="text-2xl font-bold mb-4">لوحة التحكم (أدمن)</h1>

  <div class="grid gap-4 md:grid-cols-3">
    @if (Route::has('admin.import.index'))
      <a href="{{ route('admin.import.index') }}" class="p-4 rounded-xl shadow bg-white block">الاستيراد</a>
    @endif
    @if (Route::has('admin.export.index'))
      <a href="{{ route('admin.export.index') }}" class="p-4 rounded-xl shadow bg-white block">التصدير</a>
    @endif
    @if (Route::has('admin.manufacturing.index'))
      <a href="{{ route('admin.manufacturing.index') }}" class="p-4 rounded-xl shadow bg-white block">التصنيع</a>
    @endif
    @if (Route::has('admin.customs.index'))
      <a href="{{ route('admin.customs.index') }}" class="p-4 rounded-xl shadow bg-white block">المستخلص الجمركي</a>
    @endif
    @if (Route::has('admin.containers.index'))
      <a href="{{ route('admin.containers.index') }}" class="p-4 rounded-xl shadow bg-white block">بورصة الحاويات والنقل</a>
    @endif
    @if (Route::has('admin.agents.index'))
      <a href="{{ route('admin.agents.index') }}" class="p-4 rounded-xl shadow bg-white block">الوكيل</a>
    @endif
    @if (Route::has('admin.suppliers.index'))
      <a href="{{ route('admin.suppliers.index') }}" class="p-4 rounded-xl shadow bg-white block">الموردون</a>
    @endif
  </div>
@endsection
