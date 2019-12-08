@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
    <h1 class="h2">Preventivi</h1>
    @if(Auth::user()->isAdmin())
    <div class="btn-toolbar mb-2 mb-md-0">
      <button type="button" class="btn btn-sm btn-outline-secondary" id="modBtn">Modifica</button>
    </div>
    @endif
  </div>
  <div class="row">
      @foreach($tables as $table)

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
                Cliente: <b>{{ $table->cliente }} </b>
                @else
                 Cliente indefinito
                @endif
              </div>
          </div>
      </div>
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
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/tables.js') }}"></script>
@endsection
