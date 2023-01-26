<?php

namespace Tests\Unit\DataSources\HackerNewsPostDataSource;

use App\DataSources\HackerNewsPostDataSource;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @covers \App\DataSources\HackerNewsPostDataSource
 * @covers \App\DataSources\HackerNewsPostDataSource::insertOrUpdate
 */
class InsertOrUpdateTest extends TestCase
{
    use WithFaker;

    public function testSuccess(): void
    {
        $postData = [
            'title' => $this->faker->sentence,
            'site' => $this->faker->url,
            'score' => $this->faker->numberBetween(1, 1000),
            'author' => $this->faker->userName,
            'created' => strtotime(Carbon::parse($this->faker->dateTimeBetween('-2 years'))->diffForHumans()),
            'comments' => $this->faker->numberBetween(1, 300),
        ];

        $hackerNewsPostDataSource = new HackerNewsPostDataSource();
        $hackerNewsPostDataSource->insertOrUpdate(
            title: data_get($postData, 'title'),
            created: data_get($postData, 'created'),
            site: data_get($postData, 'site'),
            score: data_get($postData, 'score'),
            author: data_get($postData, 'author'),
            comments: data_get($postData, 'comments'),
        );

        $this->assertDatabaseHas(
            table: 'posts',
            data: [
                'author' => data_get($postData, 'author'),
            ]
        );
    }
}
