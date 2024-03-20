<?php

namespace App\Clients;

use Illuminate\Support\Arr;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ArticleClient
{
    protected $baseUrl;

    public function __construct(array $config)
    {
        $this->baseUrl = Arr::get($config, 'base_url', null);
    }  

    private function getHttp()
    {
        return Http::baseUrl($this->baseUrl);
    }

    public function getAll(array $filters = [])
    {
        $page = Arr::get($filters, 'page', 1);

        $cacheKeys = ["page:{$page}"];

        $querystring = ['page' => $page];

        if ($author = Arr::get($filters, 'author', null)) {
            array_push($cacheKeys, "author:{$author}");
            $querystring = [...$querystring, 'author' => $author];
        }

        $cacheKey = "articles:" . implode(':', $cacheKeys);

        $baseHttp = $this->getHttp();
        $response = Cache::remember($cacheKey, 3600, function () use ($baseHttp, $querystring) {
            $response = $baseHttp->get('/', $querystring);

            if ($response->successful()) return $response->json();
            $response->throw();
        });

        return $response;
    }
}