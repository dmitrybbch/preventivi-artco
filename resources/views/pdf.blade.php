@extends('layouts.app')
@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
            <h1 class="h2" data-id="{{ $datat->id}}">
                @if($datat->nomeTavolo)
                    Anteprima "{{ $datat->nomeTavolo }}"
                @else
                    Anteprima Prev. {{ $datat->id }}"
                @endif
            </h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2" id="statusTable" role="group" aria-label="First group">
                    <b>{{$datat->cliente}}</b> ({{$datat->creatoDa}})
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-9 mx-auto">
                <table class="table table-borderless table-sm" id="foodTable">
                    <!--
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
                    -->
                    <tbody>

                    @php ($orders = $datat->orders())
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
                        @php($totaleCapitolo = 0)
                        @php($totaleCategoria = 0)

                        @foreach($foodOrdinati as $fornitura)
                            @if($capitoloTabella != ($capAttuale = $fornitura['chapter']))
                                <tr class="table-active" id="{{ str_replace(" ", "_",$capAttuale) }}">
                                    <th scope="row" colspan="10" class="d-none d-md-table-cell">{{$capAttuale}}</th>
                                    <th>€ {{$datat->totalPercentAddedChapter($capAttuale)}}</th>
                                </tr>
                                @php($capitoloTabella = $capAttuale)
                                @php($totaleCapitolo = 0)
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
                                <td class="amount">{{$fornitura["amount"]}}</td>
                                <td>{{ $fornitura['description'] }}</td>
                                <td class="total">€ {{$fornitura['cost']}}</td>
                                <td class="add_percent">{{$fornitura["add_percent"]}}</td>
                                {{--
                                <td class="d-none d-sm-table-cell"><img
                                        src="{{URL::asset('img_uploads/'. $fornitura['immagine'])}}"
                                        class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">
                                </td>
                                --}}
                                <td class="totalR"><b>€ {{$fornitura['cost'] * $fornitura['amount'] + $fornitura['cost'] * $fornitura['amount'] * $fornitura['add_percent']/100}}</b></td>
                            </tr>
                        @endforeach

                        {{-- logger('Debag forniture riordinate, si spera:') }}
                        {{ logger($foodOrdinati) --}}

                    @else
                        <tr><td colspan="8">Nessuna Fornitura</td></tr>
                    @endif
                    </tbody>
                    <tfoot class="bg-dark text-white">
                    <tr>
                        <th class="text-left" colspan="2">
                            <strong>Totale:</strong>
                        </th>
                        <td class="text-right" id="totaleOrdini" colspan="20">
                            <strong>{{ $datat->totalOrders()}} €</strong>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="2">
                            <strong>Totale + Ricarico:</strong>
                        </th>
                        <th class="text-right" colspan="12" id="totaleConRicarico">
                            <strong>{{ $datat->totalPercentAdded() }} €</strong>
                        </th>
                    </tr>
                    </tfoot>
                </table>

            </div>

            <div class="col-md-3">
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
