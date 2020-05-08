<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Table;
use App\Order;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PDF;

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

         $ordine = DB::table('orders')->where('table_id', $input['table_id'])->where('food_id', $input['food_id']);
         logger(count($ordine->get()));

         /*
         // Modifico l'esistente
         if(count($ordine->get())){
             $ord = $ordine->first();
             $ord->amount = $ord->amount + 1;
             $ord->save();

             return response()->json(array('order' => $ord, 'total' => Table::find($input['table_id'])->totalOrders())); // TODO: da fixare il calcolo del totale. Usare la variabile amount
         }
         // Altrimenti creo un nuovo
         else {
             $order = new Order();

             $order->table_id = $input['table_id'];
             $order->food_id = $input['food_id'];
             $order->amount = 1; // TODO: Togliere il numero magico
             $order->add_percent = 0;

             $order->save();

             return response()->json(array('order' => $order, 'total' => Table::find($input['table_id'])->totalOrders()));
         }
*/
         $ordinen = DB::table('orders')->updateOrInsert(
             ['table_id' => $input['table_id'], 'food_id' => $input['food_id']],
             ['amount' => DB::table('orders')->raw('amount + 1') ]
         );

     }

     public function orders($id)
     {
          $json = array();

          foreach (Table::find($id)->orders() as $key => $order) {
            $food = $order->food();
            $json[] = array('id' => $order->food_id, 'nome' => $food->nome, 'prezzo' => $food->prezzo, 'unita' => $food->unita, 'total' => $order->total, 'descrizione' => $food->descrizione, 'capitolo' => $food->capitolo, 'categoria' => $food->categoria, 'immagine' => $food->immagine);
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

     public function updateData(Request $request){

         $table = Table::find($request['id']);
         $table['noteAggiuntive'] = $request['note'];
         $table['ricarico'] = $request['ricarico'];
         $table['creatoDa'] = $request['creatoDa'];
         $table['cliente'] = $request['cliente'];
         logger("TableController: Log richiesta updateData: ". $request['creatoDa'] . " a " . $request['cliente']);
         $table->save();

         //return $pdf->download('Mannaggia.pdf');
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

     public function anteprima(Request $request){


     }
}
