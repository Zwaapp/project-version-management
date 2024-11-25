<?php

namespace App\Support\Drupal\Actions;

class IsDrupalPackageAction
{
    public function __invoke(string $package): bool
    {
        // Controleer op namespaces of pakketten die specifiek voor Drupal zijn
        return !strpos($package, 'drupal/');
    }

}
