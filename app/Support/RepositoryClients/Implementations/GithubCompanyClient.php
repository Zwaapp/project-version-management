<?php

namespace App\Support\RepositoryClients\Implementations;

use App\Domain\Project\Enum\ProjectSourceEnum;
use App\Support\RepositoryClients\Contracts\RepositoryClient;
use App\Support\RepositoryClients\Exceptions\MissingCredentialsException;
use App\Support\RepositoryClients\Objects\RepositoryObject;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GithubCompanyClient implements RepositoryClient
{
    protected string $token;
    protected string $organization;

    public function __construct()
    {
        $this->token = config('repository-settings.github.company.token');
        $this->organization = config('repository-settings.github.company.organization');

        if (empty($this->token) || empty($this->organization)) {
            throw new MissingCredentialsException('GitHub token or organization is not set');
        }
    }

    public function getRepositories(): array
    {
        $response = Http::withToken($this->token)
            ->get("https://api.github.com/orgs/{$this->organization}/repos");

        if (!$response->successful()) {
            Log::error("Failed to fetch repositories: {$response->body()}");

            return [];
        }

        $repositories = $response->json();

        return collect($repositories)->map(function ($repository) {
            return new RepositoryObject(
                name: $repository['name'],
                url: $repository['html_url'],
                mainBranch: $repository['default_branch'],
                repoSlug: $repository['full_name'],
                source: ProjectSourceEnum::GITHUB_ORGANIZATION
            );
        })->toArray();
    }

    public function getComposerLockFile(string $repo, string $branch): array
    {
        return $this->getFileFromRepository($repo, 'composer.lock', $branch);
    }

    public function getComposerJsonFile(string $repo, string $branch): array
    {
        return $this->getFileFromRepository($repo, 'composer.json', $branch);
    }

    private function getFileFromRepository(string $repo, string $filePath, string $branch = 'master'): array
    {
        $response = Http::withToken($this->token)
            ->get("https://api.github.com/repos/{$repo}/contents/{$filePath}?ref={$branch}");

        if ($response->successful()) {
            $content = $response->json();
            $decodedContent = base64_decode($content['content']);

            return json_decode($decodedContent, true);
        }

        Log::error("Failed to fetch {$filePath} for {$repo}: {$response->body()}");

        return [];
    }
}

