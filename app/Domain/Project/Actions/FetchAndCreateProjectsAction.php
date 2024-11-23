<?php

namespace App\Domain\Project\Actions;

use App\Domain\Package\Jobs\FetchPackagesJob;
use App\Support\RepositoryClients\Contracts\RepositoryClient;
use App\Support\RepositoryClients\RepositoryClientRegistry;

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

            $project = app(StoreProjectAction::class)(
                repositoryObject: $repository,
                repositoryClient: $repositoryClient,
            );

            dispatch(new FetchPackagesJob($project, $repositoryClient));

            // todo: remove projects that are not within the list since they are removed
        }
    }
}
