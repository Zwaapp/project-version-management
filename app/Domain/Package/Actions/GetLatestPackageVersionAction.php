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
                if(app(IsWordpressPluginAction::class)($package)) {
                    return $this->getLatestFromWordpress($package);
                }

                return $this->getLatestFromPackagist($package);
            });
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getLatestFromPackagist(string $package): ?string
    {
        $url = "https://repo.packagist.org/p2/{$package}.json";

        $response = Http::get($url);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();
        $versions = $data['packages'][$package];

        return $this->getLatestVersion($versions);
    }

    public function getLatestVersion(array $versions)
    {
        $stableVersions = collect($versions)
            ->map(function($version, $key) { // Make one format
                // In case of wordpress plugins the value is the link to the plugin and the key is the actual version
                return is_string($version) ? $key : $version['version'];
            })->filter(function($version) { // Remove non-stable versions
                return !preg_match('/-(alpha|beta|rc)/i', $version);
            })->toArray();

        // Find the latest stable version
        usort($stableVersions, function($a, $b) {
            /*
             * Delete the 'v' prefix from the version since in some cases it
             * is present and in some cases it is not within the same package information. This can
             * break the version_compare function.
             */
            $aVersion = ltrim($a, 'v');
            $bVersion = ltrim($b, 'v');

            return version_compare($bVersion, $aVersion);
        });

        return $stableVersions[0] ?? null;

    }

    public function getLatestFromWordpress(string $package): ?string
    {
        // Stripe verything before the '/' to keep only the package name
        $slug = explode('/', $package)[1];
        $url = "https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]={$slug}";

        $response = Http::get($url);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        return $this->getLatestVersion($data['versions']);
    }
}
