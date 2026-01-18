<?php

namespace App\Livewire\Shipping;

use Livewire\Component;

class ContainerTracker extends Component
{
    public $tracking_number = '';
    public $trackingData = null;
    public $searchPerformed = false;

    protected $rules = [
        'tracking_number' => 'required|string|min:8',
    ];

    public function trackShipment()
    {
        $this->validate();

        // TODO: Integrate with real tracking APIs
        $this->trackingData = [
            'tracking_number' => $this->tracking_number,
            'status' => 'في البحر',
            'current_location' => 'قرب ميناء جدة',
            'progress_percentage' => 65,
            'estimated_arrival' => now()->addDays(7)->format('Y-m-d'),
            'events' => [
                ['date' => '2024-01-15', 'location' => 'Shanghai Port', 'status' => 'تم التحميل', 'icon' => 'fa-anchor'],
                ['date' => '2024-01-18', 'location' => 'في البحر', 'status' => 'في الطريق', 'icon' => 'fa-ship'],
                ['date' => '2024-01-22', 'location' => 'عبور قناة السويس', 'status' => 'عبور قناة', 'icon' => 'fa-water'],
                ['date' => null, 'location' => 'ميناء جدة', 'status' => 'متوقع الوصول', 'icon' => 'fa-flag-checkered'],
            ],
        ];

        $this->searchPerformed = true;
    }

    public function render()
    {
        return view('livewire.shipping.container-tracker');
    }
}
