<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Services\Shipping\QuoteAggregatorService;

class ShippingQuote extends Component
{
    // Core search fields
    public $origin_port = '';
    public $destination_port = '';
    public $loading_date = '';
    public $weight_kg = '';
    public $cbm = '';
    public $cargo_type = 'normal'; // normal, dangerous
    public $service_type = 'FCL'; // FCL, LCL
    public $container_type = '20GP'; // 20GP, 40GP, 40HQ, Reefer
    
    // Dimensions (optional)
    public $length = '';
    public $width = '';
    public $height = '';
    
    // Search state
    public $searchPerformed = false;
    public $quotes = [];
    public $sortBy = 'best_value'; // best_value, price, transit_time
    
    // Comparison
    public $selectedForComparison = [];
    
    // Saved quotes
    public $savedQuotes = [];

    protected $rules = [
        'origin_port' => 'required|string|min:2',
        'destination_port' => 'required|string|min:2',
        'loading_date' => 'required|date|after:today',
        'weight_kg' => 'required|numeric|min:1',
        'cbm' => 'nullable|numeric|min:0.01',
        'cargo_type' => 'required|in:normal,dangerous',
        'service_type' => 'required|in:FCL,LCL',
        'container_type' => 'required|in:20GP,40GP,40HQ,Reefer',
    ];

    protected function messages()
    {
        return [
            'origin_port.required' => __('front.shipping.shipping_quote.validation.origin_port_required'),
            'destination_port.required' => __('front.shipping.shipping_quote.validation.destination_port_required'),
            'loading_date.required' => __('front.shipping.shipping_quote.validation.loading_date_required'),
            'loading_date.after' => __('front.shipping.shipping_quote.validation.loading_date_after'),
            'weight_kg.required' => __('front.shipping.shipping_quote.validation.weight_required'),
            'weight_kg.numeric' => __('front.shipping.shipping_quote.validation.weight_numeric'),
            'cbm.numeric' => __('front.shipping.shipping_quote.validation.cbm_numeric'),
        ];
    }

    public function mount()
    {
        // Load saved quotes from session
        $this->savedQuotes = session('saved_quotes', []);
        
        // Set default loading date (one week ahead)
        $this->loading_date = now()->addWeek()->format('Y-m-d');
    }

    /**
    * Search quotes
     */
    public function searchQuotes()
    {
        $this->validate();

        // Use aggregator service to fetch quotes
        $aggregator = app(QuoteAggregatorService::class);
        
        $searchParams = [
            'origin_port' => $this->origin_port,
            'destination_port' => $this->destination_port,
            'loading_date' => $this->loading_date,
            'weight_kg' => $this->weight_kg,
            'cbm' => $this->cbm,
            'cargo_type' => $this->cargo_type,
            'service_type' => $this->service_type,
            'container_type' => $this->container_type,
        ];

        $this->quotes = $aggregator->aggregateQuotes($searchParams)->all();
        
        // Sort quotes by selected criterion
        $this->sortQuotes();
        
        $this->searchPerformed = true;
    }

    /**
    * Simulate fetching quotes from aggregator service
    * (fallback only)
     */
    private function getQuotesFromAggregator()
    {
        // Fallback only - real service is QuoteAggregatorService
        return [];
    }

    /**
    * Sort quotes
     */
    public function sortQuotes()
    {
        $quotes = collect($this->quotes);

        switch ($this->sortBy) {
            case 'price':
                $quotes = $quotes->sortBy('total_price');
                break;
            case 'transit_time':
                $quotes = $quotes->sortBy('transit_days');
                break;
            case 'best_value':
            default:
                // Calculate best value score (price + transit + rating)
                $quotes = $quotes->sortBy(function ($quote) {
                    $priceScore = $quote['total_price'] / 10; // Price weight
                    $timeScore = $quote['transit_days'] * 5; // Transit weight
                    $ratingScore = (5 - $quote['rating']) * 50; // Rating weight
                    return $priceScore + $timeScore + $ratingScore;
                });
                break;
        }

        $this->quotes = $quotes->values()->all();
    }

    /**
    * Update sorting method
     */
    public function updateSort($sort)
    {
        $this->sortBy = $sort;
        $this->sortQuotes();
    }

    /**
    * Add/remove quote for comparison
     */
    public function toggleComparison($quoteId)
    {
        if (in_array($quoteId, $this->selectedForComparison)) {
            $this->selectedForComparison = array_diff($this->selectedForComparison, [$quoteId]);
        } else {
            if (count($this->selectedForComparison) < 3) {
                $this->selectedForComparison[] = $quoteId;
            } else {
                session()->flash('warning', __('front.shipping.shipping_quote.flash.max_comparison')); 
            }
        }
    }

    /**
    * Save quote
     */
    public function saveQuote($quoteId)
    {
        $quote = collect($this->quotes)->firstWhere('id', $quoteId);
        
        if ($quote && !in_array($quoteId, $this->savedQuotes)) {
            $this->savedQuotes[] = $quoteId;
            session()->put('saved_quotes', $this->savedQuotes);
            session()->flash('success', __('front.shipping.shipping_quote.flash.saved_successfully'));
        }
    }

    /**
    * Select quote (redirect to booking page)
     */
    public function selectQuote($quoteId)
    {
        $quote = collect($this->quotes)->firstWhere('id', $quoteId);
        
        if ($quote) {
            // Save quote details in session
            session()->put('selected_quote', $quote);
            
            // Redirect to booking page
            return redirect()->route('front.shipping.container.book');
        }
    }

    /**
    * Get selected quotes for comparison
     */
    public function getSelectedQuotesProperty()
    {
        return collect($this->quotes)
            ->whereIn('id', $this->selectedForComparison)
            ->values()
            ->all();
    }

    /**
    * Calculate CBM from dimensions
     */
    public function calculateCBM()
    {
        if ($this->length && $this->width && $this->height) {
            $this->cbm = round(($this->length * $this->width * $this->height) / 1000000, 2);
        }
    }

    public function render()
    {
        return view('livewire.shipping-quote')
            ->layout('layouts.app');
    }
}
