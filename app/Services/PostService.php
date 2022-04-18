<?php

namespace App\Services;

use App\Jobs\ImportPostJob;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class PostService {

    public function getMyPosts($request)
    {
        return $this->getPostViaCache($request, false);
    }


    public function getPublicPosts($request)
    {
        return $this->getPostViaCache($request);
    }


    private function getPostViaCache($request, $is_public=true)
    {
        $full_url = $request->fullUrl();
        $caching_key = $full_url;
        
        return Cache::remember($caching_key, Config::get( $is_public ? 'blog.caching.public.blog-listing': 'blog.caching.dashboard.listing'),  function() use ($request, $is_public){
            $query = $is_public ? Post::publishedPosts()->with('user') : Post::myPosts();
            $request->whenFilled('publication_date', function ($input) use ($query) {
                $query->orderBy('publication_date', $input ?? 'desc');
            }, function() use($query){
                $query->orderBy('id', 'desc');
            });

            if($is_public)
                $query->orderBy('published_at', 'desc');


            return $query->paginate(Config::get($is_public ? 'blog.pagination_count.posts.public' : 'blog.pagination_count.post.dashboard'))->withQueryString();
        });
    }

}