<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ParentCategory extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'ordering',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent', 'id');
    }
}
