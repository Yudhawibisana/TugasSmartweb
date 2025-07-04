<?php

namespace App\Http\Controllers\Front;

use App\Models\Category;
use App\Models\Postingan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index($slugCategory)
    {
        return view('front.category.index', [
            'postingans' => Postingan::with('Category')->whereHas('Category', function ($q) use ($slugCategory) {
                $q->where('slug', $slugCategory);
            })->latest()->paginate(6),
            'category' => $slugCategory,
            'categories' => Category::all(),
            //'category_navbar' => Category::latest()->take(3)->get()
        ]);
    }
}
