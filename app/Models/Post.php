<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function post_category()
    {
        return $this->hasOne(Category::class, 'id', 'category');
    }
}
