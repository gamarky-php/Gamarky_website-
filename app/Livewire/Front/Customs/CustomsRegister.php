<?php

namespace App\Livewire\Front\Customs;

use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * CustomsRegister Component - تسجيل مستخلص جمركي
 * 
 * @todo: Implement file upload validation and storage
 * @todo: Add email verification for registration
 * @todo: Integrate with approval workflow
 * @todo: Add SMS OTP verification
 */
class CustomsRegister extends Component
{
    use WithFileUploads;

    // Company Information
    public $company_name = '';
    public $commercial_registration = '';
    public $customs_license = '';
    public $experience_years = '';

    // Contact Information
    public $contact_name = '';
    public $mobile = '';
    public $email = '';
    public $phone = '';

    // Location & Services
    public $city = '';
    public $ports = [];
    public $specialties = [];

    // Documents
    public $doc_commercial_registration;
    public $doc_customs_license;
    public $doc_zakat_certificate;

    // Additional Info
    public $company_description = '';
    public $terms_accepted = false;

    // Form steps
    public $currentStep = 1;
    
    // Data arrays
    public $availablePorts = [];
    public $availableSpecialties = [];

    public function mount()
    {
        $this->availablePorts = $this->getAvailablePorts();
        $this->availableSpecialties = $this->getAvailableSpecialties();
    }

    /**
     * Validation rules
     * @todo: Add more specific validation rules
     */
    protected function rules()
    {
        return [
            'company_name' => 'required|string|min:3|max:200',
            'commercial_registration' => 'required|string|min:10|max:20',
            'customs_license' => 'required|string|min:6|max:20',
            'experience_years' => 'required|in:1-3,4-7,8-10,10+',
            'contact_name' => 'required|string|min:3|max:100',
            'mobile' => 'required|string|regex:/^05[0-9]{8}$/',
            'email' => 'required|email|max:100',
            'city' => 'required|string',
            'ports' => 'required|array|min:1',
            'doc_commercial_registration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_customs_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'terms_accepted' => 'accepted',
        ];
    }

    /**
     * Submit registration
     * @todo: Implement actual registration logic
     */
    public function submitRegistration()
    {
        $this->validate();

        // @todo: Upload documents to storage
        // @todo: Create broker registration record in database
        // @todo: Send verification email
        // @todo: Send notification to admin for approval
        // @todo: Create user account with pending status

        session()->flash('registration_success', 'تم إرسال طلب التسجيل بنجاح! سيتم مراجعته خلال 24-48 ساعة.');
        
        // Reset form
        $this->reset();
    }

    /**
     * Move to next step
     */
    public function nextStep()
    {
        $this->validateCurrentStep();
        $this->currentStep++;
    }

    /**
     * Move to previous step
     */
    public function previousStep()
    {
        $this->currentStep--;
    }

    /**
     * Validate current step
     * @todo: Add step-specific validation
     */
    private function validateCurrentStep()
    {
        // Step-specific validation can be added here
    }

    /**
     * Get available ports
     * @todo: Fetch from database
     */
    public function getAvailablePorts()
    {
        return [
            'jeddah' => 'ميناء جدة الإسلامي',
            'dammam' => 'ميناء الملك عبدالعزيز - الدمام',
            'jubail' => 'ميناء الجبيل',
            'yanbu' => 'ميناء ينبع',
            'riyadh' => 'مطار الملك خالد - الرياض',
            'jeddah-airport' => 'مطار الملك عبدالعزيز - جدة',
        ];
    }

    /**
     * Get available specialties
     * @todo: Fetch from database
     */
    public function getAvailableSpecialties()
    {
        return [
            'general' => 'بضائع عامة',
            'food' => 'مواد غذائية',
            'medical' => 'مستلزمات طبية',
            'electronics' => 'إلكترونيات',
            'machinery' => 'معدات وآلات',
            'chemicals' => 'مواد كيميائية',
        ];
    }

    public function render()
    {
        return view('livewire.front.customs.customs-register', [
            'available_ports' => $this->getAvailablePorts(),
            'available_specialties' => $this->getAvailableSpecialties(),
        ]);
    }
}
