<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'slug', 'cover_image', 'category_id'];

    public static function generateSlug($title)
    {
        return Str::slug($title, '-');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags() : BelongsToMany      
    {
        return $this->belongsToMany(Tag::class);
        
    }
}
