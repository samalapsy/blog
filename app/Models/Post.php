<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    public const USER_LISTING_CACHE_TAG = 'user-listing';
    public const PUBLIC_LISTING_CACHE_TAG = 'public-listing';


    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'publication_date', 'published_at', 'is_published'
    ];

    protected $casts = [
        'publication_date' => 'datetime',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];


    public function getIncrementing()
    {
        return false;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function getKeyType()
    {
        return 'string';
    }

    static function forgetCachedPosts() : void
    {
        forgetCache('listing');
    }

    
    public function scopeSort($query, $criteria)
    {
        foreach ($criteria as $column => $value) {
            $query->orderBy($column, $value);
        }
        return $query->whereIsPublished(true)->whereNotNull('published_at')->whereNotNull('published_at');
    }

    public function scopeMyPosts($query) : Builder
    {
        return $query->whereUserId(auth()->id());
    }

    public function scopePublishedPosts($query) : Builder
    {
        return $query->whereIsPublished(true)->whereNotNull('published_at');
    }

    public function scopePendingScheduledPosts($query) : Builder
    {
        return $query->whereIsPublished(false)->whereNull('published_at')->where('publication_date', '<', now());
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class)->select('id', 'name','email');
    }
}
