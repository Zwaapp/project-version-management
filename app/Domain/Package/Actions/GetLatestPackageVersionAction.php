<?php

namespace App\Domain\Package\Actions;

use App\Support\ComposerPackage\Actions\GetLatestPackageVersionAction as GetLatestComposerPackageVersionActionAlias;
use App\Support\Drupal\Actions\GetLatestDrupalPackageVersionAction;
use App\Support\Drupal\Actions\IsDrupalPackageAction;
use App\Support\Packagist\Actions\GetLatestPackagistVersionAction;
use App\Support\Wordpress\Actions\GetLatestWordpressPackageVersionAction;
use App\Support\Wordpress\Actions\GetLatestPackageVersionAction as GetLatestWordpressPackageVersionActionAlias;
use App\Support\Wordpress\Actions\IsWordpressPluginAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GetLatestPackageVersionAction
{
    public function __invoke(string $package): ?string
    {
        try {
            return Cache::remember('latest_package_version_' . $package, 60, function() use ($package) {
                if(app(IsWordpressPluginAction::class)($package)) {
                    return app(GetLatestWordpressPackageVersionAction::class)($package);
                }

                if(app(IsDrupalPackageAction::class)($package)) {
                    return app(GetLatestDrupalPackageVersionAction::class)($package);
                }

                return app(GetLatestPackagistVersionAction::class)($package);
            });
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getLatestFromPackagist(string $package): ?string
    {

        return $this->getLatestVersion($versions);
    }
}
