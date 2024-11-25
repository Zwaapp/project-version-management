<?php

namespace App\Support\Packagist\Actions;

use App\Support\Versions\Traits\LatestVersionTrait;
use Illuminate\Support\Facades\Http;

class GetLatestPackagistVersionAction
{
    use LatestVersionTrait;

    public function __invoke(string $package): ?string
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

        $this->latestVersion($versions);
    }
}
