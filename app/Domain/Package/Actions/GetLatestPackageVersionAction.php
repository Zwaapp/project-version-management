<?php

namespace App\Domain\Package\Actions;

use App\Support\Drupal\Actions\IsDrupalPackageAction;
use App\Support\VersionsManagers\Implementations\DrupalVersionManager;
use App\Support\VersionsManagers\Implementations\PackagistVersionManager;
use App\Support\VersionsManagers\Implementations\WordpressVersionManager;
use App\Support\VersionsManagers\VersionManagerRegistry;
use App\Support\Wordpress\Actions\IsWordpressPluginAction;
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
