<?php

namespace App\Http\Controllers;

use App\DataSources\HackerNewsPostDataSource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('home');
    }

    public function getPosts(): JsonResponse
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

    public function delete(Post $post): Response
    {
        HackerNewsPostDataSource::instance()->deleteById($post);
        return response()->noContent();
    }
}
