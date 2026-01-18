{{-- resources/views/livewire/front/customs/customs-index.blade.php --}}
<div dir="rtl" class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
  
  {{-- Hero Section with Search --}}
  <section class="relative bg-gradient-to-l from-[#0F2E5D] via-[#1a3f6e] to-[#0F2E5D] text-white py-16 overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-10">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">
          <i class="fas fa-search ml-3 text-yellow-400"></i>
          ابحث عن مستخلص جمركي
        </h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">
          اختر من بين أفضل المستخلصين الجمركيين المعتمدين في الشرق الأوسط
        </p>
        <div class="mt-6 flex flex-wrap items-center justify-center gap-4 text-sm">
          <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
            <i class="fas fa-shield-alt text-green-400"></i>
            <span>100% موثوق</span>
          </span>
          <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
            <i class="fas fa-star text-yellow-400"></i>
            <span>تقييمات حقيقية</span>
          </span>
          <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
            <i class="fas fa-clock text-blue-400"></i>
            <span>استجابة سريعة</span>
          </span>
        </div>
      </div>

      {{-- Advanced Filters Card --}}
      <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl p-6 md:p-8">
        <form wire:submit.prevent="searchBrokers">
          {{-- Row 1: Main Search --}}
          <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2 text-right">
              <i class="fas fa-search text-blue-600 ml-2"></i>
              ابحث بالاسم أو الميناء
            </label>
            <input type="text" wire:model="search_query" 
                   placeholder="مثال: مؤسسة الخليج، ميناء جدة..."
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>

          {{-- Row 2: Filters Grid --}}
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Country --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-flag ml-2 text-green-600"></i>الدولة
              </label>
              <select wire:model="country" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">جميع الدول</option>
                @foreach($countries as $code => $name)
                  <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
              </select>
            </div>

            {{-- Port --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-anchor ml-2 text-blue-600"></i>الميناء
              </label>
              <select wire:model="port" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">جميع الموانئ</option>
                @foreach($ports as $portName)
                  <option value="{{ $portName }}">{{ $portName }}</option>
                @endforeach
              </select>
            </div>

            {{-- Activity Type --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-boxes ml-2 text-purple-600"></i>نوع النشاط
              </label>
              <select wire:model="activity_type" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">جميع الأنشطة</option>
                @foreach($activityTypes as $code => $name)
                  <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- Row 3: Advanced Filters --}}
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Experience --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-award ml-2 text-amber-600"></i>الخبرة (سنوات)
              </label>
              <select wire:model="min_experience" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">أي خبرة</option>
                <option value="5">5 سنوات فأكثر</option>
                <option value="10">10 سنوات فأكثر</option>
                <option value="15">15 سنة فأكثر</option>
              </select>
            </div>

            {{-- Rating --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-star ml-2 text-yellow-500"></i>التقييم
              </label>
              <select wire:model="min_rating" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">جميع التقييمات</option>
                <option value="4.5">⭐ 4.5 فأعلى</option>
                <option value="4">⭐ 4.0 فأعلى</option>
                <option value="3.5">⭐ 3.5 فأعلى</option>
              </select>
            </div>

            {{-- Price Range --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-dollar-sign ml-2 text-green-600"></i>نطاق السعر التقديري
              </label>
              <select wire:model="price_range" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">أي سعر</option>
                <option value="low">أقل من 2,000 ر.س</option>
                <option value="medium">2,000 - 2,500 ر.س</option>
                <option value="high">أكثر من 2,500 ر.س</option>
              </select>
            </div>
          </div>

          {{-- Action Buttons --}}
          <div class="flex flex-wrap items-center gap-3 justify-center">
            <button type="submit" 
                    class="px-8 py-3 bg-gradient-to-l from-blue-600 to-blue-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center gap-2">
              <i class="fas fa-search"></i>
              <span>بحث متقدم</span>
            </button>
            <button type="button" wire:click="resetFilters"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 flex items-center gap-2">
              <i class="fas fa-redo"></i>
              <span>إعادة تعيين</span>
            </button>
          </div>
        </form>

        {{-- Results Counter --}}
        @if($searchPerformed && $total_results > 0)
          <div class="mt-6 pt-6 border-t border-gray-200 text-center">
            <p class="text-gray-600 font-medium">
              <i class="fas fa-check-circle text-green-500 ml-2"></i>
              تم العثور على <span class="font-bold text-blue-600">{{ $total_results }}</span> مستخلص جمركي
            </p>
          </div>
        @endif
      </div>
    </div>
  </section>

  {{-- Results Section --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    @if(count($brokers) > 0)
      {{-- Sort & View Controls --}}
      <div class="flex flex-wrap items-center justify-between mb-8 bg-white rounded-xl shadow-sm p-4 gap-4">
        <div class="flex items-center gap-3">
          <label class="text-gray-600 font-medium text-sm">ترتيب حسب:</label>
          <select wire:model="sort_by" class="px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-sm">
            <option value="rating_desc">الأعلى تقييماً</option>
            <option value="experience_desc">الأكثر خبرة</option>
            <option value="price_asc">الأقل سعراً</option>
            <option value="reviews_desc">الأكثر تقييمات</option>
          </select>
        </div>
        
        <div class="flex items-center gap-2">
          <button wire:click="$set('view_mode', 'grid')" 
                  class="p-2 rounded-lg {{ $view_mode === 'grid' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">
            <i class="fas fa-th-large"></i>
          </button>
          <button wire:click="$set('view_mode', 'list')" 
                  class="p-2 rounded-lg {{ $view_mode === 'list' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">
            <i class="fas fa-list"></i>
          </button>
        </div>
      </div>

      {{-- Brokers Grid --}}
      <div class="grid grid-cols-1 {{ $view_mode === 'grid' ? 'md:grid-cols-2 lg:grid-cols-3' : '' }} gap-6">
        @foreach($brokers as $broker)
          <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-blue-300 {{ $broker['featured'] ? 'ring-2 ring-yellow-400' : '' }}">
            
            {{-- Featured Badge --}}
            @if($broker['featured'])
              <div class="bg-gradient-to-l from-yellow-400 to-amber-500 text-white px-4 py-2 text-center font-bold text-sm flex items-center justify-center gap-2">
                <i class="fas fa-crown"></i>
                <span>مستخلص مميز</span>
              </div>
            @endif

            <div class="p-6">
              {{-- Header: Name & Badge --}}
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition">
                    {{ $broker['name'] }}
                  </h3>
                  <div class="flex flex-wrap items-center gap-2 mb-2">
                    @if($broker['verified'])
                      <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                        <i class="fas fa-check-circle"></i>
                        {{ $broker['badge'] }}
                      </span>
                    @endif
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                      <i class="fas fa-map-marker-alt"></i>
                      {{ $broker['country'] }}
                    </span>
                  </div>
                </div>
              </div>

              {{-- Rating & Reviews --}}
              <div class="flex flex-wrap items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-1">
                  @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star {{ $i <= floor($broker['rating']) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                  @endfor
                </div>
                <span class="font-bold text-gray-900">{{ $broker['rating'] }}</span>
                <span class="text-gray-500 text-sm">({{ $broker['reviews_count'] }} تقييم)</span>
              </div>

              {{-- Stats Grid --}}
              <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                  <div class="text-2xl font-bold text-blue-600">{{ $broker['experience_years'] }}</div>
                  <div class="text-xs text-gray-600 mt-1">سنة خبرة</div>
                </div>
                <div class="bg-green-50 rounded-xl p-3 text-center">
                  <div class="text-2xl font-bold text-green-600">{{ $broker['success_rate'] }}%</div>
                  <div class="text-xs text-gray-600 mt-1">نسبة النجاح</div>
                </div>
              </div>

              {{-- Port & Response Time --}}
              <div class="space-y-2 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                  <i class="fas fa-anchor text-blue-500"></i>
                  <span>{{ $broker['port'] }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                  <i class="fas fa-clock text-orange-500"></i>
                  <span>زمن الاستجابة: <strong class="text-gray-900">{{ $broker['response_time'] }}</strong></span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                  <i class="fas fa-dollar-sign text-green-500"></i>
                  <span>السعر التقديري: <strong class="text-gray-900">{{ $broker['avg_price'] }}</strong></span>
                </div>
              </div>

              {{-- Specialties --}}
              <div class="mb-4">
                <div class="flex flex-wrap gap-2">
                  @foreach($broker['specialties'] as $specialty)
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                      {{ $specialty }}
                    </span>
                  @endforeach
                </div>
              </div>

              {{-- CTA Buttons --}}
              <div class="flex flex-col gap-2 pt-4 border-t border-gray-100">
                <button class="w-full py-3 bg-gradient-to-l from-blue-600 to-blue-700 text-white rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                  <i class="fas fa-rocket"></i>
                  <span>ابدأ مع هذا المستخلص</span>
                </button>
                <button class="w-full py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 flex items-center justify-center gap-2">
                  <i class="fas fa-file-alt"></i>
                  <span>الملف الكامل</span>
                </button>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination Placeholder --}}
      <div class="mt-12 flex justify-center">
        <div class="bg-white rounded-xl shadow-md px-6 py-3">
          <p class="text-gray-500 text-sm">
            <i class="fas fa-info-circle text-blue-500 ml-2"></i>
            @todo: إضافة Pagination للنتائج
          </p>
        </div>
      </div>

    @else
      {{-- Empty State --}}
      <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
          <i class="fas fa-search text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-700 mb-3">لم يتم العثور على نتائج</h3>
        <p class="text-gray-500 mb-6">جرب تعديل معايير البحث أو إعادة تعيين الفلاتر</p>
        <button wire:click="resetFilters" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">
          <i class="fas fa-redo ml-2"></i>
          إعادة البحث
        </button>
      </div>
    @endif

  </section>

  {{-- Informational Banner --}}
  <section class="bg-gradient-to-l from-indigo-600 to-purple-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl font-bold mb-4">هل أنت مستخلص جمركي؟</h2>
      <p class="text-xl text-indigo-100 mb-6 max-w-2xl mx-auto">
        انضم إلى شبكتنا من المستخلصين المعتمدين واحصل على عملاء جدد
      </p>
      <a href="{{ route('front.customs.register') }}" 
         class="inline-flex items-center gap-2 px-8 py-4 bg-white text-indigo-600 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
        <i class="fas fa-user-plus"></i>
        <span>سجّل الآن كمستخلص</span>
      </a>
    </div>
  </section>

</div>
