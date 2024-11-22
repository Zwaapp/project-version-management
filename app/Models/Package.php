<?php

namespace App\Models;

use App\Support\ProjectType\Enums\ProjectTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'version',
        'type',
        'source',
        'dist',
        'require',
        'latest_version',
        'latest_version_url',
        'from_composer_json'
    ];

    public function getIsFrameworkAttribute(): bool
    {
        return ProjectTypeEnum::tryFrom($this->name) ? true : false;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
