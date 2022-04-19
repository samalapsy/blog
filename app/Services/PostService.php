<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Exceptions\NoPostToCacheException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostService {

    public function getMyPosts($request, $from_cache=true)
    {
        return $from_cache ? $this->getPostViaCache($request, false) : $this->getPost($request, false);
    }

    public function getPublicPosts($request)
    {
        return $this->getPostViaCache($request);
    }

    private function getPostViaCache($request, $is_public=true)
    {
        $full_url = $request->fullUrl();
        $caching_key = ($is_public ? Post::PUBLIC_LISTING_CACHE_TAG : Post::USER_LISTING_CACHE_TAG. '-' .auth()->id()).$full_url;

        return Cache::remember($caching_key, Config::get( $is_public ? 'blog.caching.public.blog-listing': 'blog.caching.dashboard.listing'),  function() use ($request, $is_public){
            $query = $is_public ? Post::publishedPosts()->with('user') : Post::myPosts();
            $request->whenFilled('publication_date', function ($input) use ($query) {
                $query->orderBy('publication_date', $input ?? 'desc');
            }, function() use($query){
                $query->orderBy('id', 'desc');
            });

            if($is_public)
                $query->orderBy('published_at', 'desc');
            
            $response = $query->paginate(Config::get($is_public ? 'blog.pagination_count.posts.public' : 'blog.pagination_count.post.dashboard'))->withQueryString();
            if($response->total() <=0 ){
                throw new NoPostToCacheException('No Post Found to cache');
            }
            // Pay attention to the items you're caching, so as not to overload the memory with junks   
            // Just add a toArray() method to the result.
            return $response;
        });
    }

    private function getPost($request, $is_public=true)
    {
        $full_url = $request->fullUrl();
        $caching_key = $is_public ? auth()->id().'-user_pag-' : 'listings-'.$full_url;

        
            $query = $is_public ? Post::publishedPosts()->with('user') : Post::myPosts();
            $request->whenFilled('publication_date', function ($input) use ($query) {
                $query->orderBy('publication_date', $input ?? 'desc');
            }, function() use($query){
                $query->orderBy('id', 'desc');
            });

            if($is_public)
                $query->orderBy('published_at', 'desc');
            
            $response = $query->paginate(Config::get($is_public ? 'blog.pagination_count.posts.public' : 'blog.pagination_count.posts.dashboard'))->withQueryString();
            if($response->total() <=0 ){
                throw new Exception('No Post Found to cache');
            }
            // Pay attention to the items you're caching, so as not to overload the memory with junks   
            // Just add a toArray() method to the result.
            return $response;
    }

    public function getPostDetails($post)
    {
        if(!$post)
            throw new ModelNotFoundException('Post not found');

        return Cache::rememberForever($post->slug, function() use ($post){
            return $post->load('user');
            // ->toArray();
        });
    }


}