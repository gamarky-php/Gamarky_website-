<?php

namespace App\Livewire\Agency;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AgencyDashboard extends Component
{
    use WithPagination;

    // Tab Selection
    public $activeTab = 'shipping'; // shipping | brand

    // Filters for Shipping Agents
    public $shippingRegion = '';
    public $shippingStatus = '';
    public $shippingMinRevenue = 0;
    
    // Filters for Brand Agents
    public $brandSector = '';
    public $brandDecision = '';
    public $brandMinScore = 0;

    // Modals
    public $showShippingAgentModal = false;
    public $showBrandAgentModal = false;
    public $selectedShippingAgent = null;
    public $selectedBrandAgent = null;

    protected $queryString = ['activeTab'];

    public function mount()
    {
        // Initialize with shipping tab
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function viewShippingAgent($userId)
    {
        $this->selectedShippingAgent = DB::table('users')
            ->where('id', $userId)
            ->where('activity_type', 'agent')
            ->first();
        $this->showShippingAgentModal = true;
    }

    public function viewBrandAgent($requestId)
    {
        $this->selectedBrandAgent = DB::table('brand_agency_requests')
            ->where('id', $requestId)
            ->first();
        $this->showBrandAgentModal = true;
    }

    public function closeModals()
    {
        $this->showShippingAgentModal = false;
        $this->showBrandAgentModal = false;
        $this->selectedShippingAgent = null;
        $this->selectedBrandAgent = null;
    }

    public function getShippingAgentsProperty()
    {
        $query = DB::table('users')
            ->select(
                'id',
                'name',
                'email',
                'phone',
                'country',
                'business_sector',
                'created_at',
                DB::raw('(SELECT COUNT(*) FROM container_quotes WHERE container_quotes.user_id = users.id AND status = "active") as active_quotes'),
                DB::raw('(SELECT COALESCE(SUM(price), 0) FROM container_bookings WHERE container_bookings.user_id = users.id AND status IN ("delivered", "completed")) as total_revenue')
            )
            ->where('activity_type', 'agent')
            ->whereNotNull('email_verified_at');

        if ($this->shippingRegion) {
            $query->where('country', $this->shippingRegion);
        }

        if ($this->shippingMinRevenue > 0) {
            $query->havingRaw('total_revenue >= ?', [$this->shippingMinRevenue]);
        }

        return $query->orderBy('total_revenue', 'desc')
            ->paginate(15);
    }

    public function getBrandAgentsProperty()
    {
        $query = DB::table('brand_agency_requests')
            ->select('*');

        if ($this->brandSector) {
            $query->where('sector', $this->brandSector);
        }

        if ($this->brandDecision) {
            $query->where('decision', $this->brandDecision);
        }

        if ($this->brandMinScore > 0) {
            $query->where('score_total', '>=', $this->brandMinScore);
        }

        return $query->orderBy('score_total', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function getShippingKpisProperty()
    {
        return [
            'total_agents' => DB::table('users')
                ->where('activity_type', 'agent')
                ->whereNotNull('email_verified_at')
                ->count(),
            
            'active_this_month' => DB::table('users')
                ->where('activity_type', 'agent')
                ->whereNotNull('email_verified_at')
                ->where('updated_at', '>=', now()->subMonth())
                ->count(),
            
            'total_bookings' => DB::table('container_bookings')
                ->whereIn('user_id', function($query) {
                    $query->select('id')
                        ->from('users')
                        ->where('activity_type', 'agent');
                })
                ->count(),
            
            'total_revenue' => DB::table('container_bookings')
                ->whereIn('user_id', function($query) {
                    $query->select('id')
                        ->from('users')
                        ->where('activity_type', 'agent');
                })
                ->whereIn('status', ['delivered', 'completed'])
                ->sum('total_price') ?? 0,
        ];
    }

    public function getBrandKpisProperty()
    {
        return [
            'total_requests' => DB::table('brand_agency_requests')->count(),
            
            'pending_review' => DB::table('brand_agency_requests')
                ->where('decision', 'pending')
                ->count(),
            
            'accepted_agents' => DB::table('brand_agency_requests')
                ->where('decision', 'accepted')
                ->count(),
            
            'avg_score' => round(
                DB::table('brand_agency_requests')
                    ->where('decision', '!=', 'pending')
                    ->avg('score_total') ?? 0,
                1
            ),
        ];
    }

    public function getSectorsProperty()
    {
        return DB::table('brand_agency_requests')
            ->select('sector')
            ->distinct()
            ->pluck('sector')
            ->filter()
            ->values()
            ->toArray();
    }

    public function getRegionsProperty()
    {
        return DB::table('users')
            ->select('country')
            ->where('activity_type', 'agent')
            ->distinct()
            ->pluck('country')
            ->filter()
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.agency.agency-dashboard', [
            'shippingAgents' => $this->shippingAgents,
            'brandAgents' => $this->brandAgents,
            'shippingKpis' => $this->shippingKpis,
            'brandKpis' => $this->brandKpis,
            'sectors' => $this->sectors,
            'regions' => $this->regions,
        ]);
    }
}
