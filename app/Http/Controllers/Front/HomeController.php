<?php

namespace App\Http\Controllers\Front;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Postingan;

class HomeController extends Controller
{
    public function index()
    {
        $keyword = request()->keyword;

        $baseQuery = Postingan::with('Category')
            ->where('status', 1)
            ->where('approval_status', 'approved');

        if ($keyword) {
            $baseQuery->where('title', 'like', '%' . $keyword . '%');
        }

        $postingans = $baseQuery->latest()->simplePaginate(6);

        return view('front.home.index', [
            'latest_post' => Postingan::where('status', 1)
                ->where('approval_status', 'approved')
                ->latest()
                ->first(),
            'postingans' => $postingans,
            'categories' => Category::latest()->get()
        ]);
    }
}
