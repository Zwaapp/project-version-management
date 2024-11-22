<?php

namespace App\Domain\Project\Actions;

use App\Models\Project;
use App\Support\RepositoryClients\Contracts\RepositoryClient;
use App\Support\RepositoryClients\Objects\RepositoryObject;

class StoreProjectAction
{
    public function __invoke(RepositoryObject $repositoryObject, RepositoryClient $repositoryClient, ?string $type = null): Project
    {
        return Project::updateOrCreate([
            'name' => $repositoryObject->name,
            'url' => $repositoryObject->url,
            'source' => $repositoryObject->source->value,
        ], [
            'name' => $repositoryObject->name,
            'url' => $repositoryObject->url,
            'source' => $repositoryObject->source->value,
            'main_branch' => $repositoryObject->mainBranch,
            'repository_slug' => $repositoryObject->repoSlug,
            'repository_client' => get_class($repositoryClient),
            'type' => null,
        ]);
    }
}
