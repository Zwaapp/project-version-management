<?php

return [
    'options' => [
        'drupal' => \App\Support\VersionsManagers\Implementations\DrupalVersionManager::class,
        'wordpress' => \App\Support\VersionsManagers\Implementations\WordpressVersionManager::class,
        // Packagist last since it's the fallback
        'packagist' => \App\Support\VersionsManagers\Implementations\PackagistVersionManager::class,
    ],
    'selected' => explode(',', env('VERSION_MANAGERS', ''))
];
