<?php

namespace App\Support\RepositoryClients\Objects;

use App\Domain\Project\Enum\ProjectSourceEnum;

class RepositoryObject
{
    public function __construct(
        public string $name,
        public string $url,
        public string $mainBranch,
        public string $repoSlug,
        public ProjectSourceEnum $source
    )
    {

    }

}
