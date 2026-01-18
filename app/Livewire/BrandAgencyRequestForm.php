<?php

namespace App\Livewire;

use App\Models\BrandAgencyRequest;
use Livewire\Component;
use Livewire\WithFileUploads;

class BrandAgencyRequestForm extends Component
{
    use WithFileUploads;

    // الحقول
    public $full_name = '';
    public $company_name = '';
    public $country = '';
    public $city = '';
    public $sector = '';
    public $experience_years = 0;
    public $current_channels = [];
    public $expansion_plan = '';
    public $licenses = [];
    public $attachments = [];
    public $phone = '';
    public $whatsapp = '';
    public $email = '';
    public $website = '';

    // حالة النموذج
    public $submitted = false;
    public $request = null;

    /**
     * القواعد
     */
    protected function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'sector' => 'required|string|max:100',
            'experience_years' => 'required|integer|min:0|max:50',
            'current_channels' => 'nullable|array',
            'expansion_plan' => 'nullable|string|max:2000',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'licenses.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ];
    }

    /**
     * رسائل الأخطاء المخصصة
     */
    protected $messages = [
        'full_name.required' => 'الاسم الكامل مطلوب',
        'country.required' => 'الدولة مطلوبة',
        'sector.required' => 'القطاع مطلوب',
        'experience_years.required' => 'سنوات الخبرة مطلوبة',
        'phone.required' => 'رقم الهاتف مطلوب',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'البريد الإلكتروني غير صالح',
        'website.url' => 'رابط الموقع غير صالح',
        'licenses.*.mimes' => 'صيغة الملف غير مدعومة (PDF, JPG, PNG فقط)',
        'licenses.*.max' => 'حجم الملف يجب ألا يتجاوز 5 ميجابايت',
        'attachments.*.mimes' => 'صيغة الملف غير مدعومة',
        'attachments.*.max' => 'حجم الملف يجب ألا يتجاوز 5 ميجابايت',
    ];

    /**
     * القطاعات المتاحة
     */
    public function getSectorsProperty()
    {
        return [
            'أغذية ومشروبات',
            'إلكترونيات',
            'أزياء وملابس',
            'مستحضرات تجميل',
            'أثاث ومفروشات',
            'أدوات منزلية',
            'سيارات وقطع غيار',
            'مواد بناء',
            'أدوية ومستلزمات طبية',
            'ألعاب أطفال',
            'رياضة ولياقة',
            'مجوهرات وإكسسوارات',
            'أخرى',
        ];
    }

    /**
     * القنوات المتاحة
     */
    public function getChannelsProperty()
    {
        return [
            'متاجر تقليدية',
            'منصات إلكترونية',
            'نقاط بيع بالتجزئة',
            'موزعون',
            'سلاسل تجارية',
            'تسويق مباشر',
        ];
    }

    /**
     * إرسال النموذج
     */
    public function submit()
    {
        $this->validate();

        // رفع الملفات
        $licensePaths = [];
        if ($this->licenses) {
            foreach ($this->licenses as $license) {
                $licensePaths[] = $license->store('licenses', 'public');
            }
        }

        $attachmentPaths = [];
        if ($this->attachments) {
            foreach ($this->attachments as $attachment) {
                $attachmentPaths[] = $attachment->store('attachments', 'public');
            }
        }

        // إنشاء الطلب
        $request = BrandAgencyRequest::create([
            'full_name' => $this->full_name,
            'company_name' => $this->company_name,
            'country' => $this->country,
            'city' => $this->city,
            'sector' => $this->sector,
            'experience_years' => $this->experience_years,
            'current_channels' => $this->current_channels,
            'expansion_plan' => $this->expansion_plan,
            'licenses' => $licensePaths,
            'attachments' => $attachmentPaths,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'website' => $this->website,
        ]);

        // حساب السكور
        $score = $request->calculateScore();
        $decision = $request->determineDecision();

        $request->update([
            'score_total' => $score,
            'decision' => $decision,
        ]);

        // تعيين حالة الإرسال
        $this->submitted = true;
        $this->request = $request->fresh();

        // إرسال إشعار (اختياري - يمكن إضافته لاحقاً)
        // Mail::to($this->email)->send(new BrandAgencyRequestReceived($request));

        session()->flash('message', 'تم إرسال طلبك بنجاح!');
    }

    /**
     * إعادة تعيين النموذج
     */
    public function resetForm()
    {
        $this->reset();
        $this->submitted = false;
        $this->request = null;
    }

    public function render()
    {
        return view('livewire.brand-agency-request-form');
    }
}
