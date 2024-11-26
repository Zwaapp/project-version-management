<?php

namespace App\Support\VersionsManagers\Implementations;

use App\Support\VersionsManagers\Contracts\VersionManagerContract;
use App\Support\VersionsManagers\Traits\LatestVersionTrait;
use Illuminate\Support\Facades\Http;

class DrupalVersionManager implements VersionManagerContract
{
    use LatestVersionTrait;

    public function getLatestVersion(string $package): ?string
    {
        // Strip everything before the '/' to keep only the module name
        $moduleName = explode('/', $package)[1];
        $url = "https://updates.drupal.org/release-history/{$moduleName}/current";

        $response = Http::get($url);

        if (!$response->successful()) {
            return null;
        }

        // Parse the XML response from Drupal.org
        $data = simplexml_load_string($response->body());

        if ($data === false || !isset($data->releases)) {
            return null;
        }

        $releases = collect($data->releases->release)
            ->map(function ($release) {
                return (string) $release->version;
            })
            ->values()
            ->toArray();

        return $this->latestVersion($releases);
    }

    public function supports(string $package): bool
    {
        return strpos($package, 'drupal/');
    }
}
