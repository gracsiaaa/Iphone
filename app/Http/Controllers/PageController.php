<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;

class PageController extends Controller
{
    public function faq(): View
    {
        return view('pages.faq', ['faqs' => Faq::active()->orderBy('category')->orderBy('sort_order')->get()->groupBy('category')]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}
