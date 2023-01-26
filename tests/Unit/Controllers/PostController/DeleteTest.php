<?php

namespace Tests\Unit\Controllers\PostController;

use App\DataSources\HackerNewsPostDataSource;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Foundation\Application;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\PostController
 * @covers \App\Http\Controllers\PostController::delete
 */
class DeleteTest extends TestCase
{
    public function testSuccessDeleteByIdIsCalled(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        /** @var Application $app */
        $app = app();
        $app->bind(HackerNewsPostDataSource::class, function () {
            $mock = $this->getMockBuilder(HackerNewsPostDataSource::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['deleteById'])
                ->getMock();

            //Assert deleteById was called
            $mock->expects($this->once())->method('deleteById');

            return $mock;
        });

        $postController = new PostController();
        $return = $postController->delete($post);
        $this->assertEquals(204, $return->status());
    }
}
