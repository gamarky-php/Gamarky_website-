<?php

namespace App\Livewire\Clearance;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * BrokerProfile Component
 * 
 * Purpose: عرض ملف كامل للمستخلص الجمركي
 * Features:
 * - Broker details (contact, experience, certifications)
 * - Document management (licenses, insurance, etc.)
 * - Composite ratings (speed, accuracy, communication, cost)
 * - Client reviews with verification status
 * - Recent clearance jobs performance
 * 
 * Usage: Route::get('/clearance/broker/{brokerId}', BrokerProfile::class)
 */
class BrokerProfile extends Component
{
    public $brokerId;
    public $broker;
    public $documents = [];
    public $reviews = [];
    public $recentJobs = [];
    public $compositeRatings = [];
    
    // ========== UI State ==========
    public $activeTab = 'overview'; // overview, documents, reviews, performance
    public $showContactModal = false;
    public $showReviewModal = false;
    
    // ========== Review Form ==========
    public $reviewRating = 5;
    public $reviewComment = '';
    public $criteriaScores = [
        'speed' => 5,
        'accuracy' => 5,
        'communication' => 5,
        'cost' => 5,
    ];

    public function mount($brokerId)
    {
        $this->brokerId = $brokerId;
        $this->loadBrokerData();
        $this->loadDocuments();
        $this->loadReviews();
        $this->loadRecentJobs();
        $this->calculateCompositeRatings();
    }

    protected function loadBrokerData()
    {
        $this->broker = DB::table('brokers')->find($this->brokerId);
        
        if (!$this->broker) {
            abort(404, 'Broker not found');
        }
    }

    protected function loadDocuments()
    {
        $this->documents = DB::table('broker_documents')
            ->where('broker_id', $this->brokerId)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('type')
            ->toArray();
    }

    protected function loadReviews()
    {
        $this->reviews = DB::table('broker_reviews')
            ->leftJoin('users', 'broker_reviews.reviewer_id', '=', 'users.id')
            ->select(
                'broker_reviews.*',
                'users.name as reviewer_name',
                'users.company_name as reviewer_company'
            )
            ->where('broker_reviews.broker_id', $this->brokerId)
            ->where('broker_reviews.status', 'approved')
            ->orderByDesc('broker_reviews.created_at')
            ->limit(20)
            ->get()
            ->toArray();
    }

    protected function loadRecentJobs()
    {
        $this->recentJobs = DB::table('clearance_jobs')
            ->leftJoin('users', 'clearance_jobs.client_id', '=', 'users.id')
            ->select(
                'clearance_jobs.*',
                'users.name as client_name'
            )
            ->where('clearance_jobs.broker_id', $this->brokerId)
            ->orderByDesc('clearance_jobs.created_at')
            ->limit(10)
            ->get()
            ->toArray();
    }

    protected function calculateCompositeRatings()
    {
        // Calculate average scores from reviews
        $reviewsWithCriteria = DB::table('broker_reviews')
            ->where('broker_id', $this->brokerId)
            ->where('status', 'approved')
            ->whereNotNull('criteria_scores')
            ->get();

        $criteriaAverages = [
            'speed' => 0,
            'accuracy' => 0,
            'communication' => 0,
            'cost' => 0,
        ];

        $counts = [
            'speed' => 0,
            'accuracy' => 0,
            'communication' => 0,
            'cost' => 0,
        ];

        foreach ($reviewsWithCriteria as $review) {
            $scores = json_decode($review->criteria_scores, true);
            if (is_array($scores)) {
                foreach ($scores as $criterion => $score) {
                    if (isset($criteriaAverages[$criterion])) {
                        $criteriaAverages[$criterion] += $score;
                        $counts[$criterion]++;
                    }
                }
            }
        }

        foreach ($criteriaAverages as $criterion => $total) {
            $criteriaAverages[$criterion] = $counts[$criterion] > 0 
                ? round($total / $counts[$criterion], 2) 
                : 0;
        }

        $this->compositeRatings = $criteriaAverages;
    }

    public function submitReview()
    {
        $this->validate([
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewComment' => 'required|string|min:10|max:1000',
        ]);

        DB::table('broker_reviews')->insert([
            'broker_id' => $this->brokerId,
            'reviewer_id' => auth()->id(),
            'source' => 'site',
            'rating' => $this->reviewRating,
            'comments' => $this->reviewComment,
            'criteria_scores' => json_encode($this->criteriaScores),
            'is_verified' => false,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->reset(['reviewRating', 'reviewComment', 'criteriaScores', 'showReviewModal']);
        $this->loadReviews();
        $this->calculateCompositeRatings();

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'تم إرسال تقييمك بنجاح. سيتم مراجعته قريبًا.',
        ]);
    }

    public function getStatsProperty()
    {
        $totalReviews = DB::table('broker_reviews')
            ->where('broker_id', $this->brokerId)
            ->where('status', 'approved')
            ->count();

        $totalJobs = DB::table('clearance_jobs')
            ->where('broker_id', $this->brokerId)
            ->count();

        $completedJobs = DB::table('clearance_jobs')
            ->where('broker_id', $this->brokerId)
            ->whereIn('status', ['cleared', 'released'])
            ->count();

        $avgClearanceTime = DB::table('clearance_jobs')
            ->where('broker_id', $this->brokerId)
            ->whereNotNull('actual_clearance_date')
            ->whereNotNull('created_at')
            ->selectRaw('AVG(DATEDIFF(actual_clearance_date, created_at)) as avg_days')
            ->value('avg_days');

        $onTimeClearances = DB::table('clearance_jobs')
            ->where('broker_id', $this->brokerId)
            ->whereNotNull('actual_clearance_date')
            ->whereRaw('actual_clearance_date <= expected_clearance_date')
            ->count();

        $onTimeRate = $completedJobs > 0 
            ? round(($onTimeClearances / $completedJobs) * 100, 1) 
            : 0;

        return [
            'total_reviews' => $totalReviews,
            'total_jobs' => $totalJobs,
            'completed_jobs' => $completedJobs,
            'avg_clearance_days' => $avgClearanceTime ? round($avgClearanceTime, 1) : 0,
            'on_time_rate' => $onTimeRate,
        ];
    }

    public function render()
    {
        return view('livewire.clearance.broker-profile', [
            'stats' => $this->stats,
        ])->layout('layouts.dashboard');
    }
}
