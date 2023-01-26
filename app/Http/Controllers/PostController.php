<?php

namespace App\Http\Controllers;

use App\DataSources\HackerNewsPostDataSource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('home');
    }

    public function getPosts(): AnonymousResourceCollection
    {
        $posts = HackerNewsPostDataSource::instance()->getAllPosts();
        return PostResource::collection($posts);
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
