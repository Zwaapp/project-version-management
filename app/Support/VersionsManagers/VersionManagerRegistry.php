<?php

namespace App\Support\VersionsManagers;

use App\Support\VersionsManagers\Contracts\VersionManagerContract;

class VersionManagerRegistry
{
    /**
     * @return VersionManagerContract[]
     */
    public function __invoke(): array
    {
        $managers = config('version-managers.selected');

        // Fallback if no managers are selected
        if(empty(array_filter($managers))) {
            $managers= config('version-managers.options');
        }

        return array_map(function($manager) {
            return app($manager);
        }, $managers);
    }
}
