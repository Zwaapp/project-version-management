<?php

namespace App\Livewire;

use App\Domain\Package\Actions\FetchPackagesFromRepositoryAction;
use App\Domain\Package\Exceptions\NoLockFileFoundException;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectListComponent extends Component
{
    public $projects = [];

    public string $search = '';

    public string $error = '';
    public string $success = '';

    public function mount()
    {
        $this->fetchProjects();
    }

    public function render()
    {
        return view('livewire.project-list-component');
    }

    public function fetchProjects()
    {
        $this->projects = Project::search($this->search)->orderBy('name')->get();
    }

    #[On('searchUpdated')]
    public function search(string $search)
    {
        $this->search = $search;

        $this->fetchProjects();
    }

    public function updatePackages(int $projectId)
    {
        $project = Project::find($projectId);

        app(FetchPackagesFromRepositoryAction::class)(
            project: $project,
            repositoryClient: app($project->repository_client),
            noCache: true
        );

        $this->fetchProjects();

        if(!$this->success) {
            $this->success = "The packages have been updated successfully.";
        }
    }

    #[On('updatedCustomBranch')]
    public function updateCustomBranch(string $branch, int $projectId)
    {
        $project = Project::find($projectId);

        DB::beginTransaction();
        try {
            $project->update(['custom_branch' => $branch]);

            // Refreshing the project packages
            $this->updatePackages($projectId);

            DB::commit();
            $this->success = "The branch has been updated to {$branch}.";
        } catch (NoLockFileFoundException $e) {
            DB::rollBack();
            $this->error = __("The project {$project->name} does not contain a lock file within the branch {$branch}");
        }
    }

    public function closeError()
    {
        $this->error = '';
    }

    public function closeSuccess()
    {
        $this->success = '';
    }
}
