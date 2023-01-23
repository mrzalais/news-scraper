<?php

namespace App\Http\Controllers;

use App\DataSources\HackerNewsPostDataSource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return view('home');
    }

    public function getPosts(Request $request)
    {
        $posts = Post::query()->orderBy('created')->get();
        return response()->json($posts);
    }

    public function insertOrUpdatePost(
        string $title,
        int $created,
        string $site = null,
        string $score = null,
        string $author = null,
        int $comments = null,
    ): ?Post {
        return HackerNewsPostDataSource::instance()->insertOrUpdate(
            $title,
            $created,
            $site,
            $score,
            $author,
            $comments
        );
    }

    public function deletePost(Post $post)
    {
        //TODO: implement this
//        return HackerNewsPostDataSource::instance()->deleteById();
    }
}
