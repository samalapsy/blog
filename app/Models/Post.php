<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Post extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'publication_date', 'published_at', 'is_published'
    ];


    public function getIncrementing()
    {
        return false;
    }

    protected $casts = [
        'publication_date' => 'datetime',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function getKeyType()
    {
        return 'string';
    }

    
    public function scopeSort($query, $criteria)
    {
        foreach ($criteria as $column => $value) {
            $query->orderBy($column, $value);
        }
        return $query->whereIsPublished(true)->whereNotNull('published_at')->whereNotNull('published_at');
    }

    public function scopeMyPosts($query)
    {
        return $query->whereUserId(auth()->id());
    }

    public function scopePublishedPosts($query)
    {
        return $query->whereIsPublished(true)->whereNotNull('published_at');
    }

    public function scopePendingScheduledPosts($query)
    {
        return $query->whereIsPublished(false)->whereNull('published_at');
    }

    public function scopeSystemAdminPosts($query)
    {
        return $query->whereTypUserId(1);
    }

    public function scopePopular($query)
    {
        return $query->where('views', '>', Config::get('blog.popular_post_view_count', 100));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
