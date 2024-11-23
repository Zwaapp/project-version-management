<?php

namespace App\Support\RepositoryClients;

use App\Support\RepositoryClients\Actions\GetRepositoryClientsAction;
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
        $this->repositoryClients = app(GetRepositoryClientsAction::class)();
    }

    /**
     * @return RepositoryClient[]
     */
    public function get(): array
    {
        return $this->repositoryClients;
    }
}
