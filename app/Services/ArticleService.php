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

    public function getAll(array $filters = []): array
    {
        $articles = $this->recursiveGetAll($filters);

        return array_filter($articles, fn ($a) => $a['title'] || $a['story_title']);
    }

    protected function recursiveGetAll(array $filters = [], int $page = 1, array &$articles = []): array
    {
        $results = $this->client->getAll(
            [
                ...$filters,
                'page' => $page
            ]            
        );
        
        $articles = array_merge($articles, Arr::get($results, 'data', []));
        $totalPages = Arr::get($results, 'total_pages', 0);

        return $totalPages > $page ? $this->recursiveGetAll($filters, $page + 1, $articles) : $articles;
    }

    public function getClient()
    {
        return $this->client;
    }
}