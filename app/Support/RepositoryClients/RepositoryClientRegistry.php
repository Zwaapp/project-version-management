<?php

namespace App\Support\RepositoryClients;

use App\Support\RepositoryClients\Contracts\RepositoryClient;
use App\Support\RepositoryClients\Exceptions\MissingCredentialsException;

class RepositoryClientRegistry
{
    /**
     * @var RepositoryClient[]
     */
    public array $repositoryClients = [];

    public function __construct()
    {
        foreach(config('repository-clients.default') as $repositoryClientClass) {
            try {
                $repositoryClient = app($repositoryClientClass);
            } catch (MissingCredentialsException $e) {
                // If no credentials are found, simply continue to the next repository client
                continue;
            }

            if($repositoryClient instanceof RepositoryClient) {
                $this->repositoryClients[] = $repositoryClient;
            }
        }
    }

    /**
     * @return RepositoryClient[]
     */
    public function get(): array
    {
        return $this->repositoryClients;
    }

    public function addManager(RepositoryClient $repositoryClient)
    {
        $this->repositoryClients[] = $repositoryClient;
    }
}
