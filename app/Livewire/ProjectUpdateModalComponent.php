<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectUpdateModalComponent extends Component
{
    public string $branch = '';
    public $project;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->branch = $project->custom_branch ?? $project->main_branch;
    }

    public function render()
    {
        return view('livewire.project-update-modal-component');
    }

    public function update()
    {
        $this->validate([
            'branch' => 'required|string',
        ]);

        $this->dispatch('updatedCustomBranch', branch: $this->branch, projectId: $this->project->id);
    }
}
