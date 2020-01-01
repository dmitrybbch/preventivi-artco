@extends('layouts.app')

@section('title')
Prodotti
@endsection

@section('content')

<div class="container-fluid">
    <div class="mb-3 border-bottom">
        <h1 class="h2">Prodotti</h1>
    </div>
    <input class="form-control mb-3" id="searchBox" type="search" placeholder="Search" aria-label="Search">
    <div class="row">
        <div class="col-md-8">
            <table class="table table-striped" id="foodTable" id="table"
                data-sortable="true"
            >
                <thead class="bg-secondary text-white">
                <tr><th scope="col" class="d-none d-md-table-cell">id</th><th scope="col">Nome Prodotto</th><th scope="col">Prezzo</th><th scope="col" class="d-none d-sm-table-cell">Descrizione</th><th scope="col" class="d-none d-sm-table-cell">Categoria</th><th scope="col" class="d-none d-sm-table-cell">Sub-Categoria</th><th scope="col" class="d-none d-sm-table-cell">Immagine</th><th scope="col">Opzioni</th></tr>                </thead>
                <tbody>
                @if( count($foods) )
                    @foreach($foods->sortBy('categoria') as $food)
                        <tr><th scope="row" class="d-none d-md-table-cell">{{ $food->id }}</th>
                            <td>{{ $food->nome }}</td><td>{{ $food->prezzo }}</td>
                            <td class="d-none d-sm-table-cell">{{ $food->descrizione }}</td>
                            <td class="d-none d-sm-table-cell">{{ $food->categoria }}</td>
                            <td class="d-none d-sm-table-cell">{{ $food->subcategoria }}</td>
                            <td class="d-none d-sm-table-cell"> <img src="{{URL::asset('img_uploads/'. $food->immagine)}}" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto"></td>
                            <td><button type="button" class="btn btn-outline-danger mr-2"><i class="far fa-trash-alt"></i></button><button type="button" class="btn btn-outline-info"><i class="far fa-edit"></i></button></td></tr>
                    @endforeach
                @else
                <tr><td colspan="5">Nessun Prodotto</td></tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                Aggiungi una fornitura
                </div>
                <div class="card-body">
                <form id="form">
                    @csrf
                    <div class="form-group">
                        <label for="nome">{{ __('Nome') }}</label>
                       <input class="form-control" id="nome" type="text" name="nome">
                    </div>

                    <div class="form-group">
                        <label for="prezzo">{{ __('Prezzo') }}</label>
                        <input class="form-control" id="prezzo" type="number" step="0.10" name="prezzo">
                    </div>

                    <div class="form-group">
                        <label for="descrizione">{{ __('Descrizione') }}</label>
                        <textarea class="form-control" id="descrizione" name="descrizione"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="categoria">{{ __('Categoria') }}</label>
                        <textarea class="form-control" id="categoria" name="categoria"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="categoria">{{ __('Sub-Categoria') }}</label>
                        <textarea class="form-control" id="subcategoria" name="subcategoria"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="immagine">{{ __('Immagine') }}</label><br>
                        <input type="file" name="immagine" id="immagine" name="immagine" />
                    </div>

                    <button type="button" onclick="newFood()" class="btn btn-primary">
                        {{ __('Crea') }}
                    </button>
                </form>
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
                            <div class="col-md-6">
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
                            <label for="descrizioneModal" class="col-md-4 col-form-label text-md-right">{{ __('Descrizione') }}</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" id="descrizioneModal"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="categoriaModal" class="col-md-4 col-form-label text-md-right">{{ __('Categoria') }}</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" id="categoriaModal"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="subcategoriaModal" class="col-md-4 col-form-label text-md-right">{{ __('Sub-Categoria') }}</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" id="subcategoriaModal"></textarea>
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
@endsection

@section('scripts')
<script src="{{ asset('js/food.js') }}"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
@endsection
