<?php

namespace App\Support\RepositoryClients\Actions;

use App\Support\RepositoryClients\Contracts\RepositoryClient;
use App\Support\RepositoryClients\Exceptions\NotARepositoryClientException;
use App\Support\RepositoryClients\Exceptions\RepositoryClientNotFoundException;

class GetRepositoryClientsAction
{
    /**
     * @return RepositoryClient[]
     */
    public function __invoke(): array
    {
        $repositoryClients = config('repository-clients.repository_clients');
        $repositoryClients = explode(',', $repositoryClients);

        $availableClients = config('repository-clients.list');

        return collect($repositoryClients)
            ->map(function(string $client) use ($availableClients) {
                if(!isset($availableClients[$client])) {
                    throw new RepositoryClientNotFoundException("Repository client {$client} not found");
                }

                $client = app($availableClients[$client]);

                if(!$client instanceof RepositoryClient) {
                    throw new NotARepositoryClientException("Repository client {$client} is not a valid repository client");
                }

                return $client;
            })->toArray();
    }
}
