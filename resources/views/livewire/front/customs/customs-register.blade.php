{{-- resources/views/livewire/front/customs/customs-register.blade.php --}}
<div dir="rtl" class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
  
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
        تسجيل مستخلص جمركي
      </h1>
      <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-6">
        انضم إلى شبكتنا من المستخلصين المعتمدين واحصل على عملاء جدد
      </p>
      <div class="flex flex-wrap items-center justify-center gap-4 text-sm">
        <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
          <i class="fas fa-shield-check text-green-400"></i>
          <span>سجل معتمد</span>
        </span>
        <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
          <i class="fas fa-users text-blue-400"></i>
          <span>عملاء موثوقون</span>
        </span>
        <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
          <i class="fas fa-chart-line text-yellow-400"></i>
          <span>زيادة في الأرباح</span>
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
            <span>البيانات الأساسية</span>
          </h2>
          <p class="text-blue-100 mt-2 text-sm">معلومات المؤسسة أو الشركة</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-building text-blue-600 ml-1"></i>اسم المؤسسة/الشركة <span class="text-red-500">*</span>
            </label>
            <input type="text" wire:model="company_name" 
                   placeholder="مثال: مؤسسة الخليج للتخليص الجمركي"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-id-card text-green-600 ml-1"></i>رقم السجل التجاري <span class="text-red-500">*</span>
            </label>
            <input type="text" wire:model="commercial_registration" 
                   placeholder="1010XXXXXX"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-user text-purple-600 ml-1"></i>اسم المدير/المسؤول <span class="text-red-500">*</span>
            </label>
            <input type="text" wire:model="manager_name" 
                   placeholder="الاسم الكامل"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-phone text-green-600 ml-1"></i>رقم الجوال <span class="text-red-500">*</span>
            </label>
            <input type="tel" wire:model="phone" 
                   placeholder="+966 5XX XXX XXX"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right" dir="ltr">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-envelope text-red-600 ml-1"></i>البريد الإلكتروني <span class="text-red-500">*</span>
            </label>
            <input type="email" wire:model="email" 
                   placeholder="info@example.com"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right" dir="ltr">
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-globe text-blue-600 ml-1"></i>الموقع الإلكتروني (اختياري)
            </label>
            <input type="url" wire:model="website" 
                   placeholder="https://example.com"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right" dir="ltr">
          </div>
          
          <div class="md:col-span-2">
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-map-marker-alt text-orange-600 ml-1"></i>العنوان الرئيسي <span class="text-red-500">*</span>
            </label>
            <textarea wire:model="address" rows="2"
                      placeholder="الشارع، الحي، المدينة، الرمز البريدي"
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
            <span>الموانئ التي تعمل بها</span>
          </h2>
          <p class="text-green-100 mt-2 text-sm">اختر الموانئ والمنافذ الجمركية</p>
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
            يمكنك اختيار أكثر من ميناء
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
            <span>التخصصات</span>
          </h2>
          <p class="text-purple-100 mt-2 text-sm">أنواع البضائع التي تتخصص في تخليصها</p>
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
            <span>الشهادات والمرفقات</span>
          </h2>
          <p class="text-orange-100 mt-2 text-sm">ارفع المستندات المطلوبة</p>
        </div>
        <div class="p-6 space-y-4">
          {{-- Commercial Registration --}}
          <div class="p-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
            <label class="block text-gray-700 font-medium mb-3">
              <i class="fas fa-file-alt text-blue-600 ml-2"></i>السجل التجاري <span class="text-red-500">*</span>
            </label>
            <input type="file" wire:model="file_commercial_registration" 
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-gray-500 mt-2">
              <i class="fas fa-info-circle text-blue-500 ml-1"></i>
              PDF أو صورة، الحجم الأقصى: 2 ميجابايت
            </p>
            @if($file_commercial_registration)
              <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-check-circle ml-1"></i>
                تم رفع الملف بنجاح
              </p>
            @endif
          </div>

          {{-- Customs License --}}
          <div class="p-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
            <label class="block text-gray-700 font-medium mb-3">
              <i class="fas fa-certificate text-green-600 ml-2"></i>ترخيص التخليص الجمركي <span class="text-red-500">*</span>
            </label>
            <input type="file" wire:model="file_customs_license" 
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            <p class="text-xs text-gray-500 mt-2">
              <i class="fas fa-info-circle text-green-500 ml-1"></i>
              PDF أو صورة، الحجم الأقصى: 2 ميجابايت
            </p>
            @if($file_customs_license)
              <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-check-circle ml-1"></i>
                تم رفع الملف بنجاح
              </p>
            @endif
          </div>

          {{-- Manager ID --}}
          <div class="p-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
            <label class="block text-gray-700 font-medium mb-3">
              <i class="fas fa-id-card text-purple-600 ml-2"></i>هوية المدير/المسؤول <span class="text-red-500">*</span>
            </label>
            <input type="file" wire:model="file_manager_id" 
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
            <p class="text-xs text-gray-500 mt-2">
              <i class="fas fa-info-circle text-purple-500 ml-1"></i>
              PDF أو صورة، الحجم الأقصى: 2 ميجابايت
            </p>
            @if($file_manager_id)
              <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-check-circle ml-1"></i>
                تم رفع الملف بنجاح
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
            <span>الخبرة والمعلومات المهنية</span>
          </h2>
          <p class="text-teal-100 mt-2 text-sm">تفاصيل خبرتك في المجال</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-calendar text-blue-600 ml-1"></i>سنوات الخبرة <span class="text-red-500">*</span>
            </label>
            <select wire:model="years_of_experience" 
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
              <option value="">-- اختر --</option>
              <option value="1-3">1-3 سنوات</option>
              <option value="3-5">3-5 سنوات</option>
              <option value="5-10">5-10 سنوات</option>
              <option value="10-15">10-15 سنة</option>
              <option value="15+">أكثر من 15 سنة</option>
            </select>
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-users text-purple-600 ml-1"></i>عدد الموظفين
            </label>
            <select wire:model="number_of_employees" 
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
              <option value="">-- اختر --</option>
              <option value="1-5">1-5 موظفين</option>
              <option value="6-10">6-10 موظفين</option>
              <option value="11-20">11-20 موظف</option>
              <option value="20+">أكثر من 20 موظف</option>
            </select>
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-box text-green-600 ml-1"></i>متوسط الشحنات الشهرية
            </label>
            <select wire:model="avg_monthly_shipments" 
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
              <option value="">-- اختر --</option>
              <option value="1-10">1-10 شحنات</option>
              <option value="11-50">11-50 شحنة</option>
              <option value="51-100">51-100 شحنة</option>
              <option value="100+">أكثر من 100 شحنة</option>
            </select>
          </div>
          
          <div>
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-language text-orange-600 ml-1"></i>اللغات
            </label>
            <input type="text" wire:model="languages" 
                   placeholder="مثال: العربية، الإنجليزية"
                   class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>
          
          <div class="md:col-span-2">
            <label class="block text-gray-700 font-medium mb-2 text-sm">
              <i class="fas fa-info-circle text-blue-600 ml-1"></i>نبذة عن الخدمات المقدمة (اختياري)
            </label>
            <textarea wire:model="services_description" rows="4"
                      placeholder="وصف مختصر عن خدماتك وخبراتك في المجال..."
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right resize-none"></textarea>
            <p class="text-xs text-gray-500 mt-1">الحد الأقصى: 500 حرف</p>
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
            <span>التكاملات التقنية</span>
          </h2>
          <p class="text-indigo-100 mt-2 text-sm">الأنظمة والمنصات التي تستخدمها</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-indigo-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-indigo-300">
            <input type="checkbox" wire:model="has_cargox_integration" 
                   class="w-5 h-5 text-indigo-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">CargoX Integration</div>
              <div class="text-xs text-gray-600">نظام إدارة المستندات الإلكترونية</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-blue-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-blue-300">
            <input type="checkbox" wire:model="has_einvoice_integration" 
                   class="w-5 h-5 text-blue-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">eInvoice / ZATCA</div>
              <div class="text-xs text-gray-600">الفوترة الإلكترونية وربط الزكاة والضريبة</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-green-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-green-300">
            <input type="checkbox" wire:model="has_fasah_integration" 
                   class="w-5 h-5 text-green-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">نظام فسح (FASAH)</div>
              <div class="text-xs text-gray-600">النظام الموحد للجمارك السعودية</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-purple-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-purple-300">
            <input type="checkbox" wire:model="has_saber_integration" 
                   class="w-5 h-5 text-purple-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">منصة سابر (SABER)</div>
              <div class="text-xs text-gray-600">نظام المطابقة للمنتجات المستوردة</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-orange-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-orange-300">
            <input type="checkbox" wire:model="has_nafis_integration" 
                   class="w-5 h-5 text-orange-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">منصة نافذة (Nafis)</div>
              <div class="text-xs text-gray-600">منصة التجارة الإلكترونية للمنشآت</div>
            </div>
          </label>

          <label class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-red-50 rounded-xl cursor-pointer transition border-2 border-transparent hover:border-red-300">
            <input type="checkbox" wire:model="has_api_integration" 
                   class="w-5 h-5 text-red-600 rounded">
            <div class="flex-1">
              <div class="font-semibold text-gray-900">API Integration</div>
              <div class="text-xs text-gray-600">تكاملات برمجية مخصصة</div>
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
            <span>الشروط والأحكام</span>
          </h2>
          <p class="text-red-100 mt-2 text-sm">راجع الشروط قبل الإرسال</p>
        </div>
        <div class="p-6 space-y-4">
          <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-blue-50 transition">
            <input type="checkbox" wire:model="agree_terms" 
                   class="w-5 h-5 text-blue-600 rounded mt-0.5">
            <div class="flex-1">
              <span class="font-semibold text-gray-900">أوافق على الشروط والأحكام</span>
              <p class="text-sm text-gray-600 mt-1">
                بتسجيلك كمستخلص جمركي، فإنك تقر بأن جميع المعلومات المقدمة صحيحة وتوافق على الالتزام بسياسات وشروط المنصة.
                <a href="#" class="text-blue-600 hover:underline">اقرأ الشروط كاملة</a>
              </p>
            </div>
          </label>

          <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-green-50 transition">
            <input type="checkbox" wire:model="agree_privacy" 
                   class="w-5 h-5 text-green-600 rounded mt-0.5">
            <div class="flex-1">
              <span class="font-semibold text-gray-900">أوافق على سياسة الخصوصية</span>
              <p class="text-sm text-gray-600 mt-1">
                نحن نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية.
                <a href="#" class="text-green-600 hover:underline">اطلع على سياسة الخصوصية</a>
              </p>
            </div>
          </label>

          {{-- Submit Button --}}
          <div class="pt-6 border-t border-gray-200">
            <button type="submit" 
                    class="w-full py-4 bg-gradient-to-l from-blue-600 to-purple-600 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 flex items-center justify-center gap-3">
              <i class="fas fa-paper-plane text-xl"></i>
              <span>إرسال الطلب للمراجعة</span>
            </button>
            <p class="text-center text-sm text-gray-500 mt-4">
              <i class="fas fa-clock text-blue-500 ml-1"></i>
              سيتم مراجعة طلبك خلال 48-72 ساعة عمل
            </p>
          </div>

          {{-- Success Message --}}
          @if(session()->has('success'))
            <div class="p-6 bg-green-100 border-2 border-green-300 rounded-xl">
              <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-check-circle text-3xl text-green-600"></i>
                <h3 class="text-xl font-bold text-green-900">تم الإرسال بنجاح!</h3>
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
      <h2 class="text-3xl font-bold mb-4">تحتاج مساعدة؟</h2>
      <p class="text-xl text-blue-100 mb-6">
        فريق الدعم جاهز لمساعدتك في إتمام التسجيل
      </p>
      <div class="flex flex-wrap items-center justify-center gap-4">
        <a href="tel:+966" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-600 rounded-xl font-bold hover:bg-gray-100 transition">
          <i class="fas fa-phone"></i>
          <span>اتصل بنا</span>
        </a>
        <a href="mailto:support@example.com" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 border-2 border-white text-white rounded-xl font-bold hover:bg-white hover:text-blue-600 transition">
          <i class="fas fa-envelope"></i>
          <span>راسلنا عبر البريد</span>
        </a>
      </div>
    </div>
  </section>

</div>
