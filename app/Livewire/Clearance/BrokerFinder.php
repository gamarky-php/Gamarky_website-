<?php

namespace App\Livewire\Clearance;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * BrokerFinder Component
 * 
 * Purpose: بحث متقدم عن المستخلصين الجمركيين
 * Features:
 * - Multi-criteria search: country, port, activity, experience, score
 * - Real-time filtering with Livewire
 * - Broker profile cards with ratings and certifications
 * - Pagination support
 * 
 * Usage: Route to this component in clearance section
 */
class BrokerFinder extends Component
{
    use WithPagination;

    // ========== Search Filters ==========
    public $searchTerm = '';
    public $countryFilter = '';
    public $portFilter = '';
    public $activityFilter = '';
    public $minExperience = 0;
    public $minScore = 0;
    public $statusFilter = 'active';
    
    // ========== UI State ==========
    public $sortBy = 'score'; // score, experience, name
    public $sortDirection = 'desc';
    public $showFilters = true;
    
    // ========== Data Collections ==========
    public $countries = [];
    public $ports = [];
    public $activities = [
        'customs' => 'التخليص الجمركي',
        'freight' => 'الشحن والنقل',
        'warehousing' => 'التخزين',
        'inspection' => 'الفحص والمعاينة',
        'consulting' => 'الاستشارات الجمركية',
    ];
    
    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'countryFilter' => ['except' => ''],
        'portFilter' => ['except' => ''],
        'activityFilter' => ['except' => ''],
        'sortBy' => ['except' => 'score'],
    ];

    public function mount()
    {
        // Load available countries from brokers table
        $this->countries = DB::table('brokers')
            ->select('country')
            ->distinct()
            ->where('status', 'active')
            ->orderBy('country')
            ->pluck('country')
            ->toArray();

        // Load available ports (unique from JSON arrays)
        $portData = DB::table('brokers')
            ->whereNotNull('ports')
            ->where('status', 'active')
            ->pluck('ports');
        
        $uniquePorts = [];
        foreach ($portData as $json) {
            $decoded = json_decode($json, true);
            if (is_array($decoded)) {
                $uniquePorts = array_merge($uniquePorts, $decoded);
            }
        }
        $this->ports = array_unique($uniquePorts);
        sort($this->ports);
    }

    public function resetFilters()
    {
        $this->reset([
            'searchTerm',
            'countryFilter',
            'portFilter',
            'activityFilter',
            'minExperience',
            'minScore',
            'sortBy',
            'sortDirection'
        ]);
        $this->resetPage();
    }

    public function toggleSort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, [
            'searchTerm', 'countryFilter', 'portFilter', 'activityFilter', 
            'minExperience', 'minScore', 'statusFilter'
        ])) {
            $this->resetPage();
        }
    }

    public function getBrokersProperty()
    {
        $query = DB::table('brokers')
            ->select([
                'id',
                'name',
                'company_name',
                'country',
                'ports',
                'activities',
                'experience_years',
                'score',
                'certifications',
                'email',
                'phone',
                'website',
                'status',
            ])
            ->where('status', $this->statusFilter);

        // Search term filter
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                  ->orWhere('company_name', 'LIKE', '%' . $this->searchTerm . '%');
            });
        }

        // Country filter
        if (!empty($this->countryFilter)) {
            $query->where('country', $this->countryFilter);
        }

        // Port filter (search within JSON array)
        if (!empty($this->portFilter)) {
            $query->whereJsonContains('ports', $this->portFilter);
        }

        // Activity filter (search within JSON array)
        if (!empty($this->activityFilter)) {
            $query->whereJsonContains('activities', $this->activityFilter);
        }

        // Experience filter
        if ($this->minExperience > 0) {
            $query->where('experience_years', '>=', $this->minExperience);
        }

        // Score filter
        if ($this->minScore > 0) {
            $query->where('score', '>=', $this->minScore);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        // Add secondary sort by score if not primary
        if ($this->sortBy !== 'score') {
            $query->orderBy('score', 'desc');
        }

        return $query->paginate(12);
    }

    public function getStatsProperty()
    {
        $totalBrokers = DB::table('brokers')->where('status', 'active')->count();
        $avgScore = DB::table('brokers')->where('status', 'active')->avg('score');
        $topCountries = DB::table('brokers')
            ->select('country', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(3)
            ->get();
        $recentReviews = DB::table('broker_reviews')
            ->where('status', 'approved')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return [
            'total_brokers' => $totalBrokers,
            'avg_score' => round($avgScore, 2),
            'top_countries' => $topCountries,
            'recent_reviews' => $recentReviews,
        ];
    }

    public function render()
    {
        return view('livewire.clearance.broker-finder', [
            'brokers' => $this->brokers,
            'stats' => $this->stats,
        ])->layout('layouts.dashboard');
    }
}
