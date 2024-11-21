<?php

return [
    'bitbucket' => [
        'token' => env('BITBUCKET_APP_PASSWORD', ''),
        'workspace' => env('BITBUCKET_WORKSPACE', ''),
        'username' => env('BITBUCKET_USERNAME', '')

    ],
    'github' => [
        'personal' => [
            'token' => env('GITHUB_PERSONAL_TOKEN', ''),
            'user' => env('GITHUB_PERSONAL_USER', '')
        ],
        'company' => [
            'token' => env('GITHUB_ORGANIZATION_TOKEN', ''),
            'organization' => env('GITHUB_ORGANIZATION', '')
        ]
    ]
];
