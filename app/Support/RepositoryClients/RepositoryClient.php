<?php

namespace App\Support\RepositoryClients;

use App\Domain\Project\Enum\ProjectSourceEnum;
use App\Support\RepositoryClients\Objects\RepositoryObject;

interface RepositoryClient
{
    /**
     * Get the repositories from the repository
     *
     * @return RepositoryObject[]
     */
    public function getRepositories(): array;

    /**
     * Get the composer.json file from the repository
     *
     * @param string $repo
     * @param string $branch
     *
     * @return array
     */
    public function getComposerJsonFile(string $repo, string $branch): array;

    /**
     * Get the composer.lock file from the repository
     *
     * @param string $repo
     * @param string $branch
     *
     * @return array
     */
    public function getComposerLockFile(string $repo, string $branch): array;
}
