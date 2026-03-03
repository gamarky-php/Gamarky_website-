<?php

namespace App\Livewire\Agent\Shipping;

use App\Models\Agent;
use Livewire\Component;

class Search extends Component
{
    public string $search = '';

    public function getAgentsProperty()
    {
        return Agent::query()
            ->search($this->search)
            ->orderByDesc('rating_auto')
            ->orderBy('company_name')
            ->limit(12)
            ->get();
    }

    public function render()
    {
        return view('livewire.agent.shipping.search', [
            'agents' => $this->agents,
        ]);
    }
}
