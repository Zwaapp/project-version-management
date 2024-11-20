<?php

namespace App\Domain\Project\Actions;

use App\Domain\Project\Enum\ProjectSourceEnum;
use App\Models\Project;

class StoreProjectAction
{
    public function __invoke(string $name, string $url, ProjectSourceEnum $source, string $type): Project
    {
        return Project::updateOrCreate([
            'name' => $name,
            'url' => $url,
            'source' => $source->value,
        ], [
            'name' => $name,
            'url' => $url,
            'source' => $source->value,
            'type' => $type,
        ]);

    }

}
