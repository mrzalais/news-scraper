<?php

namespace App\Console\Commands;

use App\Http\Controllers\PostController;
use App\Services\ScraperService;
use Illuminate\Console\Command;

class ScrapeFromHackerNewsCommand extends Command
{
    private const HACKER_NEWS_URI = 'https://news.ycombinator.com';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:scrape from hacker news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Orders the scraper to scrape data from news.ycombinator.com';
    /**
     * @var array[]
     */
    protected array $postArray;
    protected array $filteredValues;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        /** @var ScraperService $scraper */
        $scraper = app(ScraperService::class, ['siteUri' => self::HACKER_NEWS_URI]);

        $fields = $this->getFields();

        $this->postArray = $this->getPostArrayStructure();

        foreach ($fields as $field => $cssSelector) {
            $this->postArray[$field] = $scraper->filterByCssSelector($cssSelector);
        }

        foreach ($this->postArray['subtexts'] as $key => $subtext) {
            $this->filteredValues[] = $this->parseFilteredValues($key, $subtext);
        }

        $postController = new PostController();

        foreach ($this->filteredValues as $filteredValue) {
            $postController->insertOrUpdatePost(
                data_get($filteredValue, 'title'),
                data_get($filteredValue, 'age'),
                data_get($filteredValue, 'site'),
                data_get($filteredValue, 'score'),
                data_get($filteredValue, 'author'),
                data_get($filteredValue, 'comments'),
            );
        }

        return self::SUCCESS;
    }

    protected function parseFilteredValues(string $key, string $subtext): array
    {
        $filteredPostData = $this->getDefaultValuesForPost();
        $filteredPostData['title'] = $this->postArray['titles'][$key];
        $filteredPostData['subtext'] = $subtext;
        foreach ($this->postArray['sites'] as $site) {
            if (strpos($this->postArray['titles'][$key], $site)) {
                $filteredPostData['title'] = str_replace(' ' . $site, "", $this->postArray['titles'][$key]);
                $filteredPostData['site'] = $site;
            }
        }
        foreach ($this->postArray['scores'] as $score) {
            if (str_starts_with($subtext, $score)) {
                $filteredPostData['score'] = (int) preg_replace("/[^0-9.]/", '', $score);
                $offset = 4;
                $subtext = substr($subtext, strlen($score) + $offset);
            }
        }
        foreach ($this->postArray['authors'] as $author) {
            if (str_starts_with($subtext, $author)) {
                $filteredPostData['author'] = $author;
                $offset = 1;
                $subtext = substr($subtext, strlen($author) + $offset);
            }
        }
        foreach ($this->postArray['ages'] as $age) {
            if (str_starts_with($subtext, $age)) {
                $filteredPostData['age'] = strtotime($age);
                $offset = 10;
                $subtext = substr($subtext, strlen($age) + $offset);
                $filteredPostData['comments'] = (int) preg_replace("/[^0-9.]/", '', $subtext);
            }
        }
        return $filteredPostData;
    }

    protected function getDefaultValuesForPost(): array
    {
        return [
            'title' => '',
            'site' => null,
            'score' => 0,
            'author' => null,
            'age' => 0,
            'comments' => 0,
            'subtext' => '',
        ];
    }

    protected function getFields(): array
    {
        return [
            'titles' => 'titleline',
            'sites' => 'sitebit',
            'subtexts' => 'subtext',
            'scores' => 'score',
            'ages' => 'age',
            'authors' => 'hnuser'
        ];
    }

    protected function getPostArrayStructure(): array
    {
        return [
            'titles' => [],
            'sites' => [],
            'subtexts' => [],
            'scores' => [],
            'ages' => [],
            'authors' => [],
        ];
    }
}
