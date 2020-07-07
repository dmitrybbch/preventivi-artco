@extends('layouts.app')

@section('title')
    Opzioni Preventivo
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
            <h1 class="h2" data-id="{{ $table->id }}">
                @if($table->nomeTavolo)
                    {{ $table->nomeTavolo }}
                @else
                    Prev. {{ $table->id }}
                @endif
            </h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2" id="statusTable" role="group" aria-label="First group">
                    <button type="button" class="btn btn-outline-dark @if($table->stato == 'libero') active @endif"
                            value="0">Modulo
                    </button>
                    <button type="button" class="btn btn-outline-danger @if($table->stato == 'occupato') active @endif"
                            value="1">Compilando
                    </button>
                    <button type="button" class="btn btn-outline-success @if($table->stato == 'servito') active @endif"
                            value="2">Compilato
                    </button>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-5">
                <table class="table table-borderless table-sm" id="foodTable">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="d-none d-md-table-cell">id f.</th>
                        <th scope="col">Quantità</th>
                        <th scope="col">Descrizione</th>
                        <th scope="col">Prezzo (Cad.)</th>
                        <th scope="col">Ricarico</th>
                        <th scope="col">Opzioni</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php ($orders = $table->orders())
                    @if(count($orders))
                        @php($foodOrdinati = array())
                        {{ logger($foodOrdinati) }}

                        {{-- PRIMA LI INSERISCO IN UNA LISTA E LI ORDINO PER 'CATEGORIA'--}}
                        @foreach($orders as $order)
                            @php($food = $order->food())
                            @php($currentFood = array("food_id"=> ($order->food_id), "nome"=>($food->nome), "prezzo"=>($food->prezzo), "unita"=>($food->unita), "amount"=>($order->amount), "add_percent"=>($order->add_percent), "descrizione"=> ($food->descrizione), "capitolo"=> ($food->capitolo), "categoria"=> ($food->categoria), "immagine"=> ($food->immagine) ))
                            @php(array_push($foodOrdinati, $currentFood))
                        @endforeach
                        @php($foodOrdinati = collect($foodOrdinati)->sortBy('categoria')->sortBy('capitolo')->all())

                        {{-- POI LI METTO IN TABELLA A PARTIRE DALLA LISTA ORDINATA --}}

                        @php($capitoloTabella = "Cap_Vuoto_Error")
                        @php($categoriaTabella = "Cat_Vuoto_Error")


                        @foreach($foodOrdinati as $fornitura)
                            @if($capitoloTabella != ($capAttuale = $fornitura['capitolo']))
                                <tr class="table-active">
                                    <th scope="row" colspan="6" class="d-none d-md-table-cell">{{$capAttuale}}</th>
                                </tr>
                                @php($capitoloTabella = $capAttuale)
                            @endif
                            @if($categoriaTabella != ($catAttuale = $fornitura['categoria']))
                                <tr class="thead-light text-white">
                                    <th><i class="fas fa-long-arrow-alt-right "></i></th><th scope="row" colspan="5" class="d-none d-md-table-cell">{{$catAttuale}}</th>
                                </tr>
                                @php($categoriaTabella = $catAttuale)
                            @endif
                            <tr data-capitolo="{{ $fornitura['capitolo'] }}" data-categoria="{{ $fornitura['categoria'] }}">
                                <th scope="row" class="d-none d-md-table-cell">{{ $fornitura['food_id'] }}</th>
                                <td class="amount">
                                    <div class="col-md-8">
                                        <input class="form-control amount" type="number" step="1" name="quantitaTab" value="{{$fornitura['amount'] }}">{{ $fornitura['unita'] }}
                                    </div>
                                </td>
                                <td>{{ $fornitura['descrizione'] }}</td>
                                <td class="total">€ {{$fornitura['prezzo']}}</td>
                                <td class="add_percent ">
                                    <div class="col-md-8">
                                        <input class="form-control add_percent" type="number" step="0.1" name="addTab" value="{{$fornitura['add_percent'] }}"> %
                                    </div>
                                </td>
                                {{--
                                <td class="d-none d-sm-table-cell"><img
                                        src="{{URL::asset('img_uploads/'. $fornitura['immagine'])}}"
                                        class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">
                                </td>
                                --}}
                                <td><i class="fas fa-eraser togliFornitura" style="cursor:pointer"></i></td>
                            </tr>
                        @endforeach



                        {{-- logger('Debag forniture riordinate, si spera:') }}
                        {{ logger($foodOrdinati) --}}


                    @else
                        <tr>
                            <td colspan="9">Nessuna Fornitura</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot class="bg-secondary text-white">
                    <tr>
                        <th class="text-center" colspan="12" id="totaleOrdini">
                            <strong><u>Totale: {{ $table->totalOrders() }}€</u></strong>
                        </th>
                    </tr>
                    </tfoot>
                </table>

            </div>

            <div class="col-md-7">
                <div class="card">

                    <div class="card-header bg-dark text-white">
                        <div class="mb-2 mb-md-0 mr-auto ml-0">
                            <form class="form-inline">
                                <input type="text" id="searchBox" style="display: none;" class="form-control mr-0"
                                       placeholder="Search">
                                <button type="button" id="cartBtn" class="text-white btn btn-secondary"><strong>Forniture</strong></button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body" id="card-body">


                        <table class="table table-sm table-borderless" id="fornitureTable">
                            <tbody>
                            </tbody>
                        </table>

                        <form id="formdatipreventivo">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="noteAggiuntive">Note:</label>
                                    <textarea class="form-control" id="note"
                                              name="note">{{ $table->noteAggiuntive }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="ricarico">Ricarico Tot:</label>
                                    <input class="form-control" id="ricarico" type="number" step="0.10" name="ricarico"
                                           value="{{ $table->ricarico }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="creatoDa">Creato da:</label>
                                    <input class="form-control" id="creatoDa" type="text" name="creatoDa"
                                           value="@if($table->creatoDa){{$table->creatoDa}} @else{{Auth::user()->username }} @endif">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="client">Cliente:</label>
                                    <input list="clienti" class="form-control"  name="client" value="{{ $table->cliente }}"/>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        {{--<button type="button" class="btn btn-danger" id="tgf">Svuota</button> --}}
                        <button type="button" class="btn btn-outline-info" id="salvaDatiAggiuntivi">Salva Dati Aggiuntivi</button>
                        <button type="button" class="btn btn-info" id="anteprima">Anteprima</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">

        </div>

    </div>

    <datalist id="clienti">

        @foreach($clients as $client)
            <option value="{{$client->id}}"> {{$client->nome}}</option>
        @endforeach
    </datalist>
@endsection

@section('scripts')
    <script src="{{ asset('js/table.js') }}"></script>
@endsection
