<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'featuredProducts' => Product::active()->with('images')->where('is_featured', true)->latest()->take(8)->get(),
            'latestProducts' => Product::active()->with('images')->latest()->take(8)->get(),
            'latestArticles' => Article::published()->with('author')->latest('published_at')->take(3)->get(),
            'faqs' => Faq::active()->orderBy('sort_order')->take(5)->get(),
        ]);
    }
}
