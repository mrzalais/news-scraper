<?php

namespace Tests\Unit\DataSources\HackerNewsPostDataSource;

use App\DataSources\HackerNewsPostDataSource;
use App\Models\Post;
use Tests\TestCase;

/**
 * @covers \App\DataSources\HackerNewsPostDataSource
 * @covers \App\DataSources\HackerNewsPostDataSource::getPaginatedPosts
 */
class GetPaginatedPostsTest extends TestCase
{
    public function testSuccess(): void
    {
        Post::factory()->count(10)->create();

        $hackerNewsPostDataSource = new HackerNewsPostDataSource();
        $posts = $hackerNewsPostDataSource->getPaginatedPosts();

        $this->assertCount(10, $posts->items());
    }
}
