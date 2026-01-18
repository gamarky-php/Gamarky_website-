<?php

namespace App\Livewire\Shipping;

use Livewire\Component;
use App\Services\Pricing\ShippingAggregator;

class ContainerQuoteForm extends Component
{
    public $origin_port = '';
    public $destination_port = '';
    public $loading_date = '';
    public $weight_kg = '';
    public $cbm = '';
    public $cargo_type = 'general';
    public $service_type = 'FCL';
    public $container_type = '40GP';
    
    public $quotes = [];
    public $searchPerformed = false;

    public function mount()
    {
        $this->loading_date = now()->addWeek()->format('Y-m-d');
    }

    protected $rules = [
        'origin_port' => 'required|string|min:2',
        'destination_port' => 'required|string|min:2',
        'loading_date' => 'required|date|after:today',
        'weight_kg' => 'required|numeric|min:1',
        'cbm' => 'nullable|numeric|min:0.1',
    ];

    public function searchQuotes()
    {
        $this->validate();

        $aggregator = app(ShippingAggregator::class);
        
        $this->quotes = $aggregator->getContainerQuotes([
            'origin_port' => $this->origin_port,
            'destination_port' => $this->destination_port,
            'loading_date' => $this->loading_date,
            'weight_kg' => $this->weight_kg,
            'cbm' => $this->cbm,
            'cargo_type' => $this->cargo_type,
            'service_type' => $this->service_type,
            'container_type' => $this->container_type,
        ]);

        $this->searchPerformed = true;
    }

    public function render()
    {
        return view('livewire.shipping.container-quote-form');
    }
}
