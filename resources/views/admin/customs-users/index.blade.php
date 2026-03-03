@extends('layouts.dashboard')
@section('title', __('headings.customs_users'))
@section('dashboard')
  {{-- dir inherited from layout --}}
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">@lang('headings.customs_users')</h1>
    <p class="text-gray-600">@lang('help.temp_page_placeholder')</p>
  </div>
@endsection

