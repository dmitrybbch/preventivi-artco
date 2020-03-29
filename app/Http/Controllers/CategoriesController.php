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

    public function store(Request $request)
    {
        $input = $request;



        // If it's a Category

        //if($input['sezcat'] == "cat"){

            $cat = new Category();
            $cat->name = $input['name'];

            $cat->save();



        //}

        return response($input, 204);
    }
}
