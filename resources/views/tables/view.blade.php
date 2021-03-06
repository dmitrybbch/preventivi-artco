@extends('layouts.app')

@section('title')
    Opzioni Preventivo
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
            <h1 class="h2" data-id="{{ $table->id }}">
                @if($table->nomeTavolo)     {{ $table->nomeTavolo }}
                @else                       Prev. {{ $table->id }}
                @endif
            </h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2" id="statusTable" role="group" aria-label="First group">
                    <button type="button" class="btn btn-outline-dark @if($table->stato == 'libero') active @endif"
                            value="0">In Corso</button>
                    <button type="button" class="btn btn-outline-success @if($table->stato == 'occupato') active @endif"
                            value="1">Template</button>
                    <button type="button" class="btn btn-outline-danger @if($table->stato == 'servito') active @endif"
                            value="2">Concluso</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <table class="table table-borderless table-sm" id="foodTable">
                    <thead class="bg-dark text-white font-weight-bold">
                        <tr>
                            <th scope="col" class="d-none d-md-table-cell">#</th>
                            <td>Quantità</td>
                            <td>Descrizione</td>
                            <td>Prezzo</td>
                            <td>Ricarico</td>
                            <td>Parziale</td>
                            <td style="padding-right: 20px"></td>
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
                            @php($currentFood = array("provision_id"=> ($order->provision_id), "name"=>($food->name), "cost"=>($food->cost), "unit"=>($food->unit), "amount"=>($order->amount), "add_percent"=>($order->add_percent), "description"=> ($food->description), "chapter"=> ($food->chapter), "category"=> ($food->category), "image"=> ($food->image) ))
                            @php(array_push($foodOrdinati, $currentFood))
                        @endforeach
                        @php($foodOrdinati = collect($foodOrdinati)->sortBy('category')->sortBy('chapter')->all())

                        {{-- POI LI METTO IN TABELLA A PARTIRE DALLA LISTA ORDINATA --}}

                        @php($capitoloTabella = "Cap_Vuoto_Error")
                        @php($categoriaTabella = "Cat_Vuoto_Error")


                        @foreach($foodOrdinati as $fornitura)
                            @if($capitoloTabella != ($capAttuale = $fornitura['chapter']))
                                <tr class="table-active" id="{{ str_replace(" ", "_",$capAttuale) }}">
                                    <th scope="row" colspan="10" class="d-none d-md-table-cell">{{$capAttuale}}</th>

                                </tr>
                                @php($capitoloTabella = $capAttuale)
                            @endif
                            @if($categoriaTabella != ($catAttuale = $fornitura['category']))
                                <tr class="thead-light text-white" id="{{ str_replace(" ", "_",($capAttuale.$catAttuale)) }}">
                                    <th><i class="fas fa-long-arrow-alt-right "></i></th>
                                    <th scope="row" colspan="10" class="d-none d-md-table-cell">{{$catAttuale}}</th>
                                </tr>
                                @php($categoriaTabella = $catAttuale)
                            @endif
                            {{-- PER LE RIGHE DEI PRODOTTI inserisco --}}
                            <tr data-capitolo="{{ $fornitura['chapter'] }}" data-categoria="{{ $fornitura['category'] }}">
                                <th scope="row" class="d-none d-md-table-cell">{{ $fornitura['provision_id'] }}</th>
                                <td class="amount">
                                    <div class="col-md-8">
                                        <input class="form-control amount" type="number" step="1" name="quantitaTab" value="{{$fornitura['amount'] }}">
                                    </div>
                                </td>
                                <td>{{ $fornitura['description'] }}</td>
                                <td class="total">€ {{$fornitura['cost']}}</td>
                                <td class="add_percent">
                                    <div class="col-md-9">
                                        <input class="form-control add_percent" type="number" step="0.1" name="addTab" value="{{$fornitura['add_percent'] }}">
                                    </div>
                                </td>
                                {{--
                                <td class="d-none d-sm-table-cell"><img
                                        src="{{URL::asset('img_uploads/'. $fornitura['immagine'])}}"
                                        class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">
                                </td>
                                --}}
                                <td class="totalR">€ {{$fornitura['cost'] * $fornitura['amount'] + $fornitura['cost'] * $fornitura['amount'] * $fornitura['add_percent']/100}}</td>
                                <td><i class="fas fa-eraser togliFornitura" style="cursor:pointer"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                        @endforeach



                        {{-- logger('Debag forniture riordinate, si spera:') }}
                        {{ logger($foodOrdinati) --}}


                    @else
                        <tr class="bg-light">
                            <td colspan="20"><b>Fare click su "Forniture" nella scheda a destra per riempire il preventivo.</b></td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot class="bg-dark text-white">
                    <tr>
                        <th class="text-left" colspan="2">
                            <strong>Totale:</strong>
                        </th>
                        <td class="text-right font-weight-bold" id="totaleOrdini" colspan="20">
                            {{ $table->totalOrders()}} €
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="2">
                            <strong>Totale + Ricarico:</strong>
                        </th>
                        <th class="text-right font-weight-bold" colspan="12" id="totaleConRicarico">
                            {{ $table->totalPercentAdded() }} €
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
                                    <label for="client_id">Cliente:</label>
                                    {{--<input type="hidden" name="client_id" value="{{ $table->client_id }}">--}}
                                    <input list="clienti" class="form-control"  name="client_id" value="{{ $table->client_id }}"/>
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
