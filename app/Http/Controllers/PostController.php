<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Post\PostRequest;

class PostController extends Controller
{
    private $postService;
    
    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $query_parameters = $request->query();
        try {
            $results = $this->postService->getMyPosts($request);
            return view('dashboard.posts.index', compact('results', 'query_parameters'));
        } catch (Exception $e) {
            Log::critical('[PostController:index]', $e);
            return view('dashboard.posts.index', compact('query_parameters'))->with('error', 'Unable to get blog posts, please try again');
        }
    }


    public function create()
    {
        return view('dashboard.posts.create');
    }


    public function store(PostRequest $request )
    {
        $post = auth()->user()->posts()->firstOrCreate($request->validated());
        if(!$post)
            return back()->withInput();
        
        return redirect()->route('dashboard.posts.show', $post->slug)->with('success', 'Post created successfully');
    }


    public function show(Post $post)
    {
        $caching_key = 'dashboard.'. $post->slug ;
        $result = Cache::rememberForever($caching_key, function() use ($post){
            return $post->load('user');
        });

        $url =  url()->full();
        return view('dashboard.posts.show', compact('result', 'url'));
    }

}
