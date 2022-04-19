<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomePageController extends Controller
{
    private $postService;

    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    public function index(Request $request )
    {
        $query_parameters = $request->query();
        $results = [];
        try {
            $results = $this->postService->getPublicPosts($request);
            return view('public.index', compact('results', 'query_parameters'));   
        } catch (NotFoundHttpException | Exception $e) {
            Log::critical('[PostController@index]'. json_encode($e));
            return view('public.index', compact('results', 'query_parameters'))->withError('No Blog posts found, please try again after some minutes');
        }
           
    }


    public function showPost(Post $post)
    {
        try {
            $result = ($this->postService->getPostDetails($post));
            $url = url()->full();
            return view('public.show', compact('url', 'result'));
        } catch (ModelNotFoundException | RelationNotFoundException | Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

}
