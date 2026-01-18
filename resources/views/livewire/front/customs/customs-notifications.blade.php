{{-- resources/views/livewire/front/customs/customs-notifications.blade.php --}}
<div dir="rtl" class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
  
  {{-- Hero Section --}}
  <section class="relative bg-gradient-to-l from-[#0F2E5D] via-[#1a3f6e] to-[#0F2E5D] text-white py-16 overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6 backdrop-blur-sm">
        <i class="fas fa-bell text-4xl text-yellow-400"></i>
      </div>
      <h1 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">
        الإشعارات والتقييمات
      </h1>
      <p class="text-xl text-blue-100 max-w-3xl mx-auto">
        تابع حالة شحناتك وقيّم تجربتك مع المستخلصين الجمركيين
      </p>
    </div>
  </section>

  {{-- Main Content: Two Columns --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      {{-- Column A: Notifications Feed (8 cols) --}}
      <div class="lg:col-span-8 space-y-6">
        
        {{-- Notifications Header --}}
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
              <i class="fas fa-inbox text-blue-600"></i>
              سجل الإشعارات
            </h2>
            @if($unreadCount > 0)
              <span class="px-4 py-2 bg-red-100 text-red-600 rounded-full font-bold text-sm">
                {{ $unreadCount }} جديد
              </span>
            @endif
          </div>
          
          {{-- Filter Tabs --}}
          <div class="flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold text-sm">
              الكل ({{ count($notifications) }})
            </button>
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition text-sm">
              غير مقروء ({{ $unreadCount }})
            </button>
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition text-sm">
              الشحنات
            </button>
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition text-sm">
              المدفوعات
            </button>
          </div>
        </div>

        {{-- Notifications List --}}
        <div class="space-y-4">
          @forelse($notifications as $notification)
            @php
              $colorMap = [
                'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'border' => 'border-green-300'],
                'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'border' => 'border-blue-300'],
                'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'border' => 'border-orange-300'],
                'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'border' => 'border-purple-300'],
                'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'border' => 'border-red-300'],
              ];
              $colors = $colorMap[$notification['color']] ?? $colorMap['blue'];
            @endphp
            
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden border-r-4 {{ $colors['border'] }} {{ !$notification['read'] ? 'ring-2 ring-blue-200' : '' }}">
              <div class="p-6">
                <div class="flex items-start gap-4">
                  {{-- Icon --}}
                  <div class="flex items-center justify-center w-12 h-12 {{ $colors['bg'] }} rounded-full flex-shrink-0">
                    <i class="fas fa-{{ $notification['icon'] }} {{ $colors['text'] }} text-xl"></i>
                  </div>
                  
                  {{-- Content --}}
                  <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4 mb-2">
                      <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                        {{ $notification['title'] }}
                        @if(!$notification['read'])
                          <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                        @endif
                      </h3>
                      <span class="text-xs text-gray-500 whitespace-nowrap">{{ $notification['time'] }}</span>
                    </div>
                    <p class="text-gray-600 mb-3 leading-relaxed">{{ $notification['message'] }}</p>
                    
                    {{-- Meta Info --}}
                    @if(isset($notification['meta']))
                      <div class="flex flex-wrap items-center gap-2 mb-3">
                        @foreach($notification['meta'] as $key => $value)
                          <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                            {{ is_numeric($value) && $key === 'amount' ? number_format($value) . ' ر.س' : $value }}
                          </span>
                        @endforeach
                      </div>
                    @endif
                    
                    {{-- Actions --}}
                    <div class="flex items-center gap-2">
                      @if(!$notification['read'])
                        <button wire:click="markAsRead({{ $notification['id'] }})" 
                                class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition">
                          <i class="fas fa-check ml-1"></i>
                          وضع كمقروء
                        </button>
                      @endif
                      <button wire:click="deleteNotification({{ $notification['id'] }})" 
                              class="px-3 py-1.5 bg-red-100 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-200 transition">
                        <i class="fas fa-trash ml-1"></i>
                        حذف
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
              <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
              <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد إشعارات</h3>
              <p class="text-gray-500">ستظهر جميع التحديثات والإشعارات هنا</p>
            </div>
          @endforelse
        </div>

        {{-- Notification Settings --}}
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-cog text-gray-600"></i>
            إعدادات الإشعارات
          </h3>
          
          <form wire:submit.prevent="updateNotificationSettings" class="space-y-4">
            <label class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition cursor-pointer">
              <div class="flex items-center gap-3">
                <i class="fas fa-envelope text-blue-600 text-xl"></i>
                <div>
                  <div class="font-semibold text-gray-900">إشعارات البريد الإلكتروني</div>
                  <div class="text-sm text-gray-600">تلقي تحديثات عبر البريد الإلكتروني</div>
                </div>
              </div>
              <input type="checkbox" wire:model="notify_email" class="w-5 h-5 text-blue-600 rounded">
            </label>

            <label class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition cursor-pointer">
              <div class="flex items-center gap-3">
                <i class="fas fa-sms text-green-600 text-xl"></i>
                <div>
                  <div class="font-semibold text-gray-900">إشعارات الرسائل النصية (SMS)</div>
                  <div class="text-sm text-gray-600">استلام رسائل نصية للتحديثات الهامة</div>
                </div>
              </div>
              <input type="checkbox" wire:model="notify_sms" class="w-5 h-5 text-blue-600 rounded">
            </label>

            <label class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition cursor-pointer">
              <div class="flex items-center gap-3">
                <i class="fas fa-shipping-fast text-purple-600 text-xl"></i>
                <div>
                  <div class="font-semibold text-gray-900">تحديثات الشحنات</div>
                  <div class="text-sm text-gray-600">إشعارات فورية عند تغير حالة الشحنة</div>
                </div>
              </div>
              <input type="checkbox" wire:model="notify_shipment" class="w-5 h-5 text-blue-600 rounded">
            </label>

            <label class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition cursor-pointer">
              <div class="flex items-center gap-3">
                <i class="fas fa-tag text-orange-600 text-xl"></i>
                <div>
                  <div class="font-semibold text-gray-900">العروض والخصومات</div>
                  <div class="text-sm text-gray-600">تلقي إشعارات العروض الخاصة</div>
                </div>
              </div>
              <input type="checkbox" wire:model="notify_offers" class="w-5 h-5 text-blue-600 rounded">
            </label>

            <button type="submit" class="w-full py-3 bg-gradient-to-l from-blue-600 to-blue-700 text-white rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition shadow-md">
              <i class="fas fa-save ml-2"></i>
              حفظ الإعدادات
            </button>
            
            @if(session()->has('settings_success'))
              <div class="p-4 bg-green-100 text-green-700 rounded-xl font-semibold">
                <i class="fas fa-check-circle ml-2"></i>
                {{ session('settings_success') }}
              </div>
            @endif
          </form>
        </div>
      </div>

      {{-- Column B: Ratings Panel (4 cols) --}}
      <div class="lg:col-span-4 space-y-6">
        
        {{-- Submit Rating Card --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
          <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-star text-yellow-500"></i>
            قيّم المستخلص
          </h3>
          
          <form wire:submit.prevent="submitRating" class="space-y-4">
            {{-- Broker Selection --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-sm">اختر المستخلص</label>
              <select wire:model="rating_broker_id" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">-- اختر --</option>
                <option value="1">مؤسسة الخليج للتخليص الجمركي</option>
                <option value="2">شركة الرائد للخدمات الجمركية</option>
                <option value="3">مكتب السريع الجمركي</option>
              </select>
            </div>

            {{-- Star Rating --}}
            <div>
              <label class="block text-gray-700 font-medium mb-3 text-sm">التقييم</label>
              <div class="flex items-center justify-center gap-2">
                @for($i = 1; $i <= 5; $i++)
                  <button type="button" 
                          wire:click="$set('rating_stars', {{ $i }})"
                          class="text-4xl transition-all duration-200 hover:scale-110 {{ $rating_stars >= $i ? 'text-yellow-400' : 'text-gray-300' }}">
                    <i class="fas fa-star"></i>
                  </button>
                @endfor
              </div>
              @if($rating_stars > 0)
                <p class="text-center mt-2 font-semibold text-gray-700">
                  {{ $rating_stars }} من 5 نجوم
                </p>
              @endif
            </div>

            {{-- Comment --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-sm">تعليقك (اختياري)</label>
              <textarea wire:model="rating_comment" 
                        rows="4" 
                        placeholder="شارك تجربتك مع المستخلص..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right resize-none"></textarea>
              <p class="text-xs text-gray-500 mt-1">الحد الأقصى: 500 حرف</p>
            </div>

            <button type="submit" 
                    class="w-full py-3 bg-gradient-to-l from-yellow-500 to-amber-600 text-white rounded-xl font-bold hover:from-yellow-600 hover:to-amber-700 transition shadow-md hover:shadow-lg">
              <i class="fas fa-paper-plane ml-2"></i>
              إرسال التقييم
            </button>
            
            @if(session()->has('rating_success'))
              <div class="p-4 bg-green-100 text-green-700 rounded-xl font-semibold text-sm">
                <i class="fas fa-check-circle ml-2"></i>
                {{ session('rating_success') }}
              </div>
            @endif
          </form>
        </div>

        {{-- User Stats --}}
        <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
          <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line"></i>
            إحصائياتك
          </h3>
          <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-white/10 backdrop-blur-sm rounded-xl">
              <span class="text-sm">الشحنات المنجزة</span>
              <span class="text-2xl font-bold">{{ $user_stats['completed_shipments'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-white/10 backdrop-blur-sm rounded-xl">
              <span class="text-sm">التقييمات المُرسلة</span>
              <span class="text-2xl font-bold">{{ $user_stats['ratings_given'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-white/10 backdrop-blur-sm rounded-xl">
              <span class="text-sm">متوسط زمن التخليص</span>
              <span class="text-2xl font-bold">{{ $user_stats['avg_clearance_time'] ?? 0 }} يوم</span>
            </div>
          </div>
        </div>

        {{-- @todo: Ratings Table Placeholder --}}
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <i class="fas fa-table text-blue-600"></i>
            جدول التقييمات
          </h3>
          <div class="text-center py-8">
            <i class="fas fa-database text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm">@todo: جدول قابل للفرز</p>
            <p class="text-gray-400 text-xs mt-2">(تقييمات الموقع الآلية + تقييمات العملاء اليدوية)</p>
          </div>
        </div>

      </div>
    </div>
  </section>

</div>
