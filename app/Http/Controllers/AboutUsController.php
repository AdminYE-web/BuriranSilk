<?php

namespace App\Http\Controllers;

use App\Models\AboutUsPage;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AboutUsController extends Controller
{
    public function index(): View
    {
        $aboutUsPage = Schema::hasTable('about_us_pages')
            ? AboutUsPage::query()->first()
            : null;

        return view('frontend.about_us.index', compact('aboutUsPage'));
    }
}
