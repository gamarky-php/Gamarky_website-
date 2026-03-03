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
            'status' => __('shipping.truck_tracker.demo.status_in_transit'),
            'current_location' => __('shipping.truck_tracker.demo.current_location'),
            'progress_percentage' => 45,
            'estimated_arrival' => now()->addHours(6)->format('Y-m-d H:i'),
            'driver_name' => __('shipping.truck_tracker.demo.driver_name'),
            'driver_phone' => '+966501234567',
            'truck_plate' => __('shipping.truck_tracker.demo.truck_plate'),
            'speed_kmh' => 85,
            'lat' => 24.7136,
            'lng' => 46.6753,
            'events' => [
                ['time' => '08:00', 'location' => __('shipping.truck_tracker.demo.events.start_location'), 'status' => __('shipping.truck_tracker.demo.events.started')],
                ['time' => '10:30', 'location' => __('shipping.truck_tracker.demo.events.station_location'), 'status' => __('shipping.truck_tracker.demo.events.short_stop')],
                ['time' => '12:00', 'location' => __('shipping.truck_tracker.demo.events.on_road_location'), 'status' => __('shipping.truck_tracker.demo.events.in_transit')],
            ],
        ];

        $this->searchPerformed = true;
    }

    public function render()
    {
        return view('livewire.shipping.truck-tracker');
    }
}
