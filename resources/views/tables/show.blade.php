@extends('layouts.app')

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

        <form id="formNuovoPreventivo">
            <div class="input-group input-group-sm mb-3 col-sm-12">
                <input type="text" id="nomePrevInput" name="nomePrev" class="form-control" placeholder="Nuovo Preventivo" aria-label="Nome Preventivo" aria-describedby="basic-addon2">
                {{--<input type="text" class="form-control" aria-label="Cliente">--}}
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" onclick="newTable()"><i class="fas fa-plus"> Aggiungi </i></button>
                </div>
            </div>
        </form>

        <table class="table table-sm table-hover table-bordereless table-striped" id="quotesTable" data-sortable="true">
            <thead class="bg-secondary text-white">
            <tr>
                <th scope="col" class="d-none d-md-table-cell">id</th>
                <th scope="col">Nome Preventivo</th>
                <th scope="col" class="d-none d-sm-table-cell">Verso</th>
                <th scope="col" class="d-none d-sm-table-cell">Creato Da</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
                @foreach($tables as $table)
                    <tr class="prevTr" style="cursor: pointer" data-id="{{ $table->id }}">
                        <td class="d-none d-md-table-cell">{{ $table->id }}</td>
                        <td>{{ $table->nomeTavolo }}</td>
                        <td>{{ $table->cliente }}</td>
                        <td></td>
                        <td class="text-right"><!-- <button type="button" class="btn btn-sm btn-outline-danger mr-2"><i class="far fa-trash-alt"> Cancella </i></button>--> </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


{{--
<div class="row" id="rowone">

  @foreach($tables as $table)
  @if($table->stato == 'libero')
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">

              @switch($table->stato)
                  @case('occupato')
                  <div class="card text-white bg-danger" data-id="{{ $table->id }}" style="cursor: pointer">
                      @break
                      @case('servito')
                      <div class="card text-white bg-success" data-id="{{ $table->id }}" style="cursor: pointer">
                          @break
                          @default
                          <div class="card bg-secondary text-white" data-id="{{ $table->id }}" style="cursor: pointer">
                              @endswitch
                              <div class="card-header">
                                  @if($table->nomeTavolo)
                                      {{ $table->nomeTavolo }}
                                  @else
                                      Prev. {{ $table->id }}
                                  @endif
                              </div>
                              <div class="card-body">
                                  {{ $table->countOrders() }} Forniture
                                  <br><b>{{ $table->totalOrders() }} €</b>
                              </div>
                              <div class="card-footer">
                                  @if($table->cliente)
                                      <b>{{ $table->cliente }} </b>
                                  @else
                                      Cliente indefinito
                                  @endif
                              </div>
                          </div>
                      </div>
  @endif

  @endforeach




<!--
      <div class="col-md-2 mb-3" id="newTableCard">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Nuovo Preventivo</h5>
              <p class="card-text"></p>
              <a href="#" class="btn btn-primary">Crea</a>
            </div>
          </div>
      </div>
-->
  </div>
  <div class="row" id="rowtwo">
      @foreach($tables as $table)
          @if($table->stato != 'libero')
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">

                  @switch($table->stato)
                      @case('occupato')
                      <div class="card text-white bg-danger" data-id="{{ $table->id }}" style="cursor: pointer">
                          @break
                          @case('servito')
                          <div class="card text-white bg-success" data-id="{{ $table->id }}" style="cursor: pointer">
                              @break
                              @default
                              <div class="card bg-secondary text-white" data-id="{{ $table->id }}" style="cursor: pointer">
                                  @endswitch
                                  <div class="card-header">
                                      @if($table->nomeTavolo)
                                          {{ $table->nomeTavolo }}
                                      @else
                                          Prev. {{ $table->id }}
                                      @endif
                                  </div>
                                  <div class="card-body">
                                      {{ $table->countOrders() }} Forniture
                                      <br><b>{{ $table->totalOrders() }} €</b>
                                  </div>
                                  <div class="card-footer">
                                      @if($table->cliente)
                                          <b>{{ $table->cliente }} </b>
                                      @else
                                          Cliente indefinito
                                      @endif
                                  </div>
                              </div>
                          </div>
                      @endif
                  @endforeach
                  <!--
      <div class="col-md-2 mb-3" id="newTableCard">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Nuovo Preventivo</h5>
              <p class="card-text"></p>
              <a href="#" class="btn btn-primary">Crea</a>
            </div>
          </div>
      </div>
-->

  </div>
--}}
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/tables.js') }}"></script>
@endsection
