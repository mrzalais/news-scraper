<?php

namespace App\DataSources;

use App\Models\Post;
use App\Traits\HasInstance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HackerNewsPostDataSource
{
    use HasInstance;

    public function getByTitleAndAuthor(string $title, string $author): ?Post
    {
        /** @var Post $post */
        $post = Post::withTrashed()->where([
            'title' => $title,
            'author' => $author,
        ])->first();
        return $post;
    }

    public function getByTitleAndSite(string $title, string $site): ?Post
    {
        /** @var Post $post */
        $post = Post::withTrashed()->where([
            'title' => $title,
            'site' => $site,
        ])->first();
        return $post;
    }

    public function getByTitle(string $title): ?Post
    {
        /** @var Post $post */
        $post = Post::withTrashed()->where('title', $title)->first();
        return $post;
    }

    public function getPostIfExists(string $title, string $site = null, string $author = null): ?Post
    {
        if ($author) {
            $post = $this->getByTitleAndAuthor($title, $author);
        } elseif ($site) {
            $post = $this->getByTitleAndSite($title, $site);
        } else {
            $post = $this->getByTitle($title);
        }
        return $post;
    }

    public function insertOrUpdate(
        string $title,
        int $created,
        string $site = null,
        string $score = null,
        string $author = null,
        int $comments = null,
    ): ?Post {
        $post = $this->getPostIfExists($title, $site, $author);
        if ($post?->trashed()) {
            return $post;
        }

        if (!$post) {
            $post = new Post();
            $post->title = $title;
            $post->site = $site;
            $post->author = $author;
            $post->created = $created;
        }

        $post->score = $score;
        $post->comments = $comments;
        $post->save();

        return $post;
    }

    public function deleteById(Post $post): bool
    {
        return $post->delete();
    }

    public function getPaginatedPosts(): LengthAwarePaginator
    {
        return Post::query()->orderBy('created')->paginate(10);
    }
}
