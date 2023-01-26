<?php

namespace Tests\Unit\DataSources\HackerNewsPostDataSource;

use App\DataSources\HackerNewsPostDataSource;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @covers \App\DataSources\HackerNewsPostDataSource
 * @covers \App\DataSources\HackerNewsPostDataSource::getPostIfExists
 */
class GetPostIfExistsTest extends TestCase
{
    use WithFaker;

    public function testSuccessWithTitleAndAuthor(): void
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

    public function testSuccessWithTitleAndSite(): void
    {
        $title = $this->faker->sentence;
        $site = $this->faker->url;

        /** @var Post $post */
        $post = Post::factory()->create([
            'title' => $title,
            'site' => $site,
        ]);

        $hackerNewsPostDataSource = new HackerNewsPostDataSource();
        $return = $hackerNewsPostDataSource->getByTitleAndSite($title, $site);

        $post->refresh();
        $this->assertEquals($post->getAttributes(), $return->getAttributes());
    }

    public function testSuccessWithTitle(): void
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
