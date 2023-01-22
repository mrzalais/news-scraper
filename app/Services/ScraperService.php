<?php

namespace App\Services;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScraperService
{
    private string $siteUri;
    private Crawler $crawler;

    public function __construct(string $siteUri)
    {
        $this->siteUri = $siteUri;
        $this->crawler = $this->setUpCrawler();
    }

    private function setUpCrawler(): Crawler
    {
        $httpClient = new Client();
        return $httpClient->request('GET', $this->siteUri);
    }

    public function filterByCssSelector(string $selector): array
    {
        $filteredData = [];
        $this->crawler->filter('.' . $selector)->each(function ($node) use (&$filteredData) {
            $filteredData[] = $node->text();
        });

        return $filteredData;
    }

}
