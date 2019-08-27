@extends('layouts.app')

@section('title')
Menu
@endsection

@section('content')

<div class="container-fluid">
    <div class="mb-3 border-bottom">
        <h1 class="h2">Menù</h1>
    </div>
    <input class="form-control mb-3" id="searchBox" type="search" placeholder="Search" aria-label="Search">
    <div class="row">
        <div class="col-md-8">
            <table class="table table-striped" id="foodTable">
                <thead>
                    <tr><th scope="col" class="d-none d-md-table-cell">id Prodotto</th><th scope="col">Nome Prodotto</th><th scope="col">Prezzo</th><th scope="col" class="d-none d-sm-table-cell">Descrizione</th><th scope="col">Opzioni</th></tr>
                </thead>
                <tbody>
                @if( count($foods) )
                    @foreach($foods as $food)
                        <tr><th scope="row" class="d-none d-md-table-cell">{{ $food->id }}</th><td>{{ $food->nome }}</td><td>{{ $food->prezzo }}</td><td class="d-none d-sm-table-cell">{{ $food->descrizione }}</td><td><button type="button" class="btn btn-outline-danger mr-2"><i class="far fa-trash-alt"></i></button><button type="button" class="btn btn-outline-info"><i class="far fa-edit"></i></button></td></tr>
                    @endforeach
                @else
                <tr><td colspan="5">Nessun Prodotto</td></tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                Aggiungi un prodotto
                </div>
                <div class="card-body">
                <form>
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
                        <textarea class="form-control" id="descrizione"></textarea>
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
@endsection
