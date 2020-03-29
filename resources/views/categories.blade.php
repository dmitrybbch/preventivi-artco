@extends('layouts.app')

@section('title')
    Categorie
@endsection

@section('content')

    <div class="container-fluid">
        <div class="mb-3 border-bottom">
            <h1 class="h2">Categorizzazione delle Forniture</h1>
        </div>

        <div class="row">
            <div class="col-md-5">
                <form id="categoriaForm">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control"  id="creaCategoria" name="name" placeholder="Nuova Categoria" aria-label="Nuova Categoria" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" onclick="newCat()">Crea</button>
                        </div>
                    </div>
                </form>
                <table class="table table-striped" id="catTable" data-sortable="true">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th scope="col" class="d-none d-md-table-cell">id</th>
                            <th scope="col">Categorie</th><th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @if( count($cats) )
                        @foreach($cats->sortBy('name') as $cat)
                            <tr>
                                <th scope="row" class="d-none d-md-table-cell">{{ $cat->id }}</th>
                                <td>{{ $cat->name }} </td>
                                <td class="text-md-right"> <button type="button" id="deleteCat" class="btn btn-outline-danger mr-2"><i class="far fa-trash-alt"></i></button></td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="3">Nessuna Categoria</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- Tabella sezioni -->
            <div class="col-md-5">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Nuova Sezione" aria-label="Nuova Sezione" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">Crea</button>
                    </div>
                </div>

                <table class="table table-striped" id="foodTable" id="table" data-sortable="true">
                    <thead class="bg-secondary text-white">
                    <tr>
                        <th scope="col" class="d-none d-md-table-cell">id</th>
                        <th scope="col">Sezioni</th><th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( count($secs) )
                        @foreach($secs->sortBy('name') as $sec)
                            <tr>
                                <th scope="row" class="d-none d-md-table-cell">{{ $sec->id }}</th>
                                <td>{{ $sec->name }} </td>
                                <td class="text-md-right"> <button type="button" id="deleteSec" class="btn btn-outline-danger mr-2"><i class="far fa-trash-alt"></i></button></td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="3">Nessuna Sezione</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <!--
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white col-md-12">
                        Aggiungi una categoria
                    </div>
                    <div class="card-body">
                        <form id="form">

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="nome">Nome</label>
                    <input class="form-control" id="nome" type="text" name="nome">
                </div>
                <div class="form-group col-md-6">
                    <label for="prezzo">Prezzo</label>
                    <input class="form-control" id="prezzo" type="number" step="0.10" name="prezzo" placeholder="Prezzo unitario">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="nome">Unità</label>
                    <input class="form-control" id="unita" type="text" name="unita" placeholder="Unità di misura della fornitura. Es: kg, m, mquadri, mcubi, ecc..">
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
                    <label for="categoria">Categoria</label>
                    <textarea class="form-control" id="categoria" name="categoria" placeholder="NOTA: Inserire nel formato 'Categoria - Subcategoria'"></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="immagine">Immagine</label><br>
                    <input type="file" name="immagine" id="immagine" name="immagine" />
                </div>
            </div>

        </form>
    </div>
    <div class="card-footer">
        <button type="button" onclick="newFood()" class="btn btn-info"> Crea
            </button>
        </div>
    </div>
</div>
-->

        </div>

        <!-- modal per modifica prodotto
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
                                <div class="col-md-6">
                                    <input type="text" readonly class="form-control-plaintext" id="idModal" value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nomeModal" class="col-md-4 col-form-label text-md-right">Nome</label>
                                <div class="col-md-6">
                                    <input class="form-control" id="nomeModal" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="prezzoModal" class="col-md-4 col-form-label text-md-right">Prezzo</label>
                                <div class="col-md-6">
                                    <input class="form-control"id="prezzoModal" type="number" step="0.10">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="unitaModal" class="col-md-4 col-form-label text-md-right">Unità</label>
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
                                <label for="categoriaModal" class="col-md-4 col-form-label text-md-right">Categoria</label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" id="categoriaModal"></textarea>
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
        </div> -->

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/categories.js') }}"></script>
    <script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
@endsection
