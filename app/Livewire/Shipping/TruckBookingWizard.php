<?php

namespace App\Livewire\Shipping;

use Livewire\Component;
use Livewire\WithFileUploads;

class TruckBookingWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 5;

    // Step 1: Route Details
    public $origin_city = '';
    public $destination_city = '';
    public $pickup_date = '';
    public $delivery_date = '';

    // Step 2: Cargo Details
    public $weight_kg = '';
    public $truck_type = 'flatbed';
    public $cargo_description = '';

    // Step 3: Documents
    public $invoice_file;
    public $packing_list_file;

    // Step 4: Payment
    public $payment_method = 'bank_transfer';

    // Step 5: Confirmation
    public $terms_accepted = false;

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

    protected function validateCurrentStep()
    {
        $rules = match($this->currentStep) {
            1 => [
                'origin_city' => 'required|string|min:2',
                'destination_city' => 'required|string|min:2',
                'pickup_date' => 'required|date|after:today',
            ],
            2 => [
                'weight_kg' => 'required|numeric|min:100',
                'cargo_description' => 'required|string|min:10',
            ],
            3 => [
                'invoice_file' => 'required|file|mimes:pdf,jpg,png|max:5120',
            ],
            4 => [
                'payment_method' => 'required|in:bank_transfer,credit_card,cod',
            ],
            5 => [
                'terms_accepted' => 'accepted',
            ],
            default => [],
        };

        $this->validate($rules);
    }

    public function submitBooking()
    {
        $this->validateCurrentStep();
        session()->flash('success', 'تم حجز الشاحنة بنجاح!');
        return redirect()->route('front.shipping.track-truck');
    }

    public function render()
    {
        return view('livewire.shipping.truck-booking-wizard');
    }
}
