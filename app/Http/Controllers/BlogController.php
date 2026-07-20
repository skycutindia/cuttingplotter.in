<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::where('status', 'published')->with('category', 'author')->latest('published_at')->paginate(9);

        return view('frontend.blogs.index', compact('blogs'));
    }

    public function show(string $slug): View
    {
        $blog = Blog::where('slug', $slug)->where('status', 'published')->with('category', 'author')->firstOrFail();
        $blog->increment('views');

        return view('frontend.blogs.show', compact('blog'));
    }
}
