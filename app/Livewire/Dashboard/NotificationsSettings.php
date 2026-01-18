<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class NotificationsSettings extends Component
{
    /**
     * Page title for browser tab
     */
    public $pageTitle = 'إعدادات الإشعارات';

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.dashboard.notifications-settings')
            ->layout('layouts.dashboard', [
                'title' => $this->pageTitle
            ]);
    }
}
