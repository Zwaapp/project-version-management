<?php

namespace App\Domain\Package\Actions;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CreatePackagesFromJsonAndLockFileAction
{
    public function __invoke(Project $project, array $composerJson, array $composerLock): void
    {
        DB::beginTransaction();

        try {
            $project->packages()->delete();

            $project->touch();

            $directDependencies = $this->getDirectDependencies($composerJson);

            foreach ($composerLock['packages'] as $package) {
                $rootPackage = in_array($package['name'], $directDependencies);

                app(CreatePackageAction::class)(
                    project: $project,
                    name: $package['name'],
                    version: $package['version'],
                    type: $package['type'],
                    fromJsonFile: $rootPackage,
                    latestVersion: app(GetLatestPackageVersionAction::class)($package['name']),
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    private function getDirectDependencies(array $composerJson): array
    {
        $composerJsonData = $composerJson;

        $directDependencies = array_merge(
            array_keys($composerJsonData['require'] ?? []),   // Directe productie afhankelijkheden
            array_keys($composerJsonData['require-dev'] ?? []) // Directe development afhankelijkheden
        );

        return $directDependencies;
    }

}
