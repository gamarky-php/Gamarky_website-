<?php

namespace App\Livewire\Shared;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AdsManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingAd = null;

    // Form Fields
    public $title = '';
    public $description = '';
    public $image = null;
    public $existing_image = '';
    public $link_url = '';
    public $location = 'sidebar'; // sidebar | top | bottom | popup
    public $status = 'active'; // active | inactive | scheduled
    public $start_date = '';
    public $end_date = '';
    public $target_audience = 'all'; // all | import | export | manufacturing | etc.
    public $priority = 5;

    // Filters
    public $filterStatus = '';
    public $filterLocation = '';

    protected $rules = [
        'title' => 'required|min:3|max:100',
        'description' => 'nullable|max:500',
        'link_url' => 'nullable|url',
        'location' => 'required|in:sidebar,top,bottom,popup',
        'status' => 'required|in:active,inactive,scheduled',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after:start_date',
        'target_audience' => 'required',
        'priority' => 'required|integer|min:1|max:10',
    ];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($adId)
    {
        $ad = DB::table('dashboard_ads')->where('id', $adId)->first();
        
        if ($ad) {
            $this->editingAd = $ad;
            $this->title = $ad->title;
            $this->description = $ad->description;
            $this->existing_image = $ad->image_path;
            $this->link_url = $ad->link_url ?? '';
            $this->location = $ad->location;
            $this->status = $ad->status;
            $this->start_date = $ad->start_date;
            $this->end_date = $ad->end_date;
            $this->target_audience = $ad->target_audience ?? 'all';
            $this->priority = $ad->priority ?? 5;
            
            $this->showEditModal = true;
        }
    }

    public function createAd()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('dashboard-ads', 'public');
        }

        DB::table('dashboard_ads')->insert([
            'title' => $this->title,
            'description' => $this->description,
            'image_path' => $imagePath,
            'link_url' => $this->link_url,
            'location' => $this->location,
            'status' => $this->status,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'target_audience' => $this->target_audience,
            'priority' => $this->priority,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->closeModals();
        session()->flash('success', 'تم إنشاء الإعلان بنجاح');
    }

    public function updateAd()
    {
        $this->validate();

        $imagePath = $this->existing_image;
        if ($this->image) {
            if ($this->existing_image) {
                Storage::disk('public')->delete($this->existing_image);
            }
            $imagePath = $this->image->store('dashboard-ads', 'public');
        }

        DB::table('dashboard_ads')
            ->where('id', $this->editingAd->id)
            ->update([
                'title' => $this->title,
                'description' => $this->description,
                'image_path' => $imagePath,
                'link_url' => $this->link_url,
                'location' => $this->location,
                'status' => $this->status,
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'target_audience' => $this->target_audience,
                'priority' => $this->priority,
                'updated_at' => now(),
            ]);

        $this->closeModals();
        session()->flash('success', 'تم تحديث الإعلان بنجاح');
    }

    public function deleteAd($adId)
    {
        $ad = DB::table('dashboard_ads')->where('id', $adId)->first();
        
        if ($ad && $ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }

        DB::table('dashboard_ads')->where('id', $adId)->delete();
        
        session()->flash('success', 'تم حذف الإعلان بنجاح');
    }

    public function toggleStatus($adId)
    {
        $ad = DB::table('dashboard_ads')->where('id', $adId)->first();
        
        if ($ad) {
            $newStatus = $ad->status === 'active' ? 'inactive' : 'active';
            DB::table('dashboard_ads')->where('id', $adId)->update(['status' => $newStatus]);
        }
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->image = null;
        $this->existing_image = '';
        $this->link_url = '';
        $this->location = 'sidebar';
        $this->status = 'active';
        $this->start_date = '';
        $this->end_date = '';
        $this->target_audience = 'all';
        $this->priority = 5;
        $this->editingAd = null;
    }

    public function getAdsProperty()
    {
        $query = DB::table('dashboard_ads')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc');

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterLocation) {
            $query->where('location', $this->filterLocation);
        }

        return $query->paginate(15);
    }

    public function render()
    {
        return view('livewire.shared.ads-manager', [
            'ads' => $this->ads,
        ]);
    }
}
