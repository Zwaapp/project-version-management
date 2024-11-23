<?php

namespace App\Domain\Package\Actions;

class GetDirectDependenciesAction
{
    public function __invoke(array $composerJson): array
    {
        return array_merge(
            array_keys($composerJson['require'] ?? []),
            array_keys($composerJson['require-dev'] ?? [])
        );
    }
}
