<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->with('sections')->firstOrFail();

        return view('frontend.pages.show', compact('page'));
    }
}
