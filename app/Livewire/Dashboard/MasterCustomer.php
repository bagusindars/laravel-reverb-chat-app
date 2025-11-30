<?php

namespace App\Livewire\Dashboard;

use App\Models\Agent;
use Livewire\Component;

class MasterCustomer extends Component
{
    public $agent = [];

    public function mount()
    {
        $this->agent = Agent::orderBy('created_at')->get();
    }

    public function render()
    {
        return view('livewire.dashboard.master-customer')->layout('components.layouts.dashboard')->layoutData([
            'title' => 'Master Customer'
        ]);
    }
}
