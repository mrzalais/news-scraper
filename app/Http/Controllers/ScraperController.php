<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Goutte\Client;

class ScraperController extends Controller
{
    public function scrape(): void
    {
        $httpClient = new Client();
        $result = $httpClient->request('GET', 'https://news.ycombinator.com');

        //Extract to some filter method
        $titles = [];
        $result->filter('.titleline')->each(function ($node) use (&$titles) {
            $titles[] = $node->text();
        });

        $sites = [];
        $result->filter('.sitebit')->each(function ($node) use (&$sites) {
            $sites[] = $node->text();
        });

        $subtexts = [];
        $result->filter('.subtext')->each(function ($node) use (&$subtexts) {
            $subtexts[] = $node->text();
        });
        $scores = [];
        $result->filter('.score')->each(function ($node) use (&$scores) {
            $scores[] = $node->text();
        });
        $ages = [];
        $result->filter('.age')->each(function ($node) use (&$ages) {
            $ages[] = $node->text();
        });
        $authors = [];
        $result->filter('.hnuser')->each(function ($node) use (&$authors) {
            $authors[] = $node->text();
        });

        //Extract to parse method
        $filteredSubtexts = [];
        foreach ($subtexts as $key => $subtext) {
            $filteredSubtexts[$key] = [
                'title' => $titles[$key],
                'site' => null,
                'score' => 0,
                'author' => null,
                'age' => 0,
                'comments' => 0,
                'subtext' => $subtext,
            ];
            foreach ($sites as $site) {
                if (strpos($titles[$key], $site)) {
                    $filteredSubtexts[$key]['title'] = str_replace(' ' . $site, "", $titles[$key]);
                    $filteredSubtexts[$key]['site'] = $site;
                }
            }
            foreach ($scores as $score) {
                if (str_starts_with($subtext, $score)) {
                    $filteredSubtexts[$key]['score'] = (int) preg_replace("/[^0-9.]/", '', $score);
                    $offset = 4;
                    $subtext = substr($subtext, strlen($score) + $offset);
                }
            }
            foreach ($authors as $author) {
                if (str_starts_with($subtext, $author)) {
                    $filteredSubtexts[$key]['author'] = $author;
                    $offset = 1;
                    $subtext = substr($subtext, strlen($author) + $offset);
                }
            }
            foreach ($ages as $age) {
                if (str_starts_with($subtext, $age)) {
                    $filteredSubtexts[$key]['age'] = strtotime($age);
                    $offset = 10;
                    $subtext = substr($subtext, strlen($age) + $offset);
                    $filteredSubtexts[$key]['comments'] = (int) preg_replace("/[^0-9.]/", '', $subtext);
                }
            }
        }

        //Extract to save or update post method
        foreach ($filteredSubtexts as $filteredSubtext) {
            /** @var Post $post */
            $post = Post::query()->where([
                'title' => data_get($filteredSubtext, 'title'),
                'author' => data_get($filteredSubtext, 'author'),
                ])->first();

            if (!$post) {
                $post = new Post();
                $post->title = $filteredSubtext['title'];
                $post->site = $filteredSubtext['site'];
                $post->author = $filteredSubtext['author'];
                $post->created = $filteredSubtext['age'];
            }
            $post->score = $filteredSubtext['score'];
            $post->comments = $filteredSubtext['comments'];
            $post->save();
        }
    }
}
