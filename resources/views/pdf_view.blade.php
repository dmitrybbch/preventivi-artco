@extends('layouts.app')
@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
            <h1 class="h2" data-id="{{ $datat->id}}">
                @if($datat->nomeTavolo)
                    {{ $datat->nomeTavolo }}
                @else
                    Anteprima del preventivo "Prev. {{ $datat->id }}"
                @endif
            </h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2" id="statusTable" role="group" aria-label="First group">
                    <b>{{$datat->cliente}}</b> ({{$datat->creatoDa}})
                </div>
            </div>
        </div>



        <div class="row">

            <div class="col-md-12">
                <table class="table table-striped" id="foodTable">
                    <thead class="thead-dark">
                    <tr><th scope="col">Nome</th><th scope="col">Prezzo</th><th scope="col">Quantità</th><th scope="col" class="d-none d-sm-table-cell">Descrizione</th><th scope="col" class="d-none d-sm-table-cell">Categoria</th><th scope="col" class="d-none d-sm-table-cell">Immagine</th></tr>
                    </thead>
                    <tbody>

                    @php ($orders = $datat->orders())
                    @if(count($orders))
                        @php($foodOrdinati = array())
                    {{ logger($foodOrdinati) }}

                    {{-- PRIMA LI INSERISCO IN UNA LISTA E LI ORDINO PER 'CATEGORIA'--}}



                    @foreach($orders as $order)
                        @php($food = $order->food())
                        @php($currentFood = array("food_id"=> ($order->food_id), "nome"=>($food->nome), "prezzo"=>($food->prezzo), "unita"=>($food->unita), "total"=>($order->total), "descrizione"=> ($food->descrizione), "categoria"=> ($food->categoria), "immagine"=> ($food->immagine) ))
                        @php(sortedInsert($foodOrdinati, $currentFood, 'categoria'))
                    @endforeach

                    {{-- POI LI METTO IN TABELLA A PARTIRE DALLA LISTA ORDINATA --}}



                    @foreach($foodOrdinati as $fornitura)
                        <tr>
                            {{-- <th scope="row" class="d-none d-md-table-cell">{{ $fornitura['food_id'] }}</th> --}}
                            <td>{{ $fornitura['nome'] }}</td>
                            <td>{{ $fornitura['prezzo'] }}€</td>
                            <td class="total">{{$fornitura['total'] }} {{ $fornitura['unita'] }}</td>
                            <td class="d-none d-sm-table-cell">{{ $fornitura['descrizione'] }}</td>
                            <td class="d-none d-sm-table-cell">{{ $fornitura['categoria'] }}</td>

                            <td class="d-none d-sm-table-cell"> <img src="{{URL::asset('img_uploads/'. $fornitura['immagine'])}}" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto"> </td>
                        </tr>
                    @endforeach

                        {{-- logger('Debag forniture riordinate, si spera:') }}
                        {{ logger($foodOrdinati) --}}

                    @else
                        <tr><td colspan="8">Nessuna Fornitura</td></tr>
                    @endif
                    </tbody>
                    <tfoot class="bg-secondary text-white">
                    <tr><th class="text-center" colspan="12">
                            <strong><u>Totale:  {{ $datat->totalOrders() }}€</u></strong>
                        </th>
                    </tr>
                    </tfoot>
                </table>

            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white" id="numTotalePrev">
                        <strong>Dati aggiuntivi</strong>
                    </div>

                    <div class="card-body" id="card-body">
                        <form id="formdatipreventivo">
                            <div class ="row">
                                <div class="form-group col-md-12">
                                    <label for="note"><b>Note:</b></label>
                                    <div id="note">{{ $datat->noteAggiuntive }}</div>
                                </div>
                            </div>
                            <div class ="row">
                                <div class="form-group col-md-6">
                                    <label for="ricarico"><b>Ricarico:</b></label>
                                    <div id="ricarico">{{ $datat->ricarico }}%</div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <button type="button" class="btn btn-danger" id="correggi">Correggi</button>
                        <button type="button" class="btn btn-info" id="generaPdf">Genera PDF</button>
                    </div>
                </div>
            </div>
        </div>


    </div>





@endsection

@section('scripts')
    <script src="{{ asset('js/pdfview.js') }}"></script>
@endsection
{{-- Aggiungere chiamata ajax, in qualche modo i dati --}}
