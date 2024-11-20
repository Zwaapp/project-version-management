<?php

namespace App\Support\Github;

use Illuminate\Support\Facades\Http;

class GithubPersonalClient
{
    protected $token;
    protected $organization;

    public function __construct()
    {
        $this->token = env('GITHUB_TOKEN');
        $this->organization = env('GITHUB_ORGANIZATION');
        $this->user = env('GITHUB_USER');
    }

    public function getOrganizationRepositories()
    {
        $response = Http::withToken($this->token)
            ->get("https://api.github.com/orgs/{$this->organization}/repos");

        if ($response->successful()) {
            return $response->json();
        }

        return null; // Foutafhandeling of loggen van het probleem kan hier worden toegevoegd
    }

    public function getPersonalRepositories()
    {
        $response = Http::withToken($this->token)
            ->get("https://api.github.com/user/repos");

        if ($response->successful()) {
            return $response->json();
        }

        return null; // Foutafhandeling of loggen van het probleem kan hier worden toegevoegd
    }

    public function getComposerLockFile($repo)
    {
        $response = Http::withToken($this->token)
            ->get("https://api.github.com/repos/{$this->user}/{$repo}/contents/composer.lock?ref=master");

        if ($response->successful()) {
            $content = $response->json();
            $decodedContent = base64_decode($content['content']);

            return $decodedContent;
        }

        return null;
    }

    public function getComposerJsonFile($repo)
    {
        $response = Http::withToken($this->token)
            ->get("https://api.github.com/repos/{$this->user}/{$repo}/contents/composer.json?ref=master");

        if ($response->successful()) {
            $content = $response->json();
            $decodedContent = base64_decode($content['content']);

            return $decodedContent;
        }

        return null;
    }

}
