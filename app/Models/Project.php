<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'url',
        'source',
        'type',
        'main_branch'
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

}
