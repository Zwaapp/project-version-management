<?php

namespace App\Domain\Project\Actions;

use App\Domain\Project\Enum\ProjectSourceEnum;
use App\Models\Project;
use App\Support\RepositoryClients\Objects\RepositoryObject;
use App\Support\RepositoryClients\RepositoryClient;

class StoreProjectAction
{
    public function __invoke(RepositoryObject $repositoryObject, string $type): Project
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
            'type' => $type,
        ]);
    }
}
