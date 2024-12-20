<?php

namespace App\Domain\Project\Actions;

use App\Support\ProjectType\Enums\ProjectTypeEnum;

class GetProjectTypeAction
{
    public function __invoke(array $composerJson): string
    {
        $packages = array_keys($composerJson['require'] ?? []) ?? [];

        $frameworks = [];

        // Since multiple frameworks can be active in a project, we need to loop through all packages to find the frameworks
        foreach($packages as $package) {
            $framework = ProjectTypeEnum::tryFrom($package);

            if(!is_null($framework)) {
                $frameworks[] = $framework->label();
            }
        }

        return implode(', ', $frameworks);
    }

}
