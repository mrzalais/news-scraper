<?php

namespace Tests\Unit\Services\ScraperService;

use App\Services\ScraperService;
use Goutte\Client;
use Illuminate\Contracts\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @covers \App\Services\ScraperService
 * @covers \App\Services\ScraperService::filterByCssSelector
 */
class FilterByCssSelectorTest extends TestCase
{
    private const XML_FILE_PATH = __DIR__ . '/../../../TestFiles/NewsXml.xml';

    public function testSuccess(): void
    {
        /** @var Application $app */
        $app = app();
        $app->bind(Client::class, function () {
            $mock = $this->getMockBuilder(Client::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['request'])
                ->getMock();

            $crawler = new Crawler();
            $crawler->addXmlContent(file_get_contents(self::XML_FILE_PATH));
            $mock->method('request')->willReturn($crawler);
            return $mock;
        });

        /** @var ScraperService $scraper */
        $scraper = app(ScraperService::class, ['siteUri' => 'https://news.ycombinator.com']);
        $return = $scraper->filterByCssSelector('titleline');
        $this->assertEquals(
            expected: [
                0 => "Mjolnir (fabiensanglard.net)",
                1 => "Show HN: I've built a C# IDE, Runtime, and AppStore inside Excel (querystorm.com)",
            ],
            actual: $return
        );
    }
}
