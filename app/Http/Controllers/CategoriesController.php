<?php

namespace App\Http\Controllers;

use App\Category;
use App\Chapter;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function index()
    {
        return view('categories', ['cats' => Category::All(), 'secs' => Chapter::All()]);
    }

    public function store(Request $request)
    {
        $input = $request;

        // If it's a Category
        //if($input['sezcat'] == "cat"){
        $cat = new Category();
        $cat->name = $input['name'];
        $cat->section_id = $input['section_id'];

        $cat->save();

        //}
        return response($input, 204);
    }

    public function storeSection(Request $request)
    {
        $input = $request;

        // If it's a Category
        //if($input['sezcat'] == "cat"){
        $cat = new Chapter();
        $cat->name = $input['name'];

        $cat->save();
        //}
        return response($input, 204);
    }

    public function destroy(Request $request)
    {
        $input = $request->all();

        $sec = Chapter::find($input['id']);
        $sec->delete();

        $response = ['messaggio' => 'Categoria Eliminata'];

        return response()->json($response);
    }
}
