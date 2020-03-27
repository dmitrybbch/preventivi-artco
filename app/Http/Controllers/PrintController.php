<?php

namespace App\Http\Controllers;

use App\Table;
use Illuminate\Http\Request;
use PDF;

class PrintController extends Controller
{
    public function index($id)
    {
        return view('pdf_view', ['datat' => Table::find($id)]);
    }

    public function printpdf($id)
    {
        //This  $data array will be passed to our PDF blade
        logger('PrintController: Lancio della funzione printpdf.');
        $prev = Table::find($id);
        $ordini = $prev->orders();
        logger("Anteprimo il preventivo: " . $prev->nomeTavolo);
        
        $pdf = PDF::loadView('pdf');
        return $pdf->download('medium.pdf');


    }
}
