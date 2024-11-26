<?php

namespace App\Domain\Package\Actions;

use App\Support\VersionsManagers\VersionManagerRegistry;
use Illuminate\Support\Facades\Cache;

class GetLatestPackageVersionAction
{
    public function __invoke(string $package): ?string
    {
        try {
            return Cache::remember('latest_package_version_' . $package, 60, function() use ($package) {
                $versionManagers = app(VersionManagerRegistry::class)();

                foreach($versionManagers as $versionManager) {
                    if(!$versionManager->supports($package)) {
                        continue;
                    }

                    return $versionManager->getLatestVersion($package);
                }
            });
        } catch (\Exception $e) {
            return null;
        }
    }
}
