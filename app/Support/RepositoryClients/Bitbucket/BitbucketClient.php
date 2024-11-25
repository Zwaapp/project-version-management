<?php

namespace App\Support\RepositoryClients\Bitbucket;

use App\Domain\Project\Enum\ProjectSourceEnum;
use App\Support\RepositoryClients\Contracts\RepositoryClient;
use App\Support\RepositoryClients\Exceptions\MissingCredentialsException;
use App\Support\RepositoryClients\Objects\RepositoryObject;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BitbucketClient implements RepositoryClient
{
    protected string $token;
    protected array $workspaces;
    protected string $workspace;
    protected string $username;

    public function __construct()
    {
        $this->token = config('repository-settings.bitbucket.token');
        $this->workspace = config('repository-settings.bitbucket.workspace');
        $this->workspaces = explode(',', $this->workspace);
        $this->username = config('repository-settings.bitbucket.username');

        if (empty($this->token) || empty($this->workspace) || empty($this->username)) {
            throw new MissingCredentialsException('Missing Bitbucket credentials');
        }
    }

    public function getRepositories(): array
    {
        $repositories = [];

        foreach ($this->workspaces as $workspace) {
            $url = "https://api.bitbucket.org/2.0/repositories/{$workspace}?pagelen=25";

            do {
                $response = Http::withBasicAuth($this->username, $this->token)
                    ->get($url);

                if (!$response->successful()) {
                    Log::error("Failed to fetch repositories from Bitbucket: {$response->body()}");
                    break;
                }

                $workspaceRepositories = $response->json()['values'] ?? [];

                if (empty($workspaceRepositories)) {
                    break;
                }

                $mappedRepositories = collect($workspaceRepositories)->map(function ($repository) {
                    return new RepositoryObject(
                        name: $repository['name'],
                        url: $repository['links']['html']['href'],
                        mainBranch: $repository['mainbranch']['name'] ?? null,
                        repoSlug: $repository['full_name'],
                        source: ProjectSourceEnum::BITBUCKET
                    );
                })->toArray();

                $repositories = array_merge($repositories, $mappedRepositories);

                // Ga naar de volgende pagina als er een 'next'-link is
                $url = $response->json()['next'] ?? null;

            } while ($url);
        }

        return $repositories;
    }


    public function getComposerLockFile(string $repo, string $branch): array
    {
        return $this->getFileFromRepository($repo, 'composer.lock', $branch);
    }

    public function getComposerJsonFile(string $repo, string $branch): array
    {
        return $this->getFileFromRepository($repo, 'composer.json', $branch);
    }

    protected function getFileFromRepository(string $repo, string $filePath, string $branch = 'master'): array
    {
        $url = "https://api.bitbucket.org/2.0/repositories/{$repo}/src/{$branch}/{$filePath}";

        $response = Http::withBasicAuth($this->username, $this->token)
            ->get($url);

        if (!$response->successful()) {
            Log::error("Failed to fetch {$filePath} for repository {$repo}: {$response->body()}");
            return [];
        }

        $content = $response->body();

        return json_decode($content, true);
    }
}
