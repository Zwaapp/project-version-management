<?php

namespace App\Models;

use App\Domain\Project\Enum\ProjectSourceEnum;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'url',
        'source',
        'type',
        'repository_slug',
        'repository_client',
        'main_branch'
    ];


    protected $casts = [
        'source' => ProjectSourceEnum::class,
    ];
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

}
