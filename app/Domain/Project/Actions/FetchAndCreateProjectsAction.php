<?php

namespace App\Domain\Project\Actions;

use App\Domain\Package\Actions\CreatePackagesFromJsonAndLockFileAction;
use App\Domain\Project\Enum\ProjectSourceEnum;
use App\Support\RepositoryClients\Github\GithubPersonalClient;
use App\Support\RepositoryClients\RepositoryClient;
use App\Support\RepositoryClients\RepositoryClientRegistry;
use Illuminate\Support\Facades\Cache;

class FetchAndCreateProjectsAction
{
    public function __invoke()
    {
        $repositoryClients = (new RepositoryClientRegistry())->get();

        foreach($repositoryClients as $repositoryClient) {
            $this->fetchProjects($repositoryClient);
        }
    }

    private function fetchProjects(RepositoryClient $repositoryClient)
    {
        $repositories = $repositoryClient->getRepositories();

        foreach($repositories as $repository) {
            $cacheKey = "{$repository->source->value}_{$repository->name}_";

            $jsonFile = Cache::remember("{$cacheKey}_json", 1, function() use ($repository, $repositoryClient) {
                return $repositoryClient->getComposerJsonFile($repository->repoSlug, $repository->mainBranch);
            });

            $lockFile = Cache::remember("{$cacheKey}_lock", 1, function() use ($repository, $repositoryClient) {
                return $repositoryClient->getComposerLockFile($repository->repoSlug, $repository->mainBranch);
            });

            // If there is no lock file there is no reason to create any data for this project
            if(!$lockFile) {
                continue;
            }

            $project = app(StoreProjectAction::class)(
                repositoryObject: $repository,
                type: app(GetProjectTypeAction::class)($jsonFile)
            );

            app(CreatePackagesFromJsonAndLockFileAction::class)($project, $jsonFile, $lockFile);

            // todo: remove projects that are not within the list
        }
    }

}
