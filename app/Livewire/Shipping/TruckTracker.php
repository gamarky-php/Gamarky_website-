<?php

namespace App\Livewire\Shipping;

use Livewire\Component;

class TruckTracker extends Component
{
    public $tracking_number = '';
    public $trackingData = null;
    public $searchPerformed = false;

    protected $rules = [
        'tracking_number' => 'required|string|min:6',
    ];

    public function trackShipment()
    {
        $this->validate();

        // TODO: Integrate GPS tracking API
        $this->trackingData = [
            'tracking_number' => $this->tracking_number,
            'status' => 'في الطريق',
            'current_location' => 'الرياض - طريق الدمام السريع',
            'progress_percentage' => 45,
            'estimated_arrival' => now()->addHours(6)->format('Y-m-d H:i'),
            'driver_name' => 'أحمد محمد',
            'driver_phone' => '+966501234567',
            'truck_plate' => 'ر ب ج 1234',
            'speed_kmh' => 85,
            'lat' => 24.7136,
            'lng' => 46.6753,
            'events' => [
                ['time' => '08:00', 'location' => 'الرياض - نقطة انطلاق', 'status' => 'بدء الرحلة'],
                ['time' => '10:30', 'location' => 'محطة وقود - الخرج', 'status' => 'توقف قصير'],
                ['time' => '12:00', 'location' => 'على الطريق', 'status' => 'في الطريق'],
            ],
        ];

        $this->searchPerformed = true;
    }

    public function render()
    {
        return view('livewire.shipping.truck-tracker');
    }
}
