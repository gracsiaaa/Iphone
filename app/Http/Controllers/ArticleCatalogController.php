<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class ArticleCatalogController extends Controller
{
    public function index(): View
    {
        return view('articles.index', ['articles' => Article::published()->with('author')->latest('published_at')->paginate(9)]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->status === 'published' && (!$article->published_at || $article->published_at->isPast()), 404);
        $article->load('author');
        return view('articles.show', compact('article'));
    }
}
