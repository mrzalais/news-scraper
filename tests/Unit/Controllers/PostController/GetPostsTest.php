<?php

namespace Tests\Unit\Controllers\PostController;

use App\Http\Controllers\PostController;
use App\Models\Post;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\PostController
 * @covers \App\Http\Controllers\PostController::getPosts
 */
class GetPostsTest extends TestCase
{
    public function testSuccessItReturnsAllPostsFromDataSource(): void
    {
        Post::factory()->count(10)->create();

        $postController = new PostController();
        $postsReturned = $postController->getPosts();

        $this->assertEquals(10, $postsReturned->count());
    }
}
