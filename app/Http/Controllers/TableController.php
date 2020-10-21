<?php

namespace App\Http\Controllers;

use App\Food;
use Illuminate\Http\Request;
use App\Table;
use App\Order;
use App\Client;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return view('tables.view', ['table' => Table::find($id), 'clients' => Client::all()]);
    }

    public function add(Request $request)
    {
        $input = $request->all();

        /*
        $ordinen = DB::table('orders')->updateOrInsert(
            ['table_id' => $input['table_id'], 'food_id' => $input['food_id']],
            ['amount' => DB::table('orders')->raw('amount + 1')]
        );
        */

        // TODO: si creano duplicati. Fare una query per vedere se ci sono già le righe da inserire. NON è urgente, cancellare cancella tutto
        $new = new Order;
        $new->table_id = $input["table_id"];
        $new->food_id = $input["food_id"];
        $new->amount = 1;
        $new->add_percent = 0;

        $new->save();

        $fornituraModel = Food::find($input['food_id']);
        $total = Table::find($input['table_id'])->totalOrders();
        $totalMargin = Table::find($input['table_id'])->totalPercentAdded();

        return response()->json(array('order' => $new, 'fornitura' => $fornituraModel, 'total' => $total, 'totalWithMargin' => $totalMargin));

    }

    public function updateOrderAmount(Request $request){

        $input = $request->all();
        logger("Aggiorno l'amount dell'ordine " . $input['table_id'] . "-" . $input['food_id'] );

        $ordine = DB::table('orders')
            ->where('table_id', $input['table_id'])
            ->where('food_id', $input['food_id'])
            ->update(['amount' => $input["amount"] ]);

    }

    public function updateOrderAddpercent(Request $request){

        $input = $request->all();
        logger("Aggiorno l'add_percentage dell'ordine " . $input['table_id'] . "-" . $input['food_id'] );

        $ordine = DB::table('orders')
            ->where('table_id', $input['table_id'])
            ->where('food_id', $input['food_id'])
            ->update(['add_percent' => $input["add_percent"] ]);

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

        if (isset($input['stato'])) {
            $stati = array('libero', 'occupato', 'servito');
            $table->stato = $stati[$input['stato']];
        }

        $table->save();
        return response()->json($table);
    }

    public function updateData(Request $request)
    {

        $table = Table::find($request['id']);
        $table['noteAggiuntive'] = $request['note'];
        $table['ricarico'] = $request['ricarico'];
        $table['creatoDa'] = $request['creatoDa'];
        $table['client_id'] = $request['cliente'];
        logger("TableController: Log richiesta updateData: " . $request['creatoDa'] . " a " . $request['cliente']);
        $table->save();

        //return $pdf->download('Mannaggia.pdf');
    }

    public function destroy(Request $request)
    {
        $input = $request->all();

        $order = Order::where('table_id', $input['table_id'])->where('food_id', $input['food_id'])->delete();

        return response()->json(array('order' => $order, 'total' => Table::find($input['table_id'])->totalOrders()));
    }

    public function empty(Request $request)
    {
        $input = $request->all();

        $order = Order::where('table_id', $input['table_id'])->delete();

        $response = ['messaggio' => 'prodotto eliminato'];
        return response()->json($response);
    }

    public function anteprima(Request $request)
    {

    }

}
