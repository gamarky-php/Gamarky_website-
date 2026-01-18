<?php

namespace App\Livewire\Clearance;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * ClearanceTimeline Component
 * 
 * Purpose: تتبع عملية التخليص الجمركي بالمراحل
 * Features:
 * - Multi-stage clearance process tracking
 * - Stages: initiation → inspection → documentation → release → exit
 * - SLA monitoring with alerts
 * - Real-time notifications
 * - Progress visualization with status colors
 * - Fee breakdown display
 * 
 * Usage: Route to clearance jobs with timeline visualization
 */
class ClearanceTimeline extends Component
{
    use WithPagination;

    public $jobId;
    public $job;
    public $stages = [];
    public $broker;
    
    // ========== Filters ==========
    public $statusFilter = '';
    public $searchTerm = '';
    public $slaFilter = 'all'; // all, on-track, at-risk, overdue
    
    // ========== UI State ==========
    public $showJobDetails = false;
    public $selectedJobId = null;
    
    // ========== Stage Definitions ==========
    protected $stageDefinitions = [
        'initiation' => [
            'name' => 'بدء الإجراءات',
            'icon' => 'clipboard-list',
            'color' => 'blue',
            'description' => 'استلام المستندات وتسجيل الشحنة',
        ],
        'inspection' => [
            'name' => 'الكشف والمعاينة',
            'icon' => 'search',
            'color' => 'yellow',
            'description' => 'فحص البضائع والتحقق من المطابقة',
        ],
        'documentation' => [
            'name' => 'تجهيز المستندات',
            'icon' => 'document-text',
            'color' => 'purple',
            'description' => 'إعداد وتقديم المستندات الجمركية',
        ],
        'release' => [
            'name' => 'الإفراج الجمركي',
            'icon' => 'check-circle',
            'color' => 'green',
            'description' => 'الحصول على موافقة الجمارك',
        ],
        'exit' => [
            'name' => 'خروج البضاعة',
            'icon' => 'truck',
            'color' => 'emerald',
            'description' => 'نقل البضائع من الجمارك',
        ],
    ];

    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'searchTerm' => ['except' => ''],
        'slaFilter' => ['except' => 'all'],
    ];

    public function mount($jobId = null)
    {
        if ($jobId) {
            $this->jobId = $jobId;
            $this->loadJobDetails();
        }
    }

    protected function loadJobDetails()
    {
        $this->job = DB::table('clearance_jobs')
            ->leftJoin('users as client', 'clearance_jobs.client_id', '=', 'client.id')
            ->leftJoin('brokers', 'clearance_jobs.broker_id', '=', 'brokers.id')
            ->select(
                'clearance_jobs.*',
                'client.name as client_name',
                'client.email as client_email',
                'brokers.name as broker_name',
                'brokers.company_name as broker_company'
            )
            ->where('clearance_jobs.id', $this->jobId)
            ->first();

        if (!$this->job) {
            abort(404, 'Clearance job not found');
        }

        // Parse stages JSON
        $this->stages = json_decode($this->job->stages ?? '[]', true) ?: [];
        
        // Initialize stages if empty
        if (empty($this->stages)) {
            $this->stages = $this->initializeStages();
            $this->updateJobStages();
        }
    }

    protected function initializeStages()
    {
        $stages = [];
        foreach (array_keys($this->stageDefinitions) as $stage) {
            $stages[] = [
                'stage' => $stage,
                'status' => $stage === 'initiation' ? 'in_progress' : 'pending',
                'started_at' => $stage === 'initiation' ? now()->toDateTimeString() : null,
                'completed_at' => null,
                'notes' => null,
            ];
        }
        return $stages;
    }

    protected function updateJobStages()
    {
        DB::table('clearance_jobs')
            ->where('id', $this->jobId)
            ->update([
                'stages' => json_encode($this->stages),
                'updated_at' => now(),
            ]);
    }

    public function completeStage($stageIndex)
    {
        if (isset($this->stages[$stageIndex])) {
            $this->stages[$stageIndex]['status'] = 'completed';
            $this->stages[$stageIndex]['completed_at'] = now()->toDateTimeString();
            
            // Auto-start next stage
            if (isset($this->stages[$stageIndex + 1])) {
                $this->stages[$stageIndex + 1]['status'] = 'in_progress';
                $this->stages[$stageIndex + 1]['started_at'] = now()->toDateTimeString();
            }
            
            $this->updateJobStages();
            $this->loadJobDetails();
            
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'تم إكمال المرحلة بنجاح',
            ]);
        }
    }

    public function addStageNote($stageIndex, $note)
    {
        if (isset($this->stages[$stageIndex])) {
            $this->stages[$stageIndex]['notes'] = $note;
            $this->updateJobStages();
        }
    }

    public function getSlaStatusProperty()
    {
        if (!$this->job) return 'unknown';
        
        $expectedDate = \Carbon\Carbon::parse($this->job->expected_clearance_date);
        $today = \Carbon\Carbon::today();
        $daysRemaining = $today->diffInDays($expectedDate, false);
        
        if ($daysRemaining < 0) {
            return 'overdue'; // متأخر
        } elseif ($daysRemaining <= 1) {
            return 'at-risk'; // معرض للخطر
        } else {
            return 'on-track'; // على المسار
        }
    }

    public function getProgressPercentageProperty()
    {
        if (empty($this->stages)) return 0;
        
        $completed = collect($this->stages)->filter(function ($stage) {
            return $stage['status'] === 'completed';
        })->count();
        
        return round(($completed / count($this->stages)) * 100);
    }

    public function getJobsProperty()
    {
        $query = DB::table('clearance_jobs')
            ->leftJoin('users', 'clearance_jobs.client_id', '=', 'users.id')
            ->leftJoin('brokers', 'clearance_jobs.broker_id', '=', 'brokers.id')
            ->select(
                'clearance_jobs.*',
                'users.name as client_name',
                'brokers.name as broker_name'
            );

        // Apply filters
        if (!empty($this->statusFilter)) {
            $query->where('clearance_jobs.status', $this->statusFilter);
        }

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('clearance_jobs.shipment_ref', 'LIKE', '%' . $this->searchTerm . '%')
                  ->orWhere('clearance_jobs.bl_number', 'LIKE', '%' . $this->searchTerm . '%');
            });
        }

        // SLA filter
        if ($this->slaFilter !== 'all') {
            $today = now()->toDateString();
            if ($this->slaFilter === 'overdue') {
                $query->where('clearance_jobs.expected_clearance_date', '<', $today)
                      ->whereNull('clearance_jobs.actual_clearance_date');
            } elseif ($this->slaFilter === 'at-risk') {
                $tomorrow = now()->addDay()->toDateString();
                $query->whereBetween('clearance_jobs.expected_clearance_date', [$today, $tomorrow])
                      ->whereNull('clearance_jobs.actual_clearance_date');
            } elseif ($this->slaFilter === 'on-track') {
                $query->where('clearance_jobs.expected_clearance_date', '>', now()->addDay()->toDateString());
            }
        }

        return $query->orderByDesc('clearance_jobs.created_at')->paginate(15);
    }

    public function viewJobDetails($jobId)
    {
        $this->selectedJobId = $jobId;
        $this->jobId = $jobId;
        $this->loadJobDetails();
        $this->showJobDetails = true;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['statusFilter', 'searchTerm', 'slaFilter'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        if ($this->jobId && $this->job) {
            // Single job timeline view
            return view('livewire.clearance.clearance-timeline', [
                'stageDefinitions' => $this->stageDefinitions,
                'slaStatus' => $this->slaStatus,
                'progressPercentage' => $this->progressPercentage,
            ])->layout('layouts.dashboard');
        } else {
            // Jobs list view
            return view('livewire.clearance.clearance-timeline', [
                'jobs' => $this->jobs,
            ])->layout('layouts.dashboard');
        }
    }
}
