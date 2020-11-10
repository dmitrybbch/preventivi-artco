<?php

namespace App\Http\Controllers;

use App\Order;
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
        return view('tables.show', ['tables' => Table::where('stato', '!=', 'servito')->get()]);
    }

    public function archiveIndex()
    {
        return view('tables.archive', ['tables' => Table::where('stato', 'servito')->get()]);
    }

    public function clientIndex($clientId){
        return view('tables.show', ['tables' => Table::where('client_id', $clientId)->get()]);
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

    public function cloneTable($id, $quote_name){ //Potrebbe servire una request normale con un verbo nuovo, invece che solo id
        $table = Table::find($id);
        $new_table = $table->replicate();
        $new_table->nomeTavolo = $quote_name;
        $new_table->push();

        foreach($table->orders() as $oldOrder){
            $orderCopia = new Order;
            $orderCopia->food_id = $oldOrder->food_id;
            $orderCopia->table_id = $new_table->id;
            $orderCopia->amount = $oldOrder->amount;
            $orderCopia->add_percent = $oldOrder->add_percent;
            $orderCopia->save();
        }
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
