<?php

namespace App\Http\Controllers;

use App\Table;
use Illuminate\Http\Request;
use PDF;

class PrintController extends Controller
{
    public function index($id)
    {
        return view('pdf_view', ['table' => Table::find($id)]);
    }

    public function printpdf()
    {
        // This  $data array will be passed to our PDF blade
        logger('PrintController: Lancio della funzione printpdf.');
        $data = [
            'title' => 'First PDF for Medium',
            'heading' => 'Hello from 99Points.info',
            'content' => 'Standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.'
            ];

        $pdf = PDF::loadView('pdf', $data);
        return $pdf->download('medium.pdf');
    }
}
