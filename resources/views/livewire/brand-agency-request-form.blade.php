<div dir="rtl">
    @if(!$submitted)
        {{-- نموذج الطلب --}}
        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">📝 معلومات مقدم الطلب</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- الاسم الكامل --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            الاسم الكامل <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               wire:model="full_name" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('full_name') border-red-500 @enderror">
                        @error('full_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- اسم الشركة --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم الشركة</label>
                        <input type="text" 
                               wire:model="company_name" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    {{-- الدولة --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            الدولة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               wire:model="country" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('country') border-red-500 @enderror">
                        @error('country') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- المدينة --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المدينة</label>
                        <input type="text" 
                               wire:model="city" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- معلومات النشاط --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">💼 معلومات النشاط</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- القطاع --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            القطاع <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="sector" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sector') border-red-500 @enderror">
                            <option value="">اختر القطاع</option>
                            @foreach($this->sectors as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('sector') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- سنوات الخبرة --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            سنوات الخبرة <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               wire:model="experience_years" 
                               min="0" 
                               max="50"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('experience_years') border-red-500 @enderror">
                        @error('experience_years') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- القنوات الحالية --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">القنوات الحالية</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($this->channels as $channel)
                                <label class="flex items-center space-x-2 space-x-reverse">
                                    <input type="checkbox" 
                                           wire:model="current_channels" 
                                           value="{{ $channel }}"
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ $channel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- خطة التوسع --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">خطة التوسع</label>
                        <textarea wire:model="expansion_plan" 
                                  rows="4" 
                                  placeholder="اشرح خطتك للتوسع في السوق المحلي..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        <p class="text-xs text-gray-500 mt-1">خطة واضحة ومفصلة تزيد من فرص القبول</p>
                    </div>
                </div>
            </div>

            {{-- الوثائق --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">📎 الوثائق والمرفقات</h3>
                
                <div class="space-y-4">
                    {{-- التراخيص --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            التراخيص والشهادات (رخصة تجارية، شهادة ضريبية، إلخ)
                        </label>
                        <input type="file" 
                               wire:model="licenses" 
                               multiple 
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('licenses.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (حد أقصى 5 ميجابايت لكل ملف)</p>
                        
                        @if($licenses)
                            <div class="mt-2 space-y-1">
                                @foreach($licenses as $index => $license)
                                    <div class="text-xs text-green-600">✓ {{ $license->getClientOriginalName() }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- مرفقات إضافية --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">مرفقات إضافية (مراجع، صور أعمال)</label>
                        <input type="file" 
                               wire:model="attachments" 
                               multiple 
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('attachments.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        
                        @if($attachments)
                            <div class="mt-2 space-y-1">
                                @foreach($attachments as $index => $attachment)
                                    <div class="text-xs text-green-600">✓ {{ $attachment->getClientOriginalName() }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- معلومات الاتصال --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">📞 معلومات الاتصال</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- الهاتف --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            الهاتف <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               wire:model="phone" 
                               placeholder="+966XXXXXXXXX"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- واتساب --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">واتساب</label>
                        <input type="tel" 
                               wire:model="whatsapp" 
                               placeholder="+966XXXXXXXXX"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    {{-- البريد الإلكتروني --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            البريد الإلكتروني <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               wire:model="email" 
                               placeholder="example@domain.com"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- الموقع الإلكتروني --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الموقع الإلكتروني</label>
                        <input type="url" 
                               wire:model="website" 
                               placeholder="https://example.com"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('website') border-red-500 @enderror">
                        @error('website') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- زر الإرسال --}}
            <div class="flex justify-center">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="px-8 py-3 bg-gradient-to-l from-blue-600 to-blue-700 text-white font-bold rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove>إرسال الطلب 🚀</span>
                    <span wire:loading>جاري الإرسال...</span>
                </button>
            </div>
        </form>
    @else
        {{-- بطاقة النتيجة --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center space-y-6">
            <div class="text-6xl mb-4">
                @if($request->decision === 'accepted')
                    🎉
                @elseif($request->decision === 'conditional')
                    ⚠️
                @else
                    📋
                @endif
            </div>

            <h2 class="text-3xl font-bold text-gray-800">{{ $request->decision_message }}</h2>

            {{-- السكور --}}
            <div class="bg-gradient-to-l from-blue-50 to-blue-100 rounded-xl p-6">
                <p class="text-sm text-gray-600 mb-2">تقييمك الإجمالي</p>
                <p class="text-5xl font-bold text-blue-600">{{ $request->score_total }}/100</p>
            </div>

            {{-- شارة القرار --}}
            <div class="inline-block">
                <span class="px-6 py-3 rounded-full text-lg font-bold 
                    @if($request->decision === 'accepted') bg-green-100 text-green-800
                    @elseif($request->decision === 'conditional') bg-yellow-100 text-yellow-800
                    @elseif($request->decision === 'rejected') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ match($request->decision) {
                        'accepted' => 'مقبول ✓',
                        'conditional' => 'قبول مشروط',
                        'rejected' => 'مرفوض',
                        default => 'قيد المراجعة'
                    } }}
                </span>
            </div>

            {{-- التوصيات --}}
            @if($request->recommendations && count($request->recommendations) > 0)
                <div class="bg-amber-50 rounded-xl p-6 text-right">
                    <h3 class="text-lg font-bold text-amber-800 mb-4">💡 توصيات لتحسين ملفك</h3>
                    <ul class="space-y-2">
                        @foreach($request->recommendations as $recommendation)
                            <li class="text-sm text-amber-700 flex items-start">
                                <span class="ml-2">•</span>
                                <span>{{ $recommendation }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- معلومات إضافية --}}
            <div class="bg-gray-50 rounded-xl p-6 text-right">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📌 الخطوات التالية</h3>
                <div class="space-y-3 text-sm text-gray-700">
                    @if($request->decision === 'accepted')
                        <p>✓ سيتم التواصل معك خلال 48 ساعة</p>
                        <p>✓ سنرسل تفاصيل العقد عبر البريد الإلكتروني</p>
                        <p>✓ يمكنك متابعة حالة طلبك من خلال لوحة التحكم</p>
                    @elseif($request->decision === 'conditional')
                        <p>⚠️ يحتاج ملفك لمراجعة إضافية</p>
                        <p>⚠️ سيتم التواصل معك لاستكمال الوثائق</p>
                        <p>⚠️ بعد استكمال المتطلبات، سيتم إعادة التقييم</p>
                    @else
                        <p>📋 شكراً لتقديمك الطلب</p>
                        <p>📋 يمكنك التقديم مجدداً بعد تحسين ملفك</p>
                        <p>📋 راجع التوصيات أعلاه لمعرفة نقاط التحسين</p>
                    @endif
                </div>
            </div>

            {{-- أزرار --}}
            <div class="flex justify-center gap-4">
                <button wire:click="resetForm" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    تقديم طلب جديد
                </button>
                <a href="{{ route('front.home') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    العودة للرئيسية
                </a>
            </div>
        </div>
    @endif
</div>
