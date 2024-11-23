<?php

use App\Support\RepositoryClients\Bitbucket\BitbucketClient;
use App\Support\RepositoryClients\Github\GithubCompanyClient;
use App\Support\RepositoryClients\Github\GithubPersonalClient;

return [
    'list' => [
        'github_company' => GithubCompanyClient::class,
        'github_personal' => GithubPersonalClient::class,
        'bitbucket' => BitbucketClient::class,
    ],

    'repository_clients' => env('REPOSITORY_CLIENTS', '')
];
