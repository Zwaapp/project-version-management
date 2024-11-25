<?php

namespace App\Support\Wordpress\Actions;

use App\Support\Versions\Traits\LatestVersionTrait;
use Illuminate\Support\Facades\Http;

class GetLatestWordpressPackageVersionAction
{
    use LatestVersionTrait;

    public function __invoke(string $package): ?string
    {
        // Stripe verything before the '/' to keep only the package name
        $slug = explode('/', $package)[1];
        $url = "https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]={$slug}";

        $response = Http::get($url);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        // Flip to have url as the key and version as the value and then reset the keys
        $versions = array_values(array_flip($data['versions']));

        return $this->latestVersion($versions);
    }

}
