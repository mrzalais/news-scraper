<?php

namespace Tests\Unit\DataSources\HackerNewsPostDataSource;

use App\DataSources\HackerNewsPostDataSource;
use App\Models\Post;
use Tests\TestCase;

/**
 * @covers \App\DataSources\HackerNewsPostDataSource
 * @covers \App\DataSources\HackerNewsPostDataSource::deleteById
 */
class DeleteByIdTest extends TestCase
{
    public function testSuccess(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $this->assertFalse($post->trashed());

        $hackerNewsPostDataSource = new HackerNewsPostDataSource();
        $hackerNewsPostDataSource->deleteById($post);

        $this->assertTrue($post->trashed());
    }
}
