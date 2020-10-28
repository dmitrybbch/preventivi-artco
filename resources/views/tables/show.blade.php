@extends('layouts.app')

@section('title')
    Preventivi
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
        <h1 class="h2">Preventivi</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2" id="switchTemplateIncorso" role="group" aria-label="First group">
                <button type="button" id="incorso" class="btn btn-outline-dark active" value="0">In Corso</button>
                <button type="button" id="template" class="btn btn-outline-success" value="2">Template</button>
            </div>
        </div>
    </div>

    <div class="panel panel-default rounded-sm">

        <form id="formNuovoPreventivo" style="width: 90%; margin: auto">
            <div class="input-group input-group-sm mb-3 col-sm-12">
                <input type="text" id="nomePrevInput" name="nomePrev" class="form-control" placeholder="Nuovo Preventivo" aria-label="Nome Preventivo" aria-describedby="basic-addon2">
                {{--<input type="text" class="form-control" aria-label="Cliente">--}}
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" onclick="newTable()"><i class="fas fa-plus"> Aggiungi </i></button>
                </div>
            </div>
        </form>

        <table class="table table-sm table-hover table-bordereless sortable" data-reorderable-rows="true" id="quotesTable" data-sortable="true" style="margin: auto; width: 90%" >
            <thead class="bg-secondary text-white">
                <tr>
                    <th scope="col" class="d-none d-md-table-cell" style="width: 4%">#</th>
                    <th scope="col" style="cursor:pointer">Nome Preventivo</th>
                    <th scope="col" class="d-none d-sm-table-cell" style="width: 10%; cursor: pointer">Cliente</th>
                    <th style="width: 8%; cursor: pointer">Forniture</th>
                    <th style="width: 9%; cursor: pointer">Totale</th>
                    <th scope="col" class="d-none d-sm-table-cell" style="width: 5%; cursor: pointer">Autore</th>
                    <th scope="col" style="width: 13%; cursor: pointer">Modificato</th>
                    <th scope="col" style="width: 4%"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tables as $table)
                    @if($table->stato == 'libero')
                        <tr class="righe-in-corso" data-id="{{ $table->id }}">
                    @elseif($table->stato == 'occupato')
                        <tr class="righe-template" style="display: none" data-id="{{ $table->id }}">
                    @endif
                        <td class="d-none d-md-table-cell"><b>{{ $table->id }}</b></td>
                        <td class="prevTr" style="cursor: pointer">{{ $table->nomeTavolo }}</td>
                        <td><b>{{ $table->cliente }}</b></td>
                        <td>{{ $table->countOrders() }}</td>
                        <td>{{ $table->totalPercentAdded() }} €</td>
                        <td>{{ $table->creatoDa }}</td>
                        <td>{{ $table->updated_at }}</td>
                        <td><i class="far fa-trash-alt eliminaPrev" style="cursor: pointer"></i></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div>

</div>

@endsection

@section('scripts')
<script src="{{ asset('js/tables.js') }}"></script>

@endsection
