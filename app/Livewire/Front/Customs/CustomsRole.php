<?php

namespace App\Livewire\Front\Customs;

use Livewire\Component;

/**
 * CustomsRole Component - دور المستخلص الجمركي
 * 
 * Educational page about customs broker role and services
 * @todo: Add dynamic content management system
 * @todo: Add multilingual support for services descriptions
 */
class CustomsRole extends Component
{
    // Services data
    public $services = [];
    public $benefits = [];
    public $clearanceStages = []; // Timeline stages

    public function mount()
    {
        $this->loadServicesData();
        $this->loadBenefitsData();
        $this->loadClearanceStages();
    }

    /**
     * Load services data
     * @todo: Fetch from database/CMS
     */
    private function loadServicesData()
    {
        $this->services = [
            [
                'id' => 1,
                'title' => 'إعداد الوثائق الجمركية',
                'description' => 'تحضير وتجهيز جميع المستندات المطلوبة للتخليص الجمركي بما في ذلك البيان الجمركي، الفواتير التجارية، شهادات المنشأ، وقوائم التعبئة.',
                'icon' => 'fa-file-alt',
                'color' => 'blue',
            ],
            [
                'id' => 2,
                'title' => 'حساب الرسوم الجمركية',
                'description' => 'تحديد وحساب الرسوم الجمركية والضرائب المستحقة بدقة وفقاً للتعريفة الجمركية ونوع البضاعة وقيمتها.',
                'icon' => 'fa-calculator',
                'color' => 'green',
            ],
            [
                'id' => 3,
                'title' => 'التمثيل أمام الجمارك',
                'description' => 'تمثيلك الرسمي أمام السلطات الجمركية في جميع المعاملات والإجراءات، مما يوفر عليك الوقت والجهد.',
                'icon' => 'fa-handshake',
                'color' => 'purple',
            ],
            [
                'id' => 4,
                'title' => 'تسريع إجراءات الإفراج',
                'description' => 'تسهيل وتسريع عملية الإفراج عن البضائع من الموانئ والمطارات من خلال الخبرة والعلاقات الجيدة مع الجهات المعنية.',
                'icon' => 'fa-shipping-fast',
                'color' => 'teal',
            ],
            [
                'id' => 5,
                'title' => 'الحصول على التصاريح',
                'description' => 'المساعدة في الحصول على التصاريح والموافقات اللازمة من الجهات المختصة لبعض أنواع البضائع الخاصة.',
                'icon' => 'fa-certificate',
                'color' => 'orange',
            ],
            [
                'id' => 6,
                'title' => 'حل المشاكل الجمركية',
                'description' => 'معالجة أي مشاكل أو تعقيدات قد تنشأ خلال عملية التخليص الجمركي، مثل نقص المستندات أو تصنيف البضائع.',
                'icon' => 'fa-shield-alt',
                'color' => 'red',
            ],
        ];
    }

    /**
     * Load benefits data
     * @todo: Fetch from database/CMS
     */
    private function loadBenefitsData()
    {
        $this->benefits = [
            [
                'id' => 1,
                'title' => 'توفير الوقت',
                'description' => 'تقليل مدة التخليص الجمركي بشكل كبير من خلال الخبرة والكفاءة',
                'icon' => 'fa-clock',
                'color' => 'yellow',
            ],
            [
                'id' => 2,
                'title' => 'توفير التكلفة',
                'description' => 'تجنب الغرامات والرسوم الإضافية الناتجة عن الأخطاء في الإجراءات',
                'icon' => 'fa-money-bill-wave',
                'color' => 'green',
            ],
            [
                'id' => 3,
                'title' => 'الامتثال الكامل',
                'description' => 'ضمان الامتثال لجميع الأنظمة واللوائح الجمركية المحلية والدولية',
                'icon' => 'fa-check-circle',
                'color' => 'green',
            ],
        ];
    }
    
    /**
     * Load customs clearance stages (Timeline)
     * @todo: Fetch from database/CMS
     * @todo: Add file upload functionality for each stage
     * @todo: Implement notification system for stage updates
     */
    private function loadClearanceStages()
    {
        $this->clearanceStages = [
            [
                'id' => 1,
                'title' => 'تحضير الملف',
                'description' => 'جمع جميع المستندات المطلوبة وتجهيز الملف الأولي للتخليص',
                'documents' => [
                    'الفاتورة التجارية (Commercial Invoice)',
                    'قائمة التعبئة (Packing List)',
                    'بوليصة الشحن (Bill of Lading / Airway Bill)',
                    'شهادة المنشأ (Certificate of Origin)',
                    'السجل التجاري وترخيص الاستيراد',
                ],
                'typical_time' => '1-2 يوم عمل',
                'icon' => 'fa-folder-open',
                'color' => 'blue',
                'status' => 'pending', // @todo: dynamic from DB
            ],
            [
                'id' => 2,
                'title' => 'مراجعة المستندات',
                'description' => 'التدقيق الشامل لجميع الوثائق والتأكد من صحتها واكتمالها',
                'documents' => [
                    'التحقق من تطابق الأوزان والكميات',
                    'مراجعة التعريفة الجمركية للمنتجات',
                    'التأكد من موافقة الأسعار للقيمة الجمركية',
                    'فحص صلاحية الشهادات والتراخيص',
                ],
                'typical_time' => '3-6 ساعات',
                'icon' => 'fa-search',
                'color' => 'green',
                'status' => 'pending',
            ],
            [
                'id' => 3,
                'title' => 'التقديم الإلكتروني',
                'description' => 'رفع البيان الجمركي إلكترونياً عبر منصة الجمارك (FASAH/سابر)',
                'documents' => [
                    'إنشاء البيان الجمركي الإلكتروني',
                    'إرفاق المستندات الممسوحة ضوئياً',
                    'حساب الرسوم والضرائب المستحقة',
                    'تقديم طلب الفسح عبر النظام',
                ],
                'typical_time' => '2-4 ساعات',
                'icon' => 'fa-upload',
                'color' => 'purple',
                'status' => 'pending',
            ],
            [
                'id' => 4,
                'title' => 'الحجز والكشف',
                'description' => 'حجز موعد الكشف الميداني والتنسيق مع السلطات الجمركية',
                'documents' => [
                    'استلام إشعار الكشف من الجمارك',
                    'التنسيق مع شركة النقل لتجهيز الحاوية',
                    'حضور الكشف الفعلي على البضائع',
                    'معالجة أي ملاحظات أو استفسارات',
                ],
                'typical_time' => '1-3 أيام عمل',
                'icon' => 'fa-clipboard-check',
                'color' => 'orange',
                'status' => 'pending',
            ],
            [
                'id' => 5,
                'title' => 'الإفراج الجمركي',
                'description' => 'استكمال الإجراءات وسداد الرسوم للحصول على إذن الإفراج',
                'documents' => [
                    'سداد الرسوم الجمركية والضرائب',
                    'استلام إذن الإفراج الرسمي',
                    'إنهاء إجراءات التأمين (إن وجد)',
                    'تسليم المستندات لشركة النقل',
                ],
                'typical_time' => '4-8 ساعات',
                'icon' => 'fa-check-circle',
                'color' => 'teal',
                'status' => 'pending',
            ],
            [
                'id' => 6,
                'title' => 'الخروج من الميناء',
                'description' => 'إتمام إجراءات الخروج ونقل البضائع من الميناء/المطار',
                'documents' => [
                    'إصدار بوابة الخروج (Gate Pass)',
                    'تسليم البضائع لشركة النقل المحلي',
                    'تحديث حالة الشحنة في النظام',
                    'إرسال نسخة المستندات للعميل',
                ],
                'typical_time' => '2-4 ساعات',
                'icon' => 'fa-truck-loading',
                'color' => 'indigo',
                'status' => 'pending',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.front.customs.customs-role');
    }
}
