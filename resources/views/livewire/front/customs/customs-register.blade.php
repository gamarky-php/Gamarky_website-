{{-- resources/views/livewire/front/customs/customs-register.blade.php --}}
{{-- dir inherited from layout --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
  
  {{-- Hero Section --}}
  <section class="relative bg-gradient-to-l from-[#0F2E5D] via-[#1a3f6e] to-[#0F2E5D] text-white py-16 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6 backdrop-blur-sm">
        <i class="fas fa-user-plus text-4xl text-yellow-400"></i>
      </div>
      <h1 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">
        {{ __('front.clearance.register.hero_title') }}
      </h1>
      <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-6">
        {{ __('front.clearance.register.hero_subtitle') }}
      </p>
      <div class="flex flex-wrap items-center justify-center gap-4 text-sm">
        <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
          <i class="fas fa-shield-check text-green-400"></i>
          <span>{{ __('front.clearance.register.badges.verified_record') }}</span>
        </span>
        <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
          <i class="fas fa-users text-blue-400"></i>
          <span>{{ __('front.clearance.register.badges.trusted_clients') }}</span>
        </span>
        <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
          <i class="fas fa-chart-line text-yellow-400"></i>
          <span>{{ __('front.clearance.register.badges.profit_growth') }}</span>
        </span>
      </div>
    </div>
  </section>

  {{-- Multi-Section Form --}}
  <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <form wire:submit.prevent="submitRegistration" class="space-y-6">
      
      {{-- Section 1: Basic Information --}}
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-l from-blue-600 to-blue-700 p-6 text-white">
          <h2 class="text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
              <span class="font-bold">1</span>
            </div>
            <span>{{ __('front.clearance.register.sections.basic.title') }}</span>
          </h2>
          <p class="text-blue-100 mt-2 text-sm">{{ __('front.clearance.register.sections.basic.subtitle') }}</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
                  <i class="fas fa-building text-blue-600 ml-1"></i>{{ __('front.clearance.register.labels.company_name_required') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" wire:model="company_name" 
                    placeholder="{{ __('front.clearance.register.placeholders.company_name') }}"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-id-card text-green-600 ml-1"></i>{{ __('front.clearance.register.labels.commercial_registration_required') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" wire:model="commercial_registration" 
                   placeholder="1010XXXXXX"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
                  <i class="fas fa-user text-purple-600 ml-1"></i>{{ __('front.clearance.register.labels.manager_name_required') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" wire:model="manager_name" 
                    placeholder="{{ __('front.clearance.register.placeholders.manager_name') }}"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-phone text-green-600 ml-1"></i>{{ __('front.clearance.register.labels.phone_required') }} <span class="text-red-500">*</span>
            </label>
            <input type="tel" wire:model="phone" 
                   placeholder="+966 5XX XXX XXX"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right ltr">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-envelope text-red-600 ml-1"></i>{{ __('front.clearance.register.labels.email_required') }} <span class="text-red-500">*</span>
            </label>
            <input type="email" wire:model="email" 
                   placeholder="info@example.com"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right ltr">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-globe text-blue-600 ml-1"></i>{{ __('front.clearance.register.labels.website_optional') }}
            </label>
            <input type="url" wire:model="website" 
                   placeholder="https://example.com"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right ltr">
          </div>
          
          <div class="md:col-span-2">
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-map-marker-alt text-orange-600 ml-1"></i>{{ __('front.clearance.register.labels.main_address_required') }} <span class="text-red-500">*</span>
            </label>
            <textarea wire:model="address" rows="2"
                      placeholder="{{ __('front.clearance.register.placeholders.main_address') }}"
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right resize-none"></textarea>
          </div>
        </div>
      </div>

      {{-- Section 2: Ports --}}
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-l from-green-600 to-emerald-700 p-6 text-white">
          <h2 class="text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
              <span class="font-bold">2</span>
            </div>
            <span>{{ __('front.clearance.register.sections.ports.title') }}</span>
          </h2>
          <p class="text-green-100 mt-2 text-sm">{{ __('front.clearance.register.sections.ports.subtitle') }}</p>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($availablePorts as $port)
              <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-green-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-green-300">
                <input type="checkbox" wire:model="selected_ports" value="{{ $port }}" 
                       class="w-5 h-5 text-green-600 rounded">
                <span class="font-medium text-gray-900">{{ $port }}</span>
              </label>
            @endforeach
          </div>
          <p class="text-xs text-gray-500 mt-3">
            <i class="fas fa-info-circle text-blue-500 ml-1"></i>
            {{ __('front.clearance.register.sections.ports.hint') }}
          </p>
        </div>
      </div>

      {{-- Section 3: Specialties --}}
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-l from-purple-600 to-violet-700 p-6 text-white">
          <h2 class="text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
              <span class="font-bold">3</span>
            </div>
            <span>{{ __('front.clearance.register.sections.specialties.title') }}</span>
          </h2>
          <p class="text-purple-100 mt-2 text-sm">{{ __('front.clearance.register.sections.specialties.subtitle') }}</p>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @foreach($availableSpecialties as $specialty)
              <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-purple-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-purple-300">
                <input type="checkbox" wire:model="selected_specialties" value="{{ $specialty }}" 
                       class="w-5 h-5 text-purple-600 rounded">
                <span class="font-medium text-gray-900 text-sm">{{ $specialty }}</span>
              </label>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Section 4: Certificates & Documents --}}
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-l from-orange-600 to-amber-700 p-6 text-white">
          <h2 class="text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
              <span class="font-bold">4</span>
            </div>
            <span>{{ __('front.clearance.register.sections.documents.title') }}</span>
          </h2>
          <p class="text-orange-100 mt-2 text-sm">{{ __('front.clearance.register.sections.documents.subtitle') }}</p>
        </div>
        <div class="p-6 space-y-4">
          {{-- Commercial Registration --}}
          <div class="p-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
            <label class="block text-gray-700 font-medium mb-3">
              <i class="fas fa-file-alt text-blue-600 ml-2"></i>{{ __('front.clearance.register.documents.commercial_registration_required') }} <span class="text-red-500">*</span>
            </label>
            <input type="file" wire:model="file_commercial_registration" 
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-gray-500 mt-2">
              <i class="fas fa-info-circle text-blue-500 ml-1"></i>
              {{ __('front.clearance.register.documents.upload_hint') }}
            </p>
            @if($file_commercial_registration)
              <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-check-circle ml-1"></i>
                {{ __('front.clearance.register.documents.upload_success') }}
              </p>
            @endif
          </div>

          {{-- Customs License --}}
          <div class="p-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
            <label class="block text-gray-700 font-medium mb-3">
              <i class="fas fa-certificate text-green-600 ml-2"></i>{{ __('front.clearance.register.documents.customs_license_required') }} <span class="text-red-500">*</span>
            </label>
            <input type="file" wire:model="file_customs_license" 
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            <p class="text-xs text-gray-500 mt-2">
              <i class="fas fa-info-circle text-green-500 ml-1"></i>
              {{ __('front.clearance.register.documents.upload_hint') }}
            </p>
            @if($file_customs_license)
              <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-check-circle ml-1"></i>
                {{ __('front.clearance.register.documents.upload_success') }}
              </p>
            @endif
          </div>

          {{-- Manager ID --}}
          <div class="p-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
            <label class="block text-gray-700 font-medium mb-3">
              <i class="fas fa-id-card text-purple-600 ml-2"></i>{{ __('front.clearance.register.documents.manager_id_required') }} <span class="text-red-500">*</span>
            </label>
            <input type="file" wire:model="file_manager_id" 
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
            <p class="text-xs text-gray-500 mt-2">
              <i class="fas fa-info-circle text-purple-500 ml-1"></i>
              {{ __('front.clearance.register.documents.upload_hint') }}
            </p>
            @if($file_manager_id)
              <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-check-circle ml-1"></i>
                {{ __('front.clearance.register.documents.upload_success') }}
              </p>
            @endif
          </div>
        </div>
      </div>

      {{-- Section 5: Experience --}}
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-l from-teal-600 to-cyan-700 p-6 text-white">
          <h2 class="text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
              <span class="font-bold">5</span>
            </div>
            <span>{{ __('front.clearance.register.sections.experience.title') }}</span>
          </h2>
          <p class="text-teal-100 mt-2 text-sm">{{ __('front.clearance.register.sections.experience.subtitle') }}</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-calendar text-blue-600 ml-1"></i>{{ __('front.clearance.register.labels.years_of_experience_required') }} <span class="text-red-500">*</span>
            </label>
            <select wire:model="years_of_experience" 
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
              <option value="">{{ __('front.clearance.register.options.select') }}</option>
              <option value="1-3">{{ __('front.clearance.register.options.experience_1_3') }}</option>
              <option value="3-5">{{ __('front.clearance.register.options.experience_3_5') }}</option>
              <option value="5-10">{{ __('front.clearance.register.options.experience_5_10') }}</option>
              <option value="10-15">{{ __('front.clearance.register.options.experience_10_15') }}</option>
              <option value="15+">{{ __('front.clearance.register.options.experience_15_plus') }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-users text-purple-600 ml-1"></i>{{ __('front.clearance.register.labels.number_of_employees') }}
            </label>
            <select wire:model="number_of_employees" 
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
              <option value="">{{ __('front.clearance.register.options.select') }}</option>
              <option value="1-5">{{ __('front.clearance.register.options.employees_1_5') }}</option>
              <option value="6-10">{{ __('front.clearance.register.options.employees_6_10') }}</option>
              <option value="11-20">{{ __('front.clearance.register.options.employees_11_20') }}</option>
              <option value="20+">{{ __('front.clearance.register.options.employees_20_plus') }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-box text-green-600 ml-1"></i>{{ __('front.clearance.register.labels.avg_monthly_shipments') }}
            </label>
            <select wire:model="avg_monthly_shipments" 
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
              <option value="">{{ __('front.clearance.register.options.select') }}</option>
              <option value="1-10">{{ __('front.clearance.register.options.shipments_1_10') }}</option>
              <option value="11-50">{{ __('front.clearance.register.options.shipments_11_50') }}</option>
              <option value="51-100">{{ __('front.clearance.register.options.shipments_51_100') }}</option>
              <option value="100+">{{ __('front.clearance.register.options.shipments_100_plus') }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
                  <i class="fas fa-language text-orange-600 ml-1"></i>{{ __('front.clearance.register.labels.languages') }}
            </label>
            <input type="text" wire:model="languages" 
                    placeholder="{{ __('front.clearance.register.placeholders.languages') }}"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>
          
          <div class="md:col-span-2">
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-info-circle text-blue-600 ml-1"></i>{{ __('front.clearance.register.labels.services_description_optional') }}
            </label>
            <textarea wire:model="services_description" rows="4"
                      placeholder="{{ __('front.clearance.register.placeholders.services_description') }}"
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right resize-none"></textarea>
            <p class="text-xs text-gray-500 mt-1">{{ __('front.clearance.register.hints.max_500_chars') }}</p>
          </div>
        </div>
      </div>

      {{-- Section 6: Integrations --}}
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-l from-indigo-600 to-blue-700 p-6 text-white">
          <h2 class="text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
              <span class="font-bold">6</span>
            </div>
            <span>{{ __('front.clearance.register.sections.integrations.title') }}</span>
          </h2>
          <p class="text-indigo-100 mt-2 text-sm">{{ __('front.clearance.register.sections.integrations.subtitle') }}</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-indigo-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-indigo-300">
            <input type="checkbox" wire:model="has_cargox_integration" 
                   class="w-5 h-5 text-indigo-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">CargoX Integration</div>
              <div class="text-xs text-gray-600">{{ __('front.clearance.register.integrations.cargox_desc') }}</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-blue-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-blue-300">
            <input type="checkbox" wire:model="has_einvoice_integration" 
                   class="w-5 h-5 text-blue-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">eInvoice / ZATCA</div>
              <div class="text-xs text-gray-600">{{ __('front.clearance.register.integrations.einvoice_desc') }}</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-green-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-green-300">
            <input type="checkbox" wire:model="has_fasah_integration" 
                   class="w-5 h-5 text-green-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">{{ __('front.clearance.register.integrations.fasah_title') }}</div>
              <div class="text-xs text-gray-600">{{ __('front.clearance.register.integrations.fasah_desc') }}</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-purple-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-purple-300">
            <input type="checkbox" wire:model="has_saber_integration" 
                   class="w-5 h-5 text-purple-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">{{ __('front.clearance.register.integrations.saber_title') }}</div>
              <div class="text-xs text-gray-600">{{ __('front.clearance.register.integrations.saber_desc') }}</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-orange-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-orange-300">
            <input type="checkbox" wire:model="has_nafis_integration" 
                   class="w-5 h-5 text-orange-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">{{ __('front.clearance.register.integrations.nafis_title') }}</div>
              <div class="text-xs text-gray-600">{{ __('front.clearance.register.integrations.nafis_desc') }}</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-red-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-red-300">
            <input type="checkbox" wire:model="has_api_integration" 
                   class="w-5 h-5 text-red-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">API Integration</div>
              <div class="text-xs text-gray-600">{{ __('front.clearance.register.integrations.api_desc') }}</div>
            </div>
          </label>
        </div>
      </div>

      {{-- Section 7: Terms & Submit --}}
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-l from-red-600 to-pink-700 p-6 text-white">
          <h2 class="text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
              <span class="font-bold">7</span>
            </div>
            <span>{{ __('front.clearance.register.sections.terms.title') }}</span>
          </h2>
          <p class="text-red-100 mt-2 text-sm">{{ __('front.clearance.register.sections.terms.subtitle') }}</p>
        </div>
        <div class="p-6 space-y-4">
          <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-blue-50 transition">
            <input type="checkbox" wire:model="agree_terms" 
                   class="w-5 h-5 text-blue-600 rounded mt-0.5">
            <div class="flex-1">
              <span class="font-semibold text-gray-900">{{ __('front.clearance.register.terms.agree_terms_title') }}</span>
              <p class="text-sm text-gray-600 mt-1">
                {{ __('front.clearance.register.terms.agree_terms_desc') }}
                <a href="#" class="text-blue-600 hover:underline">{{ __('front.clearance.register.terms.read_full_terms') }}</a>
              </p>
            </div>
          </label>

          <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-green-50 transition">
            <input type="checkbox" wire:model="agree_privacy" 
                   class="w-5 h-5 text-green-600 rounded mt-0.5">
            <div class="flex-1">
              <span class="font-semibold text-gray-900">{{ __('front.clearance.register.terms.agree_privacy_title') }}</span>
              <p class="text-sm text-gray-600 mt-1">
                {{ __('front.clearance.register.terms.agree_privacy_desc') }}
                <a href="#" class="text-green-600 hover:underline">{{ __('front.clearance.register.terms.read_privacy_policy') }}</a>
              </p>
            </div>
          </label>

          {{-- Submit Button --}}
          <div class="pt-6 border-t border-gray-200">
            <button type="submit" 
                    class="w-full py-4 bg-gradient-to-l from-blue-600 to-purple-600 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 flex items-center justify-center gap-3">
              <i class="fas fa-paper-plane text-xl"></i>
              <span>{{ __('front.clearance.register.submit_for_review') }}</span>
            </button>
            <p class="text-center text-sm text-gray-500 mt-4">
              <i class="fas fa-clock text-blue-500 ml-1"></i>
              {{ __('front.clearance.register.hints.review_time') }}
            </p>
          </div>

          {{-- Success Message --}}
          @if(session()->has('success'))
            <div class="p-6 bg-green-100 border-2 border-green-300 rounded-xl">
              <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-check-circle text-3xl text-green-600"></i>
                <h3 class="text-xl font-bold text-green-900">{{ __('front.clearance.register.hints.success_title') }}</h3>
              </div>
              <p class="text-green-700">{{ session('success') }}</p>
            </div>
          @endif
        </div>
      </div>

    </form>

  </section>

  {{-- Help Section --}}
  <section class="bg-gradient-to-l from-blue-600 to-purple-600 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl font-bold mb-4">{{ __('front.clearance.register.help.title') }}</h2>
      <p class="text-xl text-blue-100 mb-6">
        {{ __('front.clearance.register.help.subtitle') }}
      </p>
      <div class="flex flex-wrap items-center justify-center gap-4">
        <a href="tel:+966" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-600 rounded-xl font-bold hover:bg-gray-100 transition">
          <i class="fas fa-phone"></i>
          <span>{{ __('front.clearance.register.help.call_us') }}</span>
        </a>
        <a href="mailto:support@example.com" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 border-2 border-white text-white rounded-xl font-bold hover:bg-white hover:text-blue-600 transition">
          <i class="fas fa-envelope"></i>
          <span>{{ __('front.clearance.register.help.email_us') }}</span>
        </a>
      </div>
    </div>
  </section>

</div>
