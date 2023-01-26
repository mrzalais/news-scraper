<?php

namespace Tests\Unit\DataSources\HackerNewsPostDataSource;

use App\DataSources\HackerNewsPostDataSource;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @covers \App\DataSources\HackerNewsPostDataSource
 * @covers \App\DataSources\HackerNewsPostDataSource::getByTitleAndAuthor
 */
class GetByTitleAndAuthorTest extends TestCase
{
    use WithFaker;

    public function testSuccess(): void
    {
        $title = $this->faker->sentence;
        $author = $this->faker->userName;

        /** @var Post $post */
        $post = Post::factory()->create([
            'title' => $title,
            'author' => $author,
        ]);

        $hackerNewsPostDataSource = new HackerNewsPostDataSource();
        $return = $hackerNewsPostDataSource->getByTitleAndAuthor($title, $author);

        $post->refresh();
        $this->assertEquals($post->getAttributes(), $return->getAttributes());
    }
}
