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
        $articles = $this->recursiveGetAll($filters);

        return array_filter($articles, fn ($a) => $a['title'] || $a['story_title']);
    }

    protected function recursiveGetAll(array $filters = [], int $page = 1)
    {
        $results = $this->client->getAll(
            [
                ...$filters,
                'page' => $page
            ]            
        );
        
        $articles = Arr::get($results, 'data', []);
        $totalPages = Arr::get($results, 'total_pages', 0);
        
        if ($totalPages > $page) {
            $articles = array_merge($articles, $this->recursiveGetAll($filters, $page + 1));
        }        

        return $articles;
    }
}