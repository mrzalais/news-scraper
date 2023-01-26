<?php

namespace Tests\Unit\Controllers\PostController;

use App\DataSources\HackerNewsPostDataSource;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\PostController
 * @covers \App\Http\Controllers\PostController::getPosts
 */
class GetPostsTest extends TestCase
{
    public function testSuccessItReturnsAllPostsFromDataSource(): void
    {
        /** @var Collection $posts */
        $posts = Post::factory()->count(10)->create();

        /** @var Application $app */
        $app = app();
        $app->bind(HackerNewsPostDataSource::class, function () use ($posts) {
            $mock = $this->getMockBuilder(HackerNewsPostDataSource::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['getAllPosts'])
                ->getMock();

            $mock->method('getAllPosts')->willReturn($posts);

            return $mock;
        });

        $postController = new PostController();
        $postsReturned = $postController->getPosts();
        /** @var Collection $returnedCollection */
        $returnedCollection = $postsReturned->getOriginalContent();

        $this->assertEquals($posts->count(), $returnedCollection->count());
    }
}
