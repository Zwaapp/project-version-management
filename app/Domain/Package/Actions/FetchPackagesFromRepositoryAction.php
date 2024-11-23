<?php

namespace App\Domain\Package\Actions;

use App\Domain\Package\Exceptions\NoLockFileFoundException;
use App\Domain\Project\Actions\GetProjectTypeAction;
use App\Models\Project;
use App\Support\RepositoryClients\Contracts\RepositoryClient;
use Illuminate\Support\Facades\Cache;

class FetchPackagesFromRepositoryAction
{
    public function __invoke(Project $project, RepositoryClient $repositoryClient, bool $noCache = false)
    {
        $branch = $project->custom_branch ?? $project->main_branch;
        $cacheKey = "{$project->source->value}_{$project->name}_{$branch}";

        if($noCache) {
            Cache::forget("{$cacheKey}_json");
            Cache::forget("{$cacheKey}_lock");
        }

        $jsonFile = Cache::remember("{$cacheKey}_json", 60, function() use ($project, $repositoryClient, $branch) {
            return $repositoryClient->getComposerJsonFile($project->repository_slug, $branch);
        });

        $lockFile = Cache::remember("{$cacheKey}_lock", 60, function() use ($project, $repositoryClient, $branch) {
            return $repositoryClient->getComposerLockFile($project->repository_slug, $branch);
        });

        // If there is no lock file there is no reason to create any data for this project
        if(!$lockFile) {
            throw new NoLockFileFoundException("No lock file found for {$project->name} and branch {$branch}");
        }

        app(CreatePackagesFromJsonAndLockFileAction::class)($project, $jsonFile, $lockFile);

        $project->update(['type' => app(GetProjectTypeAction::class)($jsonFile)]);
    }
}
