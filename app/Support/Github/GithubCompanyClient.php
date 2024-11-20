<?php

namespace App\Support\Github;

use Illuminate\Support\Facades\Http;

class
GithubCompanyClient
{
    protected $token;
    protected $organization;

    public function __construct()
    {
        $this->token = env('GITHUB_TOKEN');
        $this->user = env('GITHUB_USER');
    }

    public function getComposerLockFile($username, $repo)
    {
        $response = Http::withToken($this->token)
            ->get("https://api.github.com/repos/{$username}/{$repo}/contents/composer.lock?ref=master");

        if ($response->successful()) {
            $content = $response->json();
            $decodedContent = base64_decode($content['content']);
            return json_decode($decodedContent, true);
        }

        return null;
    }

}
