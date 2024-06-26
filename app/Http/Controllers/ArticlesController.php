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
        $articles = $this->articleService->getAll($request->only('author'));
        
        return ArticleResource::collection($articles);      
    }
    
    /* for test purpose */
    public function baseIndex(Request $request)
    {
        return $this->articleService->getClient()->getAll(
            [
                ...$request->all(),
                'page' => $request->get('page') ?? 1
            ]            
        );
    }
}