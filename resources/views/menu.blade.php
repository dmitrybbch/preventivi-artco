@extends('layouts.app')

@section('title')
Prodotti
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
                <thead class="bg-secondary text-white col-md-7">
                <tr>
                    <th scope="col" colspan ="7" class="d-md-table-cell">Ultime Inserite (aggiornare per metterle in tabella)</th>
                </tr>
                </thead>
            </table>

            <table class="table table-borderless" id="foodTable" id="table" data-sortable="true">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th scope="col" class="d-none d-md-table-cell">id</th><th scope="col">Nome</th><th scope="col">Prezzo</th><th scope="col" class="d-none d-sm-table-cell">Descrizione</th><th scope="col" class="d-none d-sm-table-cell">Capitolo</th><th scope="col" class="d-none d-sm-table-cell">Categoria</th><th scope="col" class="d-none d-sm-table-cell">Immagine</th><th scope="col">Opzioni</th>
                    </tr>
                </thead>
                <tbody>
                @if( count($foods) )
                    @php($capitoloTabella = "nuffin")
                    @foreach($foods->sortBy('capitolo') as $food)
                        @if($capitoloTabella != ($capAttuale = explode(" - ", $food->capitolo)[0]))
                            <thead class="thead-light text-white" id={{$capAttuale}}>
                                <tr>
                                    <th scope="row" colspan="7" class="d-none d-md-table-cell">{{ $capAttuale }}</th>
                                </tr>
                            </thead>
                            @php($capitoloTabella = $capAttuale)
                        @endif
                        <tr><th scope="row" class="d-none d-md-table-cell">{{ $food->id }}</th>
                            <td>{{ $food->nome }}</td>
                            <td>€ {{ $food->prezzo }}  @if($food->unita) x {{ $food->unita }}@endif </td>
                            <td class="d-none d-sm-table-cell">{{ $food->descrizione }}</td>
                            <td class="d-none d-sm-table-cell">{{ $food->capitolo }}</td>
                            <td class="d-none d-sm-table-cell">{{ $food->categoria }}</td>
                            <td class="d-none d-sm-table-cell"> <img src="{{URL::asset('img_uploads/'. $food->immagine)}}" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto"></td>
                            <td><button type="button" class="btn btn-outline-danger mr-2"><i class="far fa-trash-alt"></i></button><button type="button" class="btn btn-outline-info"><i class="far fa-edit"></i></button></td></tr>
                    @endforeach
                @else
                <tr><td colspan="7">Nessun Prodotto</td></tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary text-white col-md-12">
                    Aggiungi una fornitura
                </div>
                <div class="card-body">
                <form id="form">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="nome">Nome</label>
                            <input class="form-control" id="nome" type="text" name="nome">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="prezzo">Prezzo</label>
                            <input class="form-control" id="prezzo" type="number" step="0.10" name="prezzo" placeholder="Prezzo unitario">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="unita">Unità:</label>
                            <input list="unitaMisura" id="unita" class="form-control"  name="unita" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="descrizione">Descrizione</label>
                            <textarea class="form-control" id="descrizione" name="descrizione"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="capitolo">Capitolo:</label>
                                <input list="capitoli" class="form-control"  name="capitolo" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="categoria">Categoria:</label>
                                <input list="categorie" class="form-control" name="categoria" />
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="immagine">Immagine</label><br>
                            <input type="file" name="immagine" id="immagine" name="immagine" />
                        </div>
                    </div>

                </form>
                </div>
                <div class="card-footer">
                    <button type="button" onclick="newFood()" class="btn btn-info">
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
                            <label for="idModal" class="col-md-4 col-form-label text-md-right">{{ __('Id') }}</label>
                            <div class="col-md-3">
                                <input type="text" readonly class="form-control-plaintext" id="idModal" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nomeModal" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>
                            <div class="col-md-6">
                                <input class="form-control" id="nomeModal" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="prezzoModal" class="col-md-4 col-form-label text-md-right">{{ __('Prezzo') }}</label>
                            <div class="col-md-6">
                                <input class="form-control"id="prezzoModal" type="number" step="0.10">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="unitaModal" class="col-md-4 col-form-label text-md-right">{{ __('Unità') }}</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" id="unitaModal"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="descrizioneModal" class="col-md-4 col-form-label text-md-right">Descrizione</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" id="descrizioneModal"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="capitoloModal" class="col-form-label col-md-4 text-md-right">Capitolo:</label>
                            <div class="col-sm-6">
                                <input id="capitoloModal" class="form-control" list="capitoli"/>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="categoriaModal" class="col-md-4 col-form-label text-md-right">Categoria:</label>
                            <div class="col-sm-6">
                                <input id="categoriaModal" class="form-control" list="categorie"/>
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
<script src="{{ asset('js/food.js') }}"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
@endsection
