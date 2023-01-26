<?php

namespace Tests\Feature\Console;

use App\Console\Commands\ScrapeFromHackerNewsCommand;
use App\Services\ScraperService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @covers \App\Console\Commands\ScrapeFromHackerNewsCommand
 * @covers \App\Console\Commands\ScrapeFromHackerNewsCommand::handle
 */
class HandleTest extends TestCase
{
    use WithFaker;

    public function testSuccess(): void
    {
        $data = [
            'site' => $site = $this->faker->url,
            'title' => $this->faker->sentence . ' ' . $site,
            'score' => $points = $this->faker->numberBetween(1, 1000) . ' points',
            'username' => $username = $this->faker->userName,
            'age' => $createdAt = Carbon::parse($this->faker->dateTimeBetween('-2 years'))->diffForHumans(),
            'comments' => $comments = $this->faker->numberBetween(1, 300),
            'subtext' => "{$points} by {$username} {$createdAt} | hide | {$comments} comments",
        ];

        /** @var Application $app */
        $app = app();
        $app->bind(ScraperService::class, function () use ($data) {
            $mock = $this->getMockBuilder(ScraperService::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['filterByCssSelector'])
                ->getMock();

            $mock->method('filterByCssSelector')->willReturnCallback(function ($selector) use ($data) {
                switch ($selector) {
                    case 'titleline':
                        $value = [data_get($data, 'title')];
                        break;
                    case 'sitebit':
                        $value = [data_get($data, 'site')];
                        break;
                    case 'subtext':
                        $value = [data_get($data, 'subtext')];
                        break;
                    case 'score':
                        $value = [data_get($data, 'score')];
                        break;
                    case 'age':
                        $value = [data_get($data, 'age')];
                        break;
                    case 'hnuser':
                        $value = [data_get($data, 'username')];
                        break;
                    default:
                        $value = [];
                }
                return $value;
            });

            return $mock;
        });

        $scrapeCommand = new ScrapeFromHackerNewsCommand();
        $result = $scrapeCommand->handle();

        $this->assertEquals(0, $result);

        $this->assertDatabaseHas(
            table: 'posts',
            data: [
                'author' => data_get($data, 'username')
            ]
        );
    }
}
