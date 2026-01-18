<?php

namespace App\Livewire\Containers;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * ContainerQuotesBoard Component
 * 
 * Purpose: بورصة عروض الحاويات
 * Features:
 * - Display available container quotes from multiple carriers
 * - Filter by route, container type, price range
 * - Sort by price, transit time, validity
 * - Request new quote form
 * - Accept/decline quotes
 * - Quote expiry tracking (TTL)
 * 
 * Usage: Container quotes marketplace
 */
class ContainerQuotesBoard extends Component
{
    use WithPagination;

    // ========== Filters ==========
    public $originFilter = '';
    public $destinationFilter = '';
    public $containerTypeFilter = '';
    public $carrierFilter = '';
    public $maxPrice = 10000;
    public $showExpired = false;
    
    // ========== UI State ==========
    public $showRequestModal = false;
    public $showQuoteDetails = false;
    public $selectedQuoteId = null;
    public $sortBy = 'price'; // price, transit_days, valid_until
    public $sortDirection = 'asc';
    
    // ========== Request Quote Form ==========
    public $request_origin = '';
    public $request_destination = '';
    public $request_container_type = '20ft';
    public $request_notes = '';
    
    // ========== Data Collections ==========
    public $containerTypes = [
        '20ft' => 'حاوية 20 قدم (عادية)',
        '40ft' => 'حاوية 40 قدم (عادية)',
        '40hc' => 'حاوية 40 قدم (عالية)',
        '45hc' => 'حاوية 45 قدم (عالية)',
        'reefer_20' => 'حاوية 20 قدم (مبردة)',
        'reefer_40' => 'حاوية 40 قدم (مبردة)',
    ];

    protected $queryString = [
        'originFilter' => ['except' => ''],
        'destinationFilter' => ['except' => ''],
        'containerTypeFilter' => ['except' => ''],
        'sortBy' => ['except' => 'price'],
    ];

    protected $rules = [
        'request_origin' => 'required|string|max:100',
        'request_destination' => 'required|string|max:100',
        'request_container_type' => 'required|in:20ft,40ft,40hc,45hc,reefer_20,reefer_40',
        'request_notes' => 'nullable|string|max:500',
    ];

    public function requestQuote()
    {
        $this->validate();

        $requestRef = 'CQR-' . strtoupper(substr(md5(time() . auth()->id()), 0, 8));

        DB::table('container_quotes')->insert([
            'request_ref' => $requestRef,
            'requester_id' => auth()->id(),
            'origin_port' => $this->request_origin,
            'destination_port' => $this->request_destination,
            'container_type' => $this->request_container_type,
            'price' => 0, // Will be updated by carrier
            'currency' => 'USD',
            'valid_until' => now()->addDays(7),
            'status' => 'active',
            'notes' => $this->request_notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->reset(['request_origin', 'request_destination', 'request_container_type', 'request_notes', 'showRequestModal']);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'تم إرسال طلب العرض بنجاح. رقم المرجع: ' . $requestRef,
        ]);
    }

    public function acceptQuote($quoteId)
    {
        DB::table('container_quotes')
            ->where('id', $quoteId)
            ->update([
                'status' => 'accepted',
                'updated_at' => now(),
            ]);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'تم قبول العرض. يمكنك الآن إكمال الحجز.',
        ]);

        // Redirect to booking wizard
        return redirect()->route('dashboard.containers.booking', ['quoteId' => $quoteId]);
    }

    public function declineQuote($quoteId)
    {
        DB::table('container_quotes')
            ->where('id', $quoteId)
            ->update([
                'status' => 'declined',
                'updated_at' => now(),
            ]);

        $this->dispatch('alert', [
            'type' => 'info',
            'message' => 'تم رفض العرض.',
        ]);
    }

    public function viewQuoteDetails($quoteId)
    {
        $this->selectedQuoteId = $quoteId;
        $this->showQuoteDetails = true;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, [
            'originFilter', 'destinationFilter', 'containerTypeFilter', 
            'carrierFilter', 'maxPrice', 'showExpired'
        ])) {
            $this->resetPage();
        }
    }

    public function getQuotesProperty()
    {
        $query = DB::table('container_quotes')
            ->leftJoin('users', 'container_quotes.requester_id', '=', 'users.id')
            ->select(
                'container_quotes.*',
                'users.name as requester_name',
                'users.company_name as requester_company'
            );

        // Status filter
        if ($this->showExpired) {
            $query->whereIn('container_quotes.status', ['active', 'expired']);
        } else {
            $query->where('container_quotes.status', 'active')
                  ->where('container_quotes.valid_until', '>=', now());
        }

        // Origin filter
        if (!empty($this->originFilter)) {
            $query->where('container_quotes.origin_port', 'LIKE', '%' . $this->originFilter . '%');
        }

        // Destination filter
        if (!empty($this->destinationFilter)) {
            $query->where('container_quotes.destination_port', 'LIKE', '%' . $this->destinationFilter . '%');
        }

        // Container type filter
        if (!empty($this->containerTypeFilter)) {
            $query->where('container_quotes.container_type', $this->containerTypeFilter);
        }

        // Carrier filter
        if (!empty($this->carrierFilter)) {
            $query->where('container_quotes.carrier', 'LIKE', '%' . $this->carrierFilter . '%');
        }

        // Price filter
        $query->where('container_quotes.price', '<=', $this->maxPrice);

        // Sorting
        $query->orderBy('container_quotes.' . $this->sortBy, $this->sortDirection);

        return $query->paginate(20);
    }

    public function getStatsProperty()
    {
        $totalQuotes = DB::table('container_quotes')
            ->where('status', 'active')
            ->where('valid_until', '>=', now())
            ->count();

        $avgPrice = DB::table('container_quotes')
            ->where('status', 'active')
            ->where('valid_until', '>=', now())
            ->avg('price');

        $carriers = DB::table('container_quotes')
            ->where('status', 'active')
            ->whereNotNull('carrier')
            ->distinct('carrier')
            ->count('carrier');

        $expiringToday = DB::table('container_quotes')
            ->where('status', 'active')
            ->whereDate('valid_until', today())
            ->count();

        return [
            'total_quotes' => $totalQuotes,
            'avg_price' => round($avgPrice, 2),
            'carriers' => $carriers,
            'expiring_today' => $expiringToday,
        ];
    }

    public function getSelectedQuoteProperty()
    {
        if (!$this->selectedQuoteId) return null;

        return DB::table('container_quotes')
            ->where('id', $this->selectedQuoteId)
            ->first();
    }

    public function render()
    {
        return view('livewire.containers.container-quotes-board', [
            'quotes' => $this->quotes,
            'stats' => $this->stats,
            'selectedQuote' => $this->selectedQuote,
        ])->layout('layouts.dashboard');
    }
}
