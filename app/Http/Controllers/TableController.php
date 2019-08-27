<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Table;
use App\Order;


class TableController extends Controller
{
    //controllo autorizzazione ad accedere alle risorse
     public function __construct()
     {
         $this->middleware('auth');
     }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function index($id)
     {
         return view('tables.view', ['table' => Table::find($id)]);
     }


     public function add(Request $request)
     {
          $input = $request->all();

          $order = new Order();
          $order->table_id = $input['table_id'];
          $order->food_id = $input['food_id'];

          $order->save();
          return response()->json(array('order' => $order, 'total' => Table::find($input['table_id'])->totalOrders()));
     }

     public function orders($id)
     {
          $json = array();

          foreach (Table::find($id)->orders() as $key => $order) {
            $food = $order->food();
            $json[] = array('id' => $order->food_id, 'nome' => $food->nome, 'prezzo' => $food->prezzo, 'total' => $order->total, 'descrizione' => $food->descrizione);
          }
         return response()->json($json);
     }


     public function update(Request $request)
     {
         $input = $request->all();

         $table = Table::find($input['id']);

         if(isset($input['stato'])){
           $stati = array('libero', 'occupato', 'servito');
           $table->stato = $stati[$input['stato']];
         }

         $table->save();

         return response()->json($table);
     }

     public function destroy(Request $request)
     {
          $input = $request->all();

          $order = Order::where('table_id', $input['table_id'])->where('food_id', $input['food_id'])->limit(1)->delete();

          return response()->json(array('order' => $order, 'total' => Table::find($input['table_id'])->totalOrders()));
     }

     public function empty(Request $request)
     {
          $input = $request->all();

          $order = Order::where('table_id', $input['table_id'])->delete();

          $response = ['messaggio' => 'prodotto eliminato'];
          return response()->json($response);
     }
}
