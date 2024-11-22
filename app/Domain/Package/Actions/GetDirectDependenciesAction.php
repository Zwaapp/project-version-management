<?php

namespace App\Domain\Package\Actions;

class GetDirectDependenciesAction
{
    public function __invoke(array $composerJson): array
    {
        return array_merge(
            array_keys($composerJsonData['require'] ?? []),
            array_keys($composerJsonData['require-dev'] ?? [])
        );
    }
}
