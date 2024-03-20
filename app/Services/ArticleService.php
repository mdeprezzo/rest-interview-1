<?php

namespace App\Services;

use App\Clients\ArticleClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ArticleService
{
    private $client;

    public function __construct(ArticleClient $client)
    {   
        $this->client = $client;
    }

    public function getAll(array $filters = [])
    {
        $page = Arr::get($filters, 'page', 1);

        $results = $this->client->getAll(
            [
                ...$filters,
                'page' => $page
            ]            
        );

        $articles = Arr::get($results, 'data', []);

        if ($page < $results['total_pages']) {
            for ($p = $page + 1; $p <= $results['total_pages']; $p++) {
                $articles = array_merge($articles, $this->getAll([
                    ...$filters,
                    'page' => $p
                ]));
            }
        }

        return array_filter($articles, fn ($a) => $a['title'] || $a['story_title']);
    }
}