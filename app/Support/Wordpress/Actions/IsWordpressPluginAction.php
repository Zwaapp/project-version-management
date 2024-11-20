<?php

namespace App\Support\Wordpress\Actions;

class IsWordpressPluginAction
{
    public function __invoke(string $package): bool
    {
        return strpos($package, 'wordpress-plugin') !== false;
    }

}
