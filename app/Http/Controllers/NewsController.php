<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::where('published', true)
            ->orderByDesc('date')
            ->paginate(9);

        return view('pages.news', compact('news'));
    }

    public function show(News $news)
    {
        $related = News::where('published', true)
            ->where('id', '!=', $news->id)
            ->orderByDesc('date')
            ->take(3)
            ->get();

        return view('pages.news-detail', compact('news', 'related'));
    }
}
