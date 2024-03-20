<?php

use App\Clients\ArticleClient;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;
use App\Services\ArticleService;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('articles', function (Request $request, ArticleService $articleService) {
    $articles = $articleService->getAll($request->all());

    return ArticleResource::collection($articles);
});
