<?php

namespace App\Providers;

use App\Clients\ArticleClient;
use App\Services\ArticleService;
use Carbon\Laravel\ServiceProvider;

class ArticleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ArticleService::class, function() {
            $config = $this->app->config['articles'];
            $client = new ArticleClient($config);

            return new ArticleService($client);
        });

        $this->app->alias(ArticleService::class, 'article_service');  
    }
}