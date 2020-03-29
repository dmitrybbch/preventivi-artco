<?php

namespace App\Http\Controllers;

use App\Category;
use App\Section;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function index()
    {
        return view('categories', ['cats' => Category::All(), 'secs' => Section::All()]);
    }
}
