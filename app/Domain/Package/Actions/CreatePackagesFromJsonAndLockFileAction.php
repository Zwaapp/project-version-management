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

            $directDependencies = app(GetDirectDependenciesAction::class)($composerJson);

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
}
