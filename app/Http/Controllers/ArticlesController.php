<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ArticleService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;

class ArticlesController extends Controller
{
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function __invoke(Request $request)
    {
        $articles = $this->articleService->getAll($request->all());
        
        return ArticleResource::collection($articles);      
    }
}