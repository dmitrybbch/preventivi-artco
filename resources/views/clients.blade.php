@extends('layouts.app')

@section('title')
    Clienti
@endsection

@section('content')

    <div class="container-fluid" xmlns="http://www.w3.org/1999/html">
        <div class="mb-3 border-bottom">
            <h1 class="h2">Clienti</h1>
        </div>
        <input class="form-control mb-3" id="searchBoxClienti" type="search" placeholder="Cerca" aria-label="Search">
        <div class="row">
            <div class="col-md-8" >
                <table class="table table-striped sortable" id="clientsTable" data-sortable="true">
                    <thead class="bg-secondary text-white">
                    <tr>
                        <th scope="col" class="d-none d-md-table-cell">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col" class="d-none d-sm-table-cell">Email</th>
                        <th scope="col" class="d-none d-sm-table-cell">Telefono</th>
                        <th scope="col" class="d-none d-sm-table-cell">Indirizzo</th>
                        <th scope="col" class="d-none d-sm-table-cell">Città</th>
                        <td class="d-none d-sm-table-cell" style="width: 6%"></td>
                    </tr>
                    </thead>
                    <tbody>
                    @if( count($prevs) )
                        @foreach($prevs as $prev)
                            <tr data-id="{{ $prev->id }}">
                                <th> {{$prev->id}}</th>
                                <td class="clientQuotes" style="cursor: pointer"> <b>{{$prev->nome}}</b> </td>
                                <td> {{$prev->email}}</td>
                                <td> {{$prev->telefono}}</td>
                                <td> {{$prev->indirizzo}}</td>
                                <td> {{$prev->capCittaProv}}</td>
                                <td>
                                    <i class="far fa-trash-alt deleteClient" style="font-size: 20px;cursor: pointer"></i>
                                    <i class="fas fa-tags clientQuotes" style="font-size: 20px;cursor: pointer"></i>
                                    <!--&nbsp;<i class="far fa-edit" style="font-size: 20px;cursor: pointer"></i>-->
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">Nessun Cliente :(</td>
                        </tr>
                    @endif

                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white col-md-12 font-weight-bold">
                        Nuovo Cliente
                    </div>
                    <div class="card-body">
                        <form id="formCliente">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nome">Nome</label>
                                    <input class="form-control" id="nome" type="text" name="nome">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Indirizzo</label>
                                    <input class="form-control" id="indirizzo" type="text" name="indirizzo" placeholder="Via Esempio 12">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="descrizione">Città</label>
                                    <input type="text" class="form-control" id="descrizione" name="capCittaProv" placeholder="es. 20202 Città (PD)">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Email</label>
                                    <input class="form-control" id="email" type="text" name="email">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nome">Telefono</label>
                                    <input class="form-control" id="telefono" type="text" name="telefono">
                                </div>
                            </div>


                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-info" onclick="newClient()">
                            Crea
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal per modifica cliente TODO: da riformare come modifica cliente invece che fornitura-->
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
                        <form id="newClientForm">
                            <div class="form-group row">
                                <label for="idModal" class="col-md-4 col-form-label text-md-right">Id</label>
                                <div class="col-md-3">
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
                                    <input list="unitaMisura" id="unitaModal" class="form-control"/>
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


    <datalist id="unitaMisura">
        <option value="crp">
        <option value="n">
        <option value="mq">
        <option value="ml">
    </datalist>

@endsection

@section('scripts')
    <script src="{{ asset('js/client.js') }}"></script>
    <script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
@endsection
