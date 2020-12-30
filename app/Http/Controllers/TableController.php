<?php

namespace App\Http\Controllers;

use App\Food;
use App\Provision;
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
        $new->provision_id = $input["provision_id"];
        $new->amount = 1;
        $new->add_percent = 0;

        $new->save();

        $fornituraModel = Provision::find($input['provision_id']);
        $total = Table::find($input['table_id'])->totalOrders();
        $totalMargin = Table::find($input['table_id'])->totalPercentAdded();

        return response()->json(array('order' => $new, 'fornitura' => $fornituraModel, 'total' => $total, 'totalWithMargin' => $totalMargin));

    }

    public function updateOrderAmount(Request $request){

        $input = $request->all();
        logger("Aggiorno l'amount dell'ordine " . $input['table_id'] . "-" . $input['provision_id'] );

        $ordine = DB::table('orders')
            ->where('table_id', $input['table_id'])
            ->where('provision_id', $input['provision_id'])
            ->update(['amount' => $input["amount"] ]);

    }

    public function updateOrderAddpercent(Request $request){

        $input = $request->all();
        logger("Aggiorno l'add_percentage dell'ordine " . $input['table_id'] . "-" . $input['provision_id'] );

        $ordine = DB::table('orders')
            ->where('table_id', $input['table_id'])
            ->where('provision_id', $input['provision_id'])
            ->update(['add_percent' => $input["add_percent"] ]);

    }

    public function orders($id)
    {
        $json = array();

        foreach (Table::find($id)->orders() as $key => $order) {
            $food = $order->food();
            $json[] = array('id' => $order->provision_id, 'name' => $food->name, 'cost' => $food->cost, 'unit' => $food->unit, 'total' => $order->total, 'description' => $food->description, 'chapter' => $food->chapter, 'category' => $food->category, 'image' => $food->image);
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
        $table['cliente'] = $request['cliente'];
        logger("TableController: Log richiesta updateData: " . $request['creatoDa'] . " a " . $request['cliente']);
        $table->save();

        //return $pdf->download('Mannaggia.pdf');
    }

    public function destroy(Request $request)
    {
        $input = $request->all();

        // Cancello gli ordini a lui associati
        $order = Order::where('table_id', $input['table_id'])->where('provision_id', $input['provision_id'])->delete();

        $total = Table::find($input['table_id'])->totalOrders();
        $totalMargin = Table::find($input['table_id'])->totalPercentAdded();
        return response()->json(array('order' => $order, 'total' => $total, 'totalWithMargin' => $totalMargin));
    }

    public function empty(Request $request)
    {
        $input = $request->all();

        $order = Order::where('table_id', $input['table_id'])->delete();

        $response = ['messaggio' => 'Preventivo svuotato.'];
        return response()->json($response);
    }


}
