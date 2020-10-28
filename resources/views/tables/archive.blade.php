@extends('layouts.app')

@section('title')
    Archivi
@endsection

@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
            <h1 class="h2">Archivio Preventivi Conclusi</h1>
        </div>

        <div class="panel panel-default rounded-sm">
            <div class="alert alert-danger" role="alert" style="margin: auto; width: 90%">
                <i class="fas fa-eye"></i> ATTENZIONE: Questi preventivi sono conclusi e quindi archiviati. Non andrebbero modificati. <i class="fas fa-eye"></i>
            </div>
            <br>
            <table class="table table-sm table-hover table-bordereless sortable" data-reorderable-rows="true" id="quotesTable" data-sortable="true" style="margin: auto; width: 90%" >
                <thead class="bg-danger text-white">
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
                    <tr class="text-danger" data-id="{{ $table->id }}">
                        <td class="d-none d-md-table-cell"><b>{{ $table->id }}</b></td>
                        <td class="prevTr" style="cursor: pointer">{{ $table->nomeTavolo }}</td>
                        <td><b>{{ $table->cliente }}</b></td>
                        <td>{{ $table->countOrders() }}</td>
                        <td>{{ $table->totalPercentAdded() }} €</td>
                        <td>{{ $table->creatoDa }}</td>
                        <td>{{ $table->updated_at }}</td>
                        <td class=""><i class="far fa-trash-alt eliminaPrev" style="cursor: pointer"></i></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/archive.js') }}"></script>
@endsection
