<?php

namespace Tests\Unit\DataSources\HackerNewsPostDataSource;

use App\DataSources\HackerNewsPostDataSource;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @covers \App\DataSources\HackerNewsPostDataSource
 * @covers \App\DataSources\HackerNewsPostDataSource::getByTitleAndSite
 */
class GetByTitleAndSiteTest extends TestCase
{
    use WithFaker;

    public function testSuccess(): void
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
}
