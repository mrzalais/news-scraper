<?php

namespace Tests\Unit\DataSources\HackerNewsPostDataSource;

use App\DataSources\HackerNewsPostDataSource;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @covers \App\DataSources\HackerNewsPostDataSource
 * @covers \App\DataSources\HackerNewsPostDataSource::getByTitle
 */
class GetByTitleTest extends TestCase
{
    use WithFaker;

    public function testSuccess(): void
    {
        $title = $this->faker->sentence;

        /** @var Post $post */
        $post = Post::factory()->create([
            'title' => $title,
        ]);

        $hackerNewsPostDataSource = new HackerNewsPostDataSource();
        $return = $hackerNewsPostDataSource->getByTitle($title);

        $post->refresh();
        $this->assertEquals($post->getAttributes(), $return->getAttributes());
    }
}
