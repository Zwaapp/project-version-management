<?php

use App\Support\RepositoryClients\Bitbucket\BitbucketClient;
use App\Support\RepositoryClients\Github\GithubCompanyClient;
use App\Support\RepositoryClients\Github\GithubPersonalClient;

return [
    'default' => [
        GithubCompanyClient::class,
        GithubPersonalClient::class,
        BitbucketClient::class,
    ]
];
