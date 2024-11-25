<?php

namespace App\Models;

use App\Domain\Project\Enum\ProjectSourceEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'url',
        'source',
        'type',
        'active',
        'repository_slug',
        'repository_client',
        'main_branch',
        'custom_branch'
    ];


    protected $casts = [
        'source' => ProjectSourceEnum::class,
    ];
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function scopeSearch(Builder $query, string $searchTerm)
    {
        if(!$searchTerm) {
            return $query;
        }

        return $query->where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('type', 'like', '%' . $searchTerm . '%')
            ->orWhere('source', 'like', '%' . $searchTerm . '%')
            // Also search packages
            ->orWhereHas('packages', function (Builder $packageQuery) use ($searchTerm) {
                $packageQuery->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('version', 'like', '%' . $searchTerm . '%');
            });
    }

}
