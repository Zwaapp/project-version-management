<?php

use App\Support\RepositoryClients\Implementations\BitbucketClient;
use App\Support\RepositoryClients\Implementations\GithubCompanyClient;
use App\Support\RepositoryClients\Implementations\GithubPersonalClient;

return [
    'list' => [
        'github_company' => GithubCompanyClient::class,
        'github_personal' => GithubPersonalClient::class,
        'bitbucket' => BitbucketClient::class,
    ],

    'repository_clients' => env('REPOSITORY_CLIENTS', '')
];
