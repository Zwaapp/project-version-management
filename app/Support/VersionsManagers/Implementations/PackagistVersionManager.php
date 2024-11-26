<?php

namespace App\Support\VersionsManagers\Implementations;

use App\Support\VersionsManagers\Contracts\VersionManagerContract;
use App\Support\VersionsManagers\Traits\LatestVersionTrait;
use Illuminate\Support\Facades\Http;

class PackagistVersionManager implements VersionManagerContract
{
    use LatestVersionTrait;

    public function getLatestVersion(string $package): ?string
    {
        $url = "https://repo.packagist.org/p2/{$package}.json";

        $response = Http::get($url);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();
        $versions = $data['packages'][$package];

        // Remove the layer of the package name
        $versions = collect($versions)->map(function($version) {
            return $version['version'];
        })->toArray();

        return $this->latestVersion($versions);
    }

    public function supports(string $package): bool
    {
        // This is the default manager, so it should always return true
        return true;
    }
}
