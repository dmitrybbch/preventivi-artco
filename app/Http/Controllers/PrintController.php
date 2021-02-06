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
        /*
        //This  $data array will be passed to our PDF blade
        logger('PrintController: Lancio della funzione printpdf.');
        $prev = Table::find($id);
        //$ordini = $prev->orders();

        // share data to view
        //view()->share('employee',$data);
        //$pdf = PDF::loadView('pdf_view', $data);
        logger("Anteprimo il preventivo: " . $prev->nomeTavolo);
        $datat = $prev;
        $pdf = PDF::loadView('pdf2', ['datat' => Table::find($id)]);
        return $pdf->download('preventivo.pdf');
*/
        // retrieve all records from db
        $datat = Table::find($id);

        // share data to view
        view()->share('datat',$datat);
        $pdf = PDF::loadView('pdf2', $datat);

        // download PDF file with download method
        return $pdf->download('Preventivo.pdf');
    }
}
