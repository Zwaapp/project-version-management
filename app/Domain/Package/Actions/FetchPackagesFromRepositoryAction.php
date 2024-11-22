<?php

namespace App\Domain\Package\Actions;

use App\Domain\Package\Exceptions\NoLockFileFoundException;
use App\Domain\Project\Actions\GetProjectTypeAction;
use App\Models\Project;
use App\Support\RepositoryClients\Contracts\RepositoryClient;
use Illuminate\Support\Facades\Cache;

class FetchPackagesFromRepositoryAction
{
    public function __invoke(Project $project, RepositoryClient $repositoryClient)
    {
        $cacheKey = "{$project->source->value}_{$project->name}_";

        $jsonFile = Cache::remember("{$cacheKey}_json", 1, function() use ($project, $repositoryClient) {
            return $repositoryClient->getComposerJsonFile($project->repository_slug, $project->main_branch);
        });

        $lockFile = Cache::remember("{$cacheKey}_lock", 1, function() use ($project, $repositoryClient) {
            return $repositoryClient->getComposerLockFile($project->repository_slug, $project->main_branch);
        });

        // If there is no lock file there is no reason to create any data for this project
        if(!$lockFile) {
            throw new NoLockFileFoundException("No lock file found for {$project->name}");
        }

        app(CreatePackagesFromJsonAndLockFileAction::class)($project, $jsonFile, $lockFile);

        $project->update(['type' => app(GetProjectTypeAction::class)($jsonFile)]);
    }

}
