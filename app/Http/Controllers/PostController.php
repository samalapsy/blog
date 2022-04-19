<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Post\PostRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    private $postService;
    
    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $query_parameters = $request->query();
        $results = [];
        try {
            $results = $this->postService->getMyPosts($request, false);
            return view('dashboard.posts.index', compact('results', 'query_parameters'));
        } catch (NotFoundHttpException | Exception $e) {
            Log::critical('[PostController:index]'. json_encode($e));
            return view('dashboard.posts.index', compact('results','query_parameters'))->with('error', 'Unable to get blog posts, please try again');
        }
    }


    public function create()
    {
        return view('dashboard.posts.create');
    }


    public function store(PostRequest $request )
    {
        try {
            $post = auth()->user()->posts()->firstOrCreate($request->validated());
            return redirect()->route('dashboard.posts.show', $post->slug)->with('success', 'Post created successfully');
        } catch (QueryException | Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

    }


    public function show(Post $post)
    {
        try {
            $result = $this->postService->getPostDetails($post);
            $url = url()->full();
            return view('dashboard.posts.show', compact('result', 'url'));
        } catch (ModelNotFoundException | RelationNotFoundException | Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

}
