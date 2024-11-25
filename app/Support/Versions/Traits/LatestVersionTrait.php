<?php

namespace App\Support\Versions\Traits;

trait LatestVersionTrait
{
    public function latestVersion(array $versions)
    {
        $stableVersions = collect($versions)
            ->filter(function($version) {
                if(!is_string($version)) {
                    throw new \Exception('Version is not a string in the versions array.');
                }

                // Remove non-stable versions
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

}
