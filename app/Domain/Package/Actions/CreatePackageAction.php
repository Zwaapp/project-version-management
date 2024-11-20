<?php

namespace App\Domain\Package\Actions;

use App\Models\Package;
use App\Models\Project;

class CreatePackageAction
{
    public function __invoke(
        Project $project,
        string $name,
        string $version,
        string $type,
        bool $fromJsonFile,
        ?string $source = null,
        ?string $require = null,
        ?string $latestVersion = null,
        ?string $latestVersionUrl = null,
    )
    {
        return Package::create([
            'project_id' => $project->id,
            'name' => $name,
            'version' => $version,
            'type' => $type,
            'from_composer_json' => $fromJsonFile,
            'source' => $source,
            'require' => $require,
            'latest_version' => $latestVersion,
            'latest_version_url' => $latestVersionUrl,
        ]);
    }

}
