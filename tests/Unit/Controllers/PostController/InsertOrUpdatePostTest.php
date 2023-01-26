<?php

namespace Tests\Unit\Controllers\PostController;

use App\DataSources\HackerNewsPostDataSource;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\PostController
 * @covers \App\Http\Controllers\PostController::insertOrUpdatePost
 */
class InsertOrUpdatePostTest extends TestCase
{
    use WithFaker;

    public function testSuccess(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $postData = [
            'title' => $this->faker->sentence,
            'site' => $this->faker->url,
            'score' => $this->faker->numberBetween(1, 1000),
            'author' => $this->faker->userName,
            'created' => strtotime(Carbon::parse($this->faker->dateTimeBetween('-2 years'))->diffForHumans()),
            'comments' => $this->faker->numberBetween(1, 300),
        ];

        /** @var Application $app */
        $app = app();
        $app->bind(HackerNewsPostDataSource::class, function () use ($post) {
            $mock = $this->getMockBuilder(HackerNewsPostDataSource::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['insertOrUpdate'])
                ->getMock();

            //Assert insertOrUpdate was called
            $mock->expects($this->once())
                ->method('insertOrUpdate')
                ->willReturn($post);

            return $mock;
        });

        $postController = new PostController();
        $return = $postController->insertOrUpdatePost(
            title: data_get($postData, 'title'),
            created: data_get($postData, 'created'),
            site: data_get($postData, 'site'),
            score: data_get($postData, 'score'),
            author: data_get($postData, 'author'),
            comments: data_get($postData, 'comments'),
        );

        $this->assertEquals($post, $return);
    }
}
