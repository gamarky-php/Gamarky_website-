<?php

namespace App\Livewire\Shipping;

use Livewire\Component;
use Livewire\WithFileUploads;

class ContainerBookingWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 6;

    // Step 1: Shipping Details
    public $origin_port = '';
    public $destination_port = '';
    public $loading_date = '';
    public $arrival_date = '';
    public $cargo_type = 'general';

    // Step 2: Cargo Details
    public $weight_kg = '';
    public $cbm = '';
    public $container_type = '40GP';
    public $cargo_description = '';

    // Step 3: Documents Upload
    public $invoice_file;
    public $packing_list_file;
    public $coo_file;

    // Step 4: Insurance & Additional Services
    public $needs_insurance = false;
    public $cargo_value_usd = '';
    public $needs_customs_clearance = false;
    public $needs_door_delivery = false;

    // Step 5: Payment Method
    public $payment_method = 'bank_transfer';
    public $payment_confirmed = false;

    // Step 6: Confirmation
    public $terms_accepted = false;

    public function mount()
    {
        $this->loading_date = now()->addWeek()->format('Y-m-d');
    }

    public function nextStep()
    {
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step <= $this->currentStep || $step == $this->currentStep + 1) {
            $this->currentStep = $step;
        }
    }

    protected function validateCurrentStep()
    {
        $rules = match($this->currentStep) {
            1 => [
                'origin_port' => 'required|string|min:2',
                'destination_port' => 'required|string|min:2',
                'loading_date' => 'required|date|after:today',
            ],
            2 => [
                'weight_kg' => 'required|numeric|min:1',
                'cbm' => 'required|numeric|min:0.1',
                'cargo_description' => 'required|string|min:10',
            ],
            3 => [
                'invoice_file' => 'required|file|mimes:pdf,jpg,png|max:5120',
                'packing_list_file' => 'required|file|mimes:pdf,jpg,png|max:5120',
            ],
            4 => [
                'cargo_value_usd' => $this->needs_insurance ? 'required|numeric|min:1' : 'nullable',
            ],
            5 => [
                'payment_method' => 'required|in:bank_transfer,credit_card,cod',
            ],
            6 => [
                'terms_accepted' => 'accepted',
            ],
            default => [],
        };

        $this->validate($rules);
    }

    public function submitBooking()
    {
        $this->validateCurrentStep();

        // TODO: Save booking to database, send notifications
        session()->flash('success', 'تم حجز الشحنة بنجاح! سيتم التواصل معك خلال 24 ساعة.');

        return redirect()->route('front.shipping.track-container');
    }

    public function render()
    {
        return view('livewire.shipping.container-booking-wizard');
    }
}
