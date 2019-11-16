@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
    <h1 class="h2" data-id="{{ $table->id }}">
      @if($table->nomeTavolo)
      {{ $table->nomeTavolo }}
      @else
      Prev. {{ $table->id }}
      @endif
    </h1>
    <div class="mb-2 mb-md-0 mr-auto ml-2">
      <form class="form-inline">
        <input type="text" id="searchBox" style="display: none;" class="form-control mr-1" placeholder="Search">
        <button type="button" id="cartBtn" class="btn btn-outline-info">Fornisci</button>
      </form>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group mr-2" id="statusTable" role="group" aria-label="First group">
        <button type="button" class="btn btn-outline-dark @if($table->stato == 'libero') active @endif" value="0">Da compilare</button>
        <button type="button" class="btn btn-outline-danger @if($table->stato == 'occupato') active @endif" value="1">Compilando</button>
        <button type="button" class="btn btn-outline-success @if($table->stato == 'servito') active @endif" value="2">Compilata</button>
      </div>
    </div>
  </div>

  <div class="row">

    <div class="col-md-12">
      <table class="table table-striped" id="foodTable">
            <thead class="thead-dark">
                <tr><th scope="col" class="d-none d-md-table-cell">id f.</th><th scope="col">Nome</th><th scope="col">Prezzo</th><th scope="col">Quantità</th><th scope="col" class="d-none d-sm-table-cell">Descrizione</th><th scope="col" class="d-none d-sm-table-cell">Categoria</th><th scope="col" class="d-none d-sm-table-cell">Immagine</th><th scope="col">Opzioni</th></tr>
            </thead>
        <tbody>
          @php ($orders = $table->orders())
          @if(count($orders))

              {{ logger('Debag forniture da riordinate.') }}
            @foreach($orders as $order)
              @php($food = $order->food())
              <tr><th scope="row" class="d-none d-md-table-cell">{{ $order->food_id }}</th><td>{{ $food->nome }}</td><td>{{ $food->prezzo }}€</td><td class="total">{{$order->total }} </td><td class="d-none d-sm-table-cell">{{ $food->descrizione }}</td><td class="d-none d-sm-table-cell">{{ $food->categoria }}</td><td class="d-none d-sm-table-cell"> <img src="{{URL::asset('img_uploads/'. $food->immagine)}}" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto"> </td><td><button class="btn btn-outline-danger mr-1"><i class="fas fa-minus-circle"></i></button><button class="btn btn-outline-success"><i class="fas fa-plus-circle"></i></button></td></tr>
            @endforeach
          @else
            <tr><td colspan="8">Nessuna Fornitura</td></tr>
          @endif
        </tbody>
      </table>

    </div>
  </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header" id="numTotalePrev">Totale: {{ $table->totalOrders() }}€</div>

                <div class="card-footer">
                    <button type="button" class="btn btn-danger" id="emptyBtn">Svuota</button>
                    <button type="button" id="precontoBtn" class="btn btn-info">Anteprima Preventivo</button>
                </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        Anteprima di stampa del preventivo
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body" id="PDFcontent">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="gpdf">Stampa</button>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('scripts')
<script src="{{ asset('js/table.js') }}"></script>

@endsection
