<?php

namespace App\Domain\Package\Actions;

use App\Models\Package;

class HasLatestVersion
{
    public function __invoke(Package $package): bool
    {
        if(!$package->latest_version) {
            return false;
        }

        return $package->latest_version === $package->version;
    }
}
