<?php

namespace App\Domain\Package\Actions;

use App\Support\ComposerPackage\Actions\GetLatestPackageVersionAction as GetLatestComposerPackageVersionActionAlias;
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
                $url = $this->getRemoteUrl($package);
                $response = Http::get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    $versions = $data['packages'][$package];

                    $stableVersions = array_filter($versions, function($package) {
                        // Strip alpha, beta and rc versions
                        return !preg_match('/-(alpha|beta|rc)/i', $package['version']);
                    });

                    // Find the latest stable version
                    usort($stableVersions, function($a, $b) {
                        /*
                         * Delete the 'v' prefix from the version since in some cases it
                         * is present and in some cases it is not within the same package information
                         */
                        $aVersion = ltrim($a['version'], 'v');
                        $bVersion = ltrim($b['version'], 'v');

                        return version_compare($bVersion, $aVersion);
                    });

                    return $stableVersions[0]['version'] ?? null;
                }

                return null;
            });
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getRemoteUrl(string $package): string
    {
        if(app(IsWordpressPluginAction::class)($package)) {
            return "https://wpackagist.org/p2/{$package}.json";
        }

        return "https://repo.packagist.org/p2/{$package}.json";
    }
}
