<?php

namespace App\Livewire\Shipping;

use Livewire\Component;
use App\Services\Pricing\ShippingAggregator;

class TruckQuoteForm extends Component
{
    public $origin_city = '';
    public $destination_city = '';
    public $pickup_date = '';
    public $weight_kg = '';
    public $truck_type = 'flatbed';
    
    public $quotes = [];
    public $searchPerformed = false;

    public function mount()
    {
        $this->pickup_date = now()->addDays(3)->format('Y-m-d');
    }

    protected $rules = [
        'origin_city' => 'required|string|min:2',
        'destination_city' => 'required|string|min:2',
        'pickup_date' => 'required|date|after:today',
        'weight_kg' => 'required|numeric|min:100',
    ];

    public function searchQuotes()
    {
        $this->validate();

        $aggregator = app(ShippingAggregator::class);
        
        $this->quotes = $aggregator->getTruckQuotes([
            'origin_city' => $this->origin_city,
            'destination_city' => $this->destination_city,
            'pickup_date' => $this->pickup_date,
            'weight_kg' => $this->weight_kg,
            'truck_type' => $this->truck_type,
        ]);

        $this->searchPerformed = true;
    }

    public function render()
    {
        return view('livewire.shipping.truck-quote-form');
    }
}
