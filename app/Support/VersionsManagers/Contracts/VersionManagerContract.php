<?php

namespace App\Support\VersionsManagers\Contracts;

interface VersionManagerContract
{

    public function getLatestVersion(string $package): ?string;
    public function supports(string $package): bool;

}
