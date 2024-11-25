<?php

namespace App\Models;

use App\Domain\Package\Actions\HasLatestVersion;
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

    public function hasLatestVersion(): bool
    {
        return app(HasLatestVersion::class)($this);
    }

    public function scopeSearch($query, $search)
    {
        // Also search where project active is true
        return $query->where('name', 'like', '%' . $search . '%')->orWhere('version', 'like', '%' . $search . '%')
            ->whereHas('project', function ($query) use ($search) {
                $query->where('active', true);
            });
    }
}
