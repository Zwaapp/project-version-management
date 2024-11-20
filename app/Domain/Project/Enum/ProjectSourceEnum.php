<?php

namespace App\Domain\Project\Enum;

enum ProjectSourceEnum: string
{
    case GITHUB_PERSONAL = 'github personal';
    case GITHUB_ORGANIZATION = 'github organization';

    case BITBUCKET = 'bitbucket';

}
