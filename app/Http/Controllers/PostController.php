<?php

namespace App\Http\Controllers;

use App\DataSources\HackerNewsPostDataSource;
use App\Models\Post;

class PostController extends Controller
{
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
