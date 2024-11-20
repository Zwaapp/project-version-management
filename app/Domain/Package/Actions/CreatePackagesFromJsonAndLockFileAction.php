<?php

namespace App\Domain\Package\Actions;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CreatePackagesFromJsonAndLockFileAction
{
    public function __invoke(Project $project, string $composerJson, string $composerLock): void
    {
        // Use db transaction to never lose any data if anything fails
        DB::beginTransaction();

        try {
            // Delete all packages before start
            $project->packages()->delete();
            $project->touch();

            $directDependencies = $this->getDirectDependencies($composerJson);

            $data = json_decode($composerLock, true);

            // Loop door de packages in de composer.lock
            foreach ($data['packages'] as $package) {
                // Controleer of de package voorkomt in de directe afhankelijkheden uit composer.json
                $rootPackage = in_array($package['name'], $directDependencies);

                app(CreatePackageAction::class)(
                    project: $project,
                    name: $package['name'],
                    version: $package['version'],
                    type: $package['type'],
                    fromJsonFile: $rootPackage,  // Markeren als root package als het een directe afhankelijkheid is
                    latestVersion: app(GetLatestPackageVersionAction::class)($package['name']),
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    private function getDirectDependencies($composerJson): array
    {
        // Laad de composer.json
        $composerJsonData = json_decode($composerJson, true);

        // Haal de directe afhankelijkheden uit de `require` en `require-dev` secties van composer.json
        $directDependencies = array_merge(
            array_keys($composerJsonData['require'] ?? []),   // Directe productie afhankelijkheden
            array_keys($composerJsonData['require-dev'] ?? []) // Directe development afhankelijkheden
        );

        return $directDependencies;
    }

}
