<?php

namespace App\Domain\Project\Actions;

use App\Domain\Package\Actions\CreatePackagesFromJsonAndLockFileAction;
use App\Domain\Project\Enum\ProjectSourceEnum;
use App\Support\Github\GithubPersonalClient;
use Illuminate\Support\Facades\Cache;

class FetchAndCreateProjectsAction
{
    public function __invoke()
    {
        $projects = app(GithubPersonalClient::class)->getPersonalRepositories();

        foreach($projects as $project) {
            $jsonFile = Cache::remember('github_personal_composer_json' . $project['name'], 60, function() use ($project) {
                return app(GithubPersonalClient::class)->getComposerJsonFile($project['name']);
            });
            $lockFile = Cache::remember('github_personal_composer_lock_' . $project['name'], 60, function() use ($project) {
                return app(GithubPersonalClient::class)->getComposerLockFile($project['name']);
            });

            // If there is no lock file there is no reason to create any data for this project
            if(!$lockFile) {
                continue;
            }

            $project = app(StoreProjectAction::class)(
                name: $project['name'],
                url: $project['html_url'],
                source: ProjectSourceEnum::GITHUB_PERSONAL,
                type: app(GetProjectTypeAction::class)($jsonFile)
            );

            app(CreatePackagesFromJsonAndLockFileAction::class)($project, $jsonFile, $lockFile);

            // todo: remove projects that are not within the list

        }
    }

}
