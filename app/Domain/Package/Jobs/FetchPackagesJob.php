<?php

namespace App\Domain\Package\Jobs;

use App\Domain\Package\Actions\FetchPackagesFromRepositoryAction;
use App\Domain\Package\Exceptions\NoLockFileFoundException;
use App\Models\Project;
use App\Support\RepositoryClients\Contracts\RepositoryClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchPackagesJob implements ShouldQueue
{
    use Queueable;

    protected $retries = 3;

    public function __construct(
        public Project $project,
        public RepositoryClient $repositoryClient,
    )
    {
    }

    public function handle(): void
    {
        try {
            app(FetchPackagesFromRepositoryAction::class)($this->project, $this->repositoryClient);
        } catch (NoLockFileFoundException $e) {
            // Delete projects that dont have any composer references since we cannot use them
            $this->project->delete();
        }
    }
}
