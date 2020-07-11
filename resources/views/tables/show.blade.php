@extends('layouts.app')

@section('title')
    Preventivi
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
        <h1 class="h2">Preventivi</h1>
        {{--@if(Auth::user()->isAdmin())--}}
        {{--
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="modBtn">Crea/ Cancella/ Rinomina</button>
        </div>
        --}}

    {{--@endif--}}


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

        <table class="table table-sm table-hover table-bordereless table-striped sortable" data-reorderable-rows="true" id="quotesTable" data-sortable="true" style="margin: auto; width: 90%" >
            <thead class="bg-dark text-white">
            <tr>
                <th scope="col" class="d-none d-md-table-cell" style="width: 4%">#</th>
                <th scope="col">Nome Preventivo</th>
                <th scope="col" class="d-none d-sm-table-cell" style="width: 7%">Cliente</th>
                <th style="width: 6%">#OU</th>
                <th style="width: 9%">Totale</th>
                <th scope="col" class="d-none d-sm-table-cell" style="width: 10%">Creato Da</th>
                <th scope="col" style="width: 4%"></th>
            </tr>
            </thead>
            <tbody>
                @foreach($tables as $table)
                    <tr class="" data-id="{{ $table->id }}">
                        <td class="d-none d-md-table-cell"><b>{{ $table->id }}</b></td>
                        <td class="prevTr" style="cursor: pointer">{{ $table->nomeTavolo }}</td>
                        <td>{{ $table->cliente }}</td>
                        <td>{{ $table->countOrders() }}</td>
                        <td>{{ $table->totalOrders() }} €</td>
                        <td>{{ $table->creatoDa }}</td>
                        <td><i class="far fa-trash-alt eliminaPrev" style="cursor: pointer"></i></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</div>

@endsection

@section('scripts')
<script src="{{ asset('js/tables.js') }}"></script>

@endsection
