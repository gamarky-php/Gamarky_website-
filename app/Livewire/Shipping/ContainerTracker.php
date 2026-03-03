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
            'status' => __('shipping.container_tracker.demo.status_at_sea'),
            'current_location' => __('shipping.container_tracker.demo.current_location'),
            'progress_percentage' => 65,
            'estimated_arrival' => now()->addDays(7)->format('Y-m-d'),
            'events' => [
                ['date' => '2024-01-15', 'location' => __('shipping.container_tracker.demo.events.shanghai_port'), 'status' => __('shipping.container_tracker.demo.events.loaded'), 'icon' => 'fa-anchor'],
                ['date' => '2024-01-18', 'location' => __('shipping.container_tracker.demo.events.at_sea'), 'status' => __('shipping.container_tracker.demo.events.in_transit'), 'icon' => 'fa-ship'],
                ['date' => '2024-01-22', 'location' => __('shipping.container_tracker.demo.events.suez_crossing'), 'status' => __('shipping.container_tracker.demo.events.crossing'), 'icon' => 'fa-water'],
                ['date' => null, 'location' => __('shipping.container_tracker.demo.events.jeddah_port'), 'status' => __('shipping.container_tracker.demo.events.expected_arrival'), 'icon' => 'fa-flag-checkered'],
            ],
        ];

        $this->searchPerformed = true;
    }

    public function render()
    {
        return view('livewire.shipping.container-tracker');
    }
}
