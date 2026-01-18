@extends('layouts.front')

@section('title', 'وكلاء العلامات التجارية')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white" dir="rtl">
    {{-- Hero Section --}}
    <section class="bg-gradient-to-l from-purple-600 to-indigo-700 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    🏆 وكلاء العلامات التجارية
                </h1>
                <p class="text-xl md:text-2xl text-purple-100 mb-8">
                    اربط علامتك بوكلاء محليين مؤهلين — تمثيل احترافي وتوسع مضمون
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm md:text-base">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3">
                        ✓ وكلاء متخصصون
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3">
                        ✓ تدقيق آلي
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3">
                        ✓ عقود موثقة
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="py-12 bg-white border-b">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">{{ \App\Models\BrandAgencyRequest::count() }}+</div>
                    <div class="text-gray-600">طلب وكالة</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ \App\Models\BrandAgencyRequest::where('decision', 'accepted')->count() }}</div>
                    <div class="text-gray-600">وكالة مقبولة</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ \App\Models\Brand::count() }}+</div>
                    <div class="text-gray-600">علامة تجارية</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Brands Grid (Sample) --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                    🌟 علامات تجارية تبحث عن وكلاء
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    {{-- Sample Brand Cards --}}
                    @php
                        $sampleBrands = [
                            ['name' => 'TechVision', 'sector' => 'إلكترونيات', 'countries' => ['السعودية', 'الإمارات'], 'color' => 'blue'],
                            ['name' => 'FreshFood Co.', 'sector' => 'أغذية ومشروبات', 'countries' => ['مصر', 'الأردن'], 'color' => 'green'],
                            ['name' => 'FashionStyle', 'sector' => 'أزياء', 'countries' => ['الكويت', 'قطر'], 'color' => 'pink'],
                            ['name' => 'HomeComfort', 'sector' => 'أثاث', 'countries' => ['السعودية', 'البحرين'], 'color' => 'amber'],
                            ['name' => 'BeautyGlow', 'sector' => 'مستحضرات تجميل', 'countries' => ['الإمارات', 'لبنان'], 'color' => 'purple'],
                            ['name' => 'SportsPro', 'sector' => 'رياضة', 'countries' => ['عمان', 'السعودية'], 'color' => 'red'],
                        ];
                    @endphp

                    @foreach($sampleBrands as $brand)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-{{ $brand['color'] }}-100 rounded-full flex items-center justify-center text-{{ $brand['color'] }}-600 font-bold text-xl mr-3">
                                    {{ substr($brand['name'], 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ $brand['name'] }}</h3>
                                    <p class="text-xs text-gray-500">{{ $brand['sector'] }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">الدول المطلوبة:</span>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($brand['countries'] as $country)
                                            <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $country }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <button class="w-full bg-{{ $brand['color'] }}-600 text-white py-2 rounded-lg hover:bg-{{ $brand['color'] }}-700 transition text-sm font-medium">
                                قدّم طلب وكالة
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 text-center">
                    <p class="text-blue-800 font-medium mb-2">هل علامتك التجارية غير موجودة؟</p>
                    <p class="text-blue-600 text-sm">قدّم طلباً عاماً وسنربطك بالعلامات المناسبة</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Application Form Section --}}
    <section class="py-16 bg-gradient-to-l from-purple-50 to-indigo-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">
                        📝 قدّم طلب وكالة علامة تجارية
                    </h2>
                    <p class="text-gray-600 text-lg">
                        املأ النموذج وسنقوم بتقييم طلبك آلياً خلال دقائق
                    </p>
                </div>

                <livewire:brand-agency-request-form />
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                    كيف يعمل نظام التقييم؟
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6">
                        <div class="text-4xl mb-4">📄</div>
                        <h3 class="font-bold text-gray-800 mb-3">1. التدقيق الآلي</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>✓ فحص الوثائق (20 نقطة)</li>
                            <li>✓ تقييم الخبرة (20 نقطة)</li>
                            <li>✓ القدرات التشغيلية (20 نقطة)</li>
                        </ul>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6">
                        <div class="text-4xl mb-4">⚖️</div>
                        <h3 class="font-bold text-gray-800 mb-3">2. التقييم الشامل</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>✓ الأداء المتوقع (20 نقطة)</li>
                            <li>✓ الجاهزية التقنية (10 نقاط)</li>
                            <li>✓ المرفقات (10 نقاط)</li>
                        </ul>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6">
                        <div class="text-4xl mb-4">✅</div>
                        <h3 class="font-bold text-gray-800 mb-3">3. القرار الفوري</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>✓ مقبول (85+ نقطة)</li>
                            <li>✓ مشروط (70-84 نقطة)</li>
                            <li>✓ مرفوض (&lt;70 نقطة)</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 bg-gradient-to-l from-amber-50 to-yellow-50 rounded-2xl p-8 border-2 border-amber-200">
                    <div class="flex items-start">
                        <div class="text-4xl ml-4">💡</div>
                        <div>
                            <h3 class="font-bold text-amber-800 mb-3">نصائح لزيادة فرص القبول</h3>
                            <ul class="text-amber-700 space-y-2">
                                <li>✓ أرفق جميع الوثائق الرسمية (رخصة تجارية، شهادة ضريبية)</li>
                                <li>✓ اكتب خطة توسع واضحة ومفصلة (أكثر من 50 كلمة)</li>
                                <li>✓ أضف قنوات التوزيع الحالية</li>
                                <li>✓ أرفق مراجع أو صور لأعمال سابقة</li>
                                <li>✓ أضف رابط الموقع الإلكتروني إن وجد</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefits Section --}}
    <section class="py-16 bg-gradient-to-l from-indigo-50 to-purple-50">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                    لماذا تصبح وكيلاً معتمداً؟
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-md p-6 flex items-start">
                        <div class="text-3xl ml-4">🌍</div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">وصول لعلامات عالمية</h3>
                            <p class="text-gray-600 text-sm">فرصة للتعاون مع علامات تجارية دولية مرموقة</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 flex items-start">
                        <div class="text-3xl ml-4">📈</div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">نمو مضمون</h3>
                            <p class="text-gray-600 text-sm">عقود طويلة الأمد وحصرية في منطقتك</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 flex items-start">
                        <div class="text-3xl ml-4">🤝</div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">دعم كامل</h3>
                            <p class="text-gray-600 text-sm">تدريب وتسويق ودعم فني من أصحاب العلامات</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 flex items-start">
                        <div class="text-3xl ml-4">⚡</div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">عملية سريعة</h3>
                            <p class="text-gray-600 text-sm">تقييم فوري وقرار خلال 48 ساعة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Paid Services Section --}}
    <section class="py-16 bg-white border-t">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">
                    💼 خدمات مدفوعة للوكلاء
                </h2>
                <p class="text-center text-gray-600 mb-12">خدمات احترافية لتعزيز نجاحك كوكيل تجاري</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="text-4xl mb-4">👨‍💼</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">استشارة خبير تصدير</h3>
                        <p class="text-gray-600 mb-4">جلسة استشارية مع خبراء التصدير والاستيراد لتطوير استراتيجيتك</p>
                        <a href="#" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            احجز استشارة
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="text-4xl mb-4">📚</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">برنامج تدريب الوكلاء</h3>
                        <p class="text-gray-600 mb-4">دورة شاملة لتأهيل الوكلاء الجدد وتطوير مهارات البيع والتسويق</p>
                        <a href="#" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                            سجّل الآن
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 bg-gradient-to-l from-purple-600 to-indigo-700 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">هل أنت صاحب علامة تجارية؟</h2>
            <p class="text-xl text-purple-100 mb-8">سجّل علامتك وابحث عن وكلاء محليين موثوقين</p>
            <a href="#" class="inline-block bg-white text-purple-600 px-8 py-3 rounded-lg font-bold hover:bg-purple-50 transition">
                سجّل علامتك التجارية
            </a>
        </div>
    </section>
</div>
@endsection
