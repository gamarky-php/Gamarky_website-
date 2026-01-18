<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Services\Shipping\BookingService;

class ContainerBooking extends Component
{
    use WithFileUploads;

    // Stepper State
    public $currentStep = 1;
    public $totalSteps = 6;
    
    // Selected Quote from Previous Page
    public $selectedQuote;
    
    // Step 1: بيانات الشحنة
    public $shipper_name = '';
    public $shipper_company = '';
    public $shipper_address = '';
    public $shipper_phone = '';
    public $shipper_email = '';
    
    public $consignee_name = '';
    public $consignee_company = '';
    public $consignee_address = '';
    public $consignee_phone = '';
    public $consignee_email = '';
    
    public $cargo_description = '';
    public $hs_code = '';
    public $cargo_value = '';
    
    // Step 2: اختيار الحاوية
    public $container_type = '40GP';
    public $container_quantity = 1;
    public $container_ownership = 'carrier'; // carrier, shipper_owned
    public $special_requirements = [];
    
    // Step 3: المواعيد
    public $preferred_loading_date = '';
    public $time_window = 'morning'; // morning, afternoon, evening
    public $cutoff_acknowledgement = false;
    public $flexible_dates = false;
    
    // Step 4: المستندات والتأمين
    public $invoice_file;
    public $packing_list_file;
    public $certificate_of_origin_file;
    public $other_documents = [];
    
    public $insurance_required = true;
    public $insurance_type = 'basic'; // basic, comprehensive, custom
    public $insurance_value = '';
    public $insurance_coverage = [];
    
    // Step 5: المراجعة والدفع
    public $payment_method = 'bank_transfer'; // bank_transfer, credit_card, cash
    public $payment_terms = 'prepaid'; // prepaid, collect, third_party
    public $agreed_to_terms = false;
    public $promotional_code = '';
    
    // Step 6: التأكيد
    public $booking_reference = '';
    public $booking_confirmed = false;
    
    // Uploaded Files Paths
    public $uploaded_invoice_path = '';
    public $uploaded_packing_path = '';
    public $uploaded_coo_path = '';

    protected $listeners = ['fileUploaded' => 'handleFileUpload'];

    public function mount()
    {
        // Load selected quote from session
        $this->selectedQuote = session('selected_quote');
        
        if (!$this->selectedQuote) {
            session()->flash('error', 'يرجى اختيار عرض أولاً');
            return redirect()->route('front.shipping.container.quote');
        }
        
        // Pre-fill some data if user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            $this->shipper_name = $user->name;
            $this->shipper_email = $user->email;
            $this->shipper_phone = $user->phone ?? '';
        }
        
        // Set default loading date (1 week from now)
        $this->preferred_loading_date = now()->addWeek()->format('Y-m-d');
        
        // Set default container from quote
        $this->container_type = session('selected_quote.container_type', '40GP');
    }

    /**
     * Validation rules for each step
     */
    public function rules()
    {
        $rules = [];
        
        switch ($this->currentStep) {
            case 1: // بيانات الشحنة
                $rules = [
                    'shipper_name' => 'required|string|min:3',
                    'shipper_company' => 'required|string|min:2',
                    'shipper_address' => 'required|string|min:10',
                    'shipper_phone' => 'required|string|min:10',
                    'shipper_email' => 'required|email',
                    'consignee_name' => 'required|string|min:3',
                    'consignee_company' => 'required|string|min:2',
                    'consignee_address' => 'required|string|min:10',
                    'consignee_phone' => 'required|string|min:10',
                    'consignee_email' => 'required|email',
                    'cargo_description' => 'required|string|min:10',
                    'cargo_value' => 'required|numeric|min:1',
                ];
                break;
                
            case 2: // اختيار الحاوية
                $rules = [
                    'container_type' => 'required|in:20GP,40GP,40HQ,Reefer',
                    'container_quantity' => 'required|integer|min:1|max:10',
                    'container_ownership' => 'required|in:carrier,shipper_owned',
                ];
                break;
                
            case 3: // المواعيد
                $rules = [
                    'preferred_loading_date' => 'required|date|after:today',
                    'time_window' => 'required|in:morning,afternoon,evening',
                    'cutoff_acknowledgement' => 'accepted',
                ];
                break;
                
            case 4: // المستندات والتأمين
                $rules = [
                    'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480',
                    'packing_list_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480',
                    'certificate_of_origin_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480',
                    'insurance_required' => 'boolean',
                    'insurance_type' => 'required_if:insurance_required,true|in:basic,comprehensive,custom',
                ];
                break;
                
            case 5: // المراجعة والدفع
                $rules = [
                    'payment_method' => 'required|in:bank_transfer,credit_card,cash',
                    'payment_terms' => 'required|in:prepaid,collect,third_party',
                    'agreed_to_terms' => 'accepted',
                ];
                break;
        }
        
        return $rules;
    }

    protected $messages = [
        'shipper_name.required' => 'يرجى إدخال اسم الشاحن',
        'shipper_email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
        'consignee_name.required' => 'يرجى إدخال اسم المرسل إليه',
        'cargo_description.required' => 'يرجى وصف البضاعة',
        'preferred_loading_date.after' => 'يجب أن يكون تاريخ التحميل في المستقبل',
        'cutoff_acknowledgement.accepted' => 'يجب الموافقة على موعد القطع',
        'agreed_to_terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
    ];

    /**
     * Go to next step
     */
    public function nextStep()
    {
        $this->validate();
        
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
        
        // Auto-submit on last step
        if ($this->currentStep == $this->totalSteps) {
            $this->submitBooking();
        }
    }

    /**
     * Go to previous step
     */
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    /**
     * Jump to specific step (only if previous steps are completed)
     */
    public function goToStep($step)
    {
        if ($step <= $this->currentStep && $step >= 1 && $step <= $this->totalSteps) {
            $this->currentStep = $step;
        }
    }

    /**
     * Upload invoice file
     */
    public function updatedInvoiceFile()
    {
        $this->validate([
            'invoice_file' => 'file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);
        
        if ($this->invoice_file) {
            $this->uploaded_invoice_path = $this->invoice_file->store('bookings/invoices', 'public');
            session()->flash('file_success', 'تم رفع فاتورة Invoice بنجاح');
        }
    }

    /**
     * Upload packing list file
     */
    public function updatedPackingListFile()
    {
        $this->validate([
            'packing_list_file' => 'file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);
        
        if ($this->packing_list_file) {
            $this->uploaded_packing_path = $this->packing_list_file->store('bookings/packing-lists', 'public');
            session()->flash('file_success', 'تم رفع Packing List بنجاح');
        }
    }

    /**
     * Upload certificate of origin file
     */
    public function updatedCertificateOfOriginFile()
    {
        $this->validate([
            'certificate_of_origin_file' => 'file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);
        
        if ($this->certificate_of_origin_file) {
            $this->uploaded_coo_path = $this->certificate_of_origin_file->store('bookings/certificates', 'public');
            session()->flash('file_success', 'تم رفع شهادة المنشأ بنجاح');
        }
    }

    /**
     * Calculate total price
     */
    public function getTotalPriceProperty()
    {
        $basePrice = $this->selectedQuote['total_price'] ?? 0;
        $containerMultiplier = $this->container_quantity;
        
        $insuranceCost = 0;
        if ($this->insurance_required) {
            $insuranceCost = match($this->insurance_type) {
                'basic' => $basePrice * 0.02,
                'comprehensive' => $basePrice * 0.05,
                'custom' => $basePrice * 0.08,
                default => 0,
            };
        }
        
        return ($basePrice * $containerMultiplier) + $insuranceCost;
    }

    /**
     * Submit final booking
     */
    public function submitBooking()
    {
        // Final validation
        $this->validate();
        
        try {
            // Generate booking reference
            $this->booking_reference = 'BKG-' . strtoupper(Str::random(8));
            
            // Prepare booking data
            $bookingData = [
                'reference' => $this->booking_reference,
                'quote_id' => $this->selectedQuote['id'] ?? null,
                'user_id' => auth()->id(),
                
                // Shipment Info
                'shipper_name' => $this->shipper_name,
                'shipper_company' => $this->shipper_company,
                'shipper_address' => $this->shipper_address,
                'shipper_phone' => $this->shipper_phone,
                'shipper_email' => $this->shipper_email,
                'consignee_name' => $this->consignee_name,
                'consignee_company' => $this->consignee_company,
                'consignee_address' => $this->consignee_address,
                'consignee_phone' => $this->consignee_phone,
                'consignee_email' => $this->consignee_email,
                'cargo_description' => $this->cargo_description,
                'cargo_value' => $this->cargo_value,
                'hs_code' => $this->hs_code,
                
                // Container Info
                'container_type' => $this->container_type,
                'container_quantity' => $this->container_quantity,
                'container_ownership' => $this->container_ownership,
                'special_requirements' => $this->special_requirements,
                
                // Schedule
                'preferred_loading_date' => $this->preferred_loading_date,
                'time_window' => $this->time_window,
                'flexible_dates' => $this->flexible_dates,
                
                // Documents
                'invoice_path' => $this->uploaded_invoice_path,
                'packing_list_path' => $this->uploaded_packing_path,
                'certificate_of_origin_path' => $this->uploaded_coo_path,
                
                // Insurance
                'insurance_required' => $this->insurance_required,
                'insurance_type' => $this->insurance_type,
                'insurance_value' => $this->insurance_value,
                
                // Payment
                'payment_method' => $this->payment_method,
                'payment_terms' => $this->payment_terms,
                'total_price' => $this->totalPrice,
                
                'status' => 'pending',
                'created_at' => now(),
            ];
            
            // Use BookingService to create booking
            $bookingService = app(BookingService::class);
            $booking = $bookingService->createBooking($bookingData);
            
            // Send notifications (Email, WhatsApp, Site)
            $bookingService->sendNotifications($booking);
            
            // Add performance points for agent (if applicable)
            if (isset($this->selectedQuote['provider'])) {
                $bookingService->addAgentPoints($this->selectedQuote['provider'], 10);
            }
            
            $this->booking_confirmed = true;
            
            session()->flash('success', 'تم إنشاء الحجز بنجاح! رقم المرجع: ' . $this->booking_reference);
            
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء إنشاء الحجز: ' . $e->getMessage());
        }
    }

    /**
     * Download booking confirmation
     */
    public function downloadConfirmation()
    {
        // This will generate a PDF confirmation
        // Implementation in BookingService
        return app(BookingService::class)->generateConfirmationPDF($this->booking_reference);
    }

    public function render()
    {
        return view('livewire.container-booking')
            ->layout('layouts.app');
    }
}
