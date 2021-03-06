@extends('layouts.app')

@section('title')
Forniture
@endsection

@section('content')

<div class="container-fluid" xmlns="http://www.w3.org/1999/html">
    <div class="mb-3 border-bottom">
        <h1 class="h2">Forniture</h1>
    </div>
    <input class="form-control mb-3" id="searchBox" type="search" placeholder="Cerca" aria-label="Search">
    <div class="row">
        <div class="col-md-8" >
            <table class="table table-borderless" id="inseritiTable" data-sortable="true">
                <thead class="bg-secondary text-white ">
                <tr>
                    <th scope="col" colspan="8" class="d-md-table-cell">Ultime Inserite</th>
                </tr>
                </thead>
            </table>

            <table class="table table-borderless table-sm" id="foodTable" id="table" data-sortable="true">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th scope="col" class="d-none d-md-table-cell">#</th>
                        <th scope="col">Nome</th><th scope="col">Prezzo</th>
                        <th scope="col" class="d-none d-sm-table-cell">Descrizione</th>
                        <th scope="col" class="d-none d-sm-table-cell">Immagine</th>
                        <td style="width: 9%"></td>
                    </tr>
                </thead>
                <tbody>
                @if( count($foods) )
                    @php($capitoloTabella = "Cap_Vuoto_Error")
                    @php($categoriaTabella = "Cat_Vuoto_Error")
                    @php($foods = collect($foods)->sortBy('category')->sortBy('chapter')->all())
                    @foreach($foods as $food)

                        @if($capitoloTabella != ($capAttuale = $food->chapter))
                            <tr id="{{ str_replace(" ", "_",$capAttuale) }}" class="table-active">
                                <th scope="row" colspan="8" class="d-none d-md-table-cell">{{$capAttuale}}</th>
                            </tr>
                            @php($capitoloTabella = $capAttuale)
                        @endif
                        @if($categoriaTabella != ($catAttuale = $food->category))
                            <tr id="{{ str_replace(" ", "_",($capAttuale.$catAttuale)) }}" class="thead-light text-white"> {{--id per facilitare l'inserimento--}}
                                <th><i class="fas fa-long-arrow-alt-right "></i></th><th scope="row" colspan="7" class="d-none d-md-table-cell">{{$catAttuale}}</th>
                            </tr>
                            @php($categoriaTabella = $catAttuale)
                        @endif
                        <tr data-capitolo="{{ $food->chapter }}" data-categoria="{{ $food->category }}" @if($food->order) class="font-weight-bold" @endif>
                            <th scope="row" class="d-none d-md-table-cell">{{ $food->id }}</th>
                            <td>{{ $food->name }}</td>
                            <td>€ {{ $food->cost }}  @if($food->unit) x {{ $food->unit }}@endif </td>
                            <td class="d-none d-sm-table-cell">{{ $food->description }}</td>
                            <td class="d-none d-sm-table-cell"> <img src="{{URL::asset('img_uploads/'. $food->image)}}" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto"></td>
                            <td><i class="far fa-trash-alt" style="font-size: 20px;cursor: pointer"></i>&nbsp;&nbsp;&nbsp;<i class="far fa-edit" style="font-size: 20px;cursor: pointer"></i>&nbsp;&nbsp;&nbsp;<i class="far fa-copy" style="font-size: 20px;cursor: pointer"></i></td>
                        </tr>
                    @endforeach
                @else
                <tr><td colspan="8">Nessuna Fornitura</td></tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary text-white col-md-12">
                    <b>Aggiungi una fornitura</b>
                </div>
                <div class="card-body">
                <form id="form">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="chapter">Capitolo:</label>
                            <input list="capitoli" class="form-control"  name="chapter" id="chapterForm"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="category">Categoria:</label>
                            <input list="categorie" class="form-control" name="category" id="categoryForm"/>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">Nome</label>
                            <input class="form-control" id="name" type="text" name="name">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cost">Prezzo</label>
                            <input class="form-control" id="cost" type="number" step="0.10" name="cost" placeholder="Costo unitario">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="unit">Unità:</label>
                            <input list="unitaMisura" id="unit" class="form-control"  name="unit" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="description">Descrizione</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="image">Immagine</label><br>
                            <input type="file" class="form-control-file" id="image" name="image" />
                        </div>
                        <div class="form-group col-md-6">
                            <input type="checkbox" class="form-check-input" id="order" name="order" />&nbsp;
                            <label class="form-check-label font-weight-bold" for="image">Lista ordini (grassetto)</label>
                        </div>
                    </div>

                </form>
                </div>
                <div class="card-footer">
                    <button type="button" onclick="newProvision()" class="btn btn-info">
                        Crea
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal per modifica prodotto -->
    <div class="modal" tabindex="-1" role="dialog" id="foodModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifica</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="form-group row">
                            <label for="idModal" class="col-md-4 col-form-label text-md-right">Id</label>
                            <div class="col-md-3">
                                <input type="text" readonly class="form-control-plaintext" id="idModal" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nameModal" class="col-md-4 col-form-label text-md-right">Nome</label>
                            <div class="col-md-6">
                                <input class="form-control" id="nameModal" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costModal" class="col-md-4 col-form-label text-md-right">Prezzo</label>
                            <div class="col-md-6">
                                <input class="form-control"id="costModal" type="number" step="0.10">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="unitModal" class="col-md-4 col-form-label text-md-right">Unità</label>
                            <div class="col-sm-6">
                                <input list="unitaMisura" id="unitModal" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="decriptionModal" class="col-md-4 col-form-label text-md-right">Descrizione</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" id="descriptionModal"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="chapterModal" class="col-form-label col-md-4 text-md-right">Capitolo:</label>
                            <div class="col-sm-6">
                                <input id="chapterModal" class="form-control" list="capitoli"/>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="categoryModal" class="col-md-4 col-form-label text-md-right">Categoria:</label>
                            <div class="col-sm-6">
                                <input id="categoryModal" class="form-control" list="categorie"/>
                            </div>

                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" id="btnSave" class="btn btn-primary">Salva modifiche</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

</div>

<datalist id="capitoli">
    <option value="OPERE EDILI INTERNE">
    <option value="OPERE DA GESSINO E PITTURA">
    <option value="OPERE DA FALEGNAME E PARETI DIVISORIE INTERNE">
    <option value="SERRAMENTI DI SICUREZZA ESTERNI ED INTERNI">
    <option value="ARREDI SU MISURA">
    <option value="ARREDI DI SERIE E ATTESA">
    <option value="SEDUTE E ACCESSORI VARI">
    <option value="VETROFANIE INTERNE ED ESTERNE TENDAGGI INSEGNE">
    <option value="PRATICHE AMMINISTRATIVE E PROGETTI IMPIANTI">
    <option value="IMPIANTO ELETTRICO">
    <option value="CORPI ILLUMINANTI">
    <option value="RETE STRUTTURATA E TRASMISSIONE DATI">
    <option value="IMPIANTO DI CLIMATIZZAZIONE">
    <option value="IMPIANTO DI ALLARME,  E TVCC">
    <option value="MOVIMENTAZIONE MATERIALI">

</datalist>

<datalist id="categorie">
    <option value="Impianto di cantiere">
    <option value="Assistenze">
    <option value="Demolizioni">
    <option value="Pavimentazione">
    <option value="Varie">
    <option value="Controsoffitto">
    <option value="Velette">
    <option value="Pareti in cartongesso">
    <option value="Tinteggiatura interna">
    <option value="Porte interne, pedana, rivestimenti, mensole, pareti, battiscopa">
    <option value="Serramenti di sicurezza esterni">
    <option value="Serramenti sicurezza ingresso / interni">
    <option value="Box cassa operatore">
    <option value="Postazioni lavoro">
    <option value="Cassettiera casse">
    <option value="Armadi contenitori">
    <option value="Ufficio direzionale e consulenza">
    <option value="Sedute operative casse">
    <option value="Sedute visitatori">
    <option value="Sedute attesa">
    <option value="Sgabelli">
    <option value="Accessori">
    <option value="Distribuzione generale impianto su tubazioni in PVC pesanti esistenti">
    <option value="Predisposizione per impianti tecnologici">
    <option value="Corpi illuminanti a fluorescenza">
    <option value="Impianto di climatizzazione">
    <option value="Impianto antintrusione">
    <option value="Materiali nuovi">
    <option value="Impianto di video controllo e videoregistrazione">
    <option value="Materiali nuovi">

</datalist>

<datalist id="unitaMisura">
    <option value="crp">
    <option value="n">
    <option value="mq">
    <option value="ml">
</datalist>
@endsection

@section('scripts')
<script src="{{ asset('js/provision.js') }}"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
@endsection
