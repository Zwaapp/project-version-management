<?php

namespace App\Support\RepositoryClients\Github;

use App\Domain\Project\Enum\ProjectSourceEnum;
use App\Support\RepositoryClients\Contracts\RepositoryClient;
use App\Support\RepositoryClients\Exceptions\MissingCredentialsException;
use App\Support\RepositoryClients\Objects\RepositoryObject;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GithubPersonalClient implements RepositoryClient
{
    protected string $token;
    protected string $user;

    public function __construct()
    {
        $this->token = config('repository-settings.github.personal.token');
        $this->user = config('repository-settings.github.personal.user');

        if (empty($this->token) || empty($this->user)) {
            throw new MissingCredentialsException('GitHub token or user is not set');
        }
    }

    public function getRepositories(): array
    {
        $response = Http::withToken($this->token)
            ->get("https://api.github.com/user/repos");

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
                source: ProjectSourceEnum::GITHUB_PERSONAL
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

        return [];
    }
}
