<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{

    public function __construct(Type $var = null) {
        $this->var = $var;
    }

    public function index(Request $request, PostService $postService)
    {
        
        $query_parameters = $request->query();
        try {
            $results = $postService->getPublicPosts($request);
            return view('public.index', compact('results', 'query_parameters'));
        } catch (Exception $e) {
            Log::critical('[PostController@index]', $e);
            return view('public.index', compact('query_parameters'))->with('error', 'Unable to get blog posts, please try again');
        }
    
        return view('public.index', compact('results'));
        
    }


    public function showPost(Post $post)
    {
        $result = Cache::rememberForever($post->slug, function() use ($post){
            
            return $post->load('user');
        });

        $url = url()->full();

        return view('public.show', compact('url', 'result'));
    }

    public function searchResult()
    {
        $results = [];
        return view('public.search-result', compact('results'));
    }


}
