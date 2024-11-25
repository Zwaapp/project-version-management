<?php

namespace App\Support\Wordpress\Actions;

class IsWordpressPluginAction
{
    public function __invoke(string $package): bool
    {
        $packageNames = [
            'wpackagist-plugin',
            'wordpress-plugin',
        ];

        foreach($packageNames as $packageName) {
            if(strpos($package, $packageName) === 0) {
                return true;
            }
        }

        return false;
    }

}
