<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Food;
use Illuminate\Support\Facades\Auth;

class FoodController extends Controller
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
        return view('menu', ['foods' => Food::All()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = $request->all();

        $food = new Food;
        $food->nome = $input['nome'];
        $food->prezzo = $input['prezzo'];
        $food->descrizione = $input['descrizione'];
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


        $food = Food::find($input['id']);
        $food->nome = $input['nome'];
        $food->prezzo = $input['prezzo'];
        $food->descrizione = $input['descrizione'];

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
        $food = Food::find($input['id']);
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

        $food["results"] = Food::search($input)->get();
        $food["admin"] = Auth::user()->isAdmin() ? 1 : 0;
        return response()->json($food);
    }
}
