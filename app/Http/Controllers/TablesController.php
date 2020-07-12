<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Table;
use Illuminate\Support\Facades\Auth;


class TablesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('tables.show', ['tables' => Table::all()]);
    }

    public function clientIndex($clientId){
        return view('tables.show', ['tables' => Table::where('cliente', $clientId)->get()]);
    }

    public function get()  // Restituisce tutti i tavoli con conto degli ordini e prezzo totale
    {
        if($tables = Table::all()){
            foreach ($tables as $key => $table) {
                $table->countOrders = $table->countOrders();
                $table->totalOrders = round($table->totalOrders(), 2);
            }
            return response()->json($tables);
        }

    }

    public function create(Request $request)
    {
        $input = $request->all();
        logger($input['prev']);
        $preventivo = new Table;
        $preventivo->nomeTavolo = $input['prev'];
        $preventivo->creatoDa = Auth::user()->username;
        $preventivo->save();

        return response()->json($preventivo);
    }

    public function update(Request $request)
    {
        $input = $request->all();

        foreach($input['tables'] as $key => $value){
          $table = Table::find($value['id']);

          if(isset($value['nome']))
            $table->nomeTavolo = $value['nome'];

          if(isset($value['stato'])){
            $stati = array('libero', 'occupato', 'servito');
            $table->stato = $stati[$value['stato']];
          }
          if(isset($value['cliente'])){
            $table->cliente = $value['cliente'];
          }

          $table->save();
        }

        $response = ['messaggio' => 'successo'];

        return response()->json($input);
    }

    public function destroy(Request $request)
    {
        //Log::info($id);
        $input = $request->all();
        $table = Table::find($input['id']);
        $table->delete();

        //return redirect('/expense');

        $response = ['messaggio' => 'utente eliminato'];

        return response()->json($table);
    }
}
