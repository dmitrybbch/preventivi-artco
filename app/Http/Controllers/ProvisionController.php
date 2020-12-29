<?php

namespace App\Http\Controllers;

use App\Provision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ProvisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('menu', ['foods' => Provision::All()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = $request->all();

        $food = new Provision;
        $food->name = $input['name'];
        $food->cost = $input['cost'];
        $food->unit = $input['unit'];
        $food->description = $input['description'];

        $food->chapter = $input['chapter'];
        $food->category = $input['category'];
        $food->chapter_category = $food->chapter . "_" . $food->category;

        if($request->hasFile('image')){
            $file_immagine = $request->file('image');
            $food->image = $file_immagine->getClientOriginalName();
            Image::make($file_immagine)->resize(null, 600, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('img_uploads') . '/' . $food->image);
        } else {
            $food->image = "";
        }

        $food->save();
        return response()->json($food);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $input = $request->all();

        $food = Provision::find($input['id']);
        $food->name = $input['name'];
        $food->cost = $input['cost'];
        $food->unit = $input['unit'];
        $food->description = $input['description'];
        $food->chapter = $input['chapter'];
        $food->category = $input['category'];
        $food->chapter_category = $food->chapter . "_" . $food->category;

        $food->save();
        return response()->json($food);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $food = Provision::find($input['id']);
        $food->delete();

        //return redirect('/expense');

        $response = ['messaggio' => 'prodotto eliminato'];

        return response()->json($response);
    }

    public function search(Request $request){

        $input = $request['input'];
        /*$food = Food::where('nome' , 'like' , "%{$input}%")
        ->orwhere('descrizione' , 'like' , "%{$input}%")
        ->get();*/

        $food["results"] = Provision::search($input)->orderBy('chapter', 'ASC')->orderBy('category', 'ASC')->get();
        $food["admin"] = Auth::user()->isAdmin() ? 1 : 0;
        return response()->json($food);
    }
}
