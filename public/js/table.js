// CSRF AJAX
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

var cart = 0;

$('#statusTable button').click(function() {
    var that = $(this);

    $('#badgeTable').text(that.text());

    $.ajax({
      url: '/table',
      method: 'PATCH',
      dataType: 'html',
      data: {id: $('h1').data('id'), stato: that.val()},
      success: function(res){
        that.addClass('active').siblings().removeClass('active');
      }
    })
});

$('#cartBtn').click(function(e){
    var that = $(this);

    if(cart){
      that.text('Fornisci');
      cart = 0;

      $.ajax({
        url: '/orders/' + $('h1').data('id'),
        method: 'GET',
        success: function(orders){
          if(orders.length){
              $('thead').html('<tr><th scope="col" class="d-none d-md-table-cell">ID f.</th><th scope="col">Nome Prodotto</th><th scope="col">Prezzo</th><th scope="col">Unità</th><th scope="col">Quantità</th><th scope="col" class="d-none d-sm-table-cell">Descrizione</th><th scope="col" class="d-none d-sm-table-cell">Categoria</th><th scope="col" class="d-none d-sm-table-cell">Immagine</th><th scope="col">Opzioni</th></tr>');            $('tbody').html('');

              orders.sort( compare );

              orders.forEach(function(food){
              $('tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + food.id + '</th><td>' + food.nome + '</td><td>' + food.prezzo + '€</td><td>' + food.unita + '</td><td>' + food.total + '</td><td class="d-none d-sm-table-cell">' + (food.descrizione ? food.descrizione : "") + '</td><td class="d-none d-sm-table-cell">' + (food.categoria ? food.categoria : "") + '</td><td class="d-none d-sm-table-cell">' + '<img src="/img_uploads/' + food.immagine + '" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">' + '</td><td><button class="btn btn-outline-danger mr-1"><i class="fas fa-minus-circle"></i></button><button class="btn btn-outline-success"><i class="fas fa-plus-circle"></i></button></td></tr>');            })
          } else{
            $('tbody').html('<tr><td colspan="5">Nessun ordine.</td></tr>');
          }
        }
      })

      $('#searchBox').hide(250);

      $('tbody').html("");
    } else {
      that.text('Fine');
      cart = 1;
      $('#searchBox').show(250);

      var input = $('#searchBox').val();
      doSearch(input);
    }
})

//settaggio dei risultati della ricerca con un timeout
$("#searchBox").keyup(function(){
  var input = $(this).val();
  setTimeout(function() {
    doSearch(input); //richiamo la funzione di ricerca di un prodotto
  }, 800);

})

//funzione per la ricerca
function doSearch(input){
  console.log(input);

  $.ajax ({
    url: '/menu/search',
    method: "POST",
    data: {input:input},
    success: function(res){
      res.results.sort( compare ); // Faccio il sort degli oggetti per categoria, vedi funz COMPARE
      //console.log(res.results);
      if(res.results.length){
        $('thead').html('<tr><th scope="col" class="d-none d-md-table-cell">ID</th><th scope="col">Nome Prodotto</th><th scope="col">Prezzo</th><th scope="col">Unità</th><th scope="col" class="d-none d-sm-table-cell">Descrizione</th><th scope="col" class="d-none d-sm-table-cell">Categoria</th><th scope="col" class="d-none d-sm-table-cell">Immagine</th><th scope="col">Opzioni</th></tr>')
        $('tbody').html('');

        res.results.forEach(function(food){
            $('tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + food.id + '</th><td>' + food.nome + '</td><td>' + food.prezzo + '€</td><td>' + (food.unita ? food.unita : "") + '</td><td class="d-none d-sm-table-cell">' + (food.descrizione ? food.descrizione : "") + '</td><td class="d-none d-sm-table-cell">' + (food.categoria ? food.categoria : "") + '</td><td class="d-none d-sm-table-cell">' + '<img src="/img_uploads/' + food.immagine + '" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">' + '</td><td><button class="btn btn-outline-success"><i class="fas fa-plus-circle"></i></button></td></tr>');        })
      }
      else{
        $('tbody').html('<tr><td colspan="5">Nessun risultato per "' + input + '"</td></tr>');
      }
    }

  })
}

//funzione click del bottone di aggiunta di un prodotto all'ordine
$(document).on('click', 'tr .btn-outline-success', function(event){

  var target = $(event.target);
  var tr = target.parents('tr');

  //recupero valori del prodotto
  var id = tr.children('th').text();
  var total = tr.children('td').eq(2);
  addFood(id, total); //richiamo la funzione di aggiunta
})

//funzione click del bottone di cancellazione un prodotto dall'ordine
$(document).on('click', 'tr .btn-outline-danger', function(event){

  var target = $(event.target);
  var tr = target.parents('tr');

  //recupero valori del prodotto
  var id = tr.children('th').text();
  var total = tr.children('td').eq(2);
  console.log('delete: ' + id);
  deleteFood(id, total); //richiamo la funzione di cancellazione
})

//funzione di aggiunta prodotto all'ordine
function addFood(id, total){
  console.log('id: ' + id);
  $.ajax({
    url: '/table',
    method: 'POST',
    data: {table_id: $('h1').data('id'), food_id: id},
    success: function(res){
      $('h2').text(res.total + '€');
      if(cart == 0)
        total.text(parseInt(total.text()) + 1);
    }
  })
}

//funzione di cancellazione di un prodotto dall'ordine
function deleteFood(id, total){
  $.ajax({
    url: '/orders',
    method: 'DELETE',
    data: {table_id: $('h1').data('id'), food_id: id},
    success: function(res){
      $('h2').text(res.total + '€');
      res = parseInt(res.order);
      if( parseInt(total.text()) - res){
        total.text(parseInt(total.text()) - res);
      } else {
        total.parents('tr').remove();
      }

    }
  })
}

function editData(){
    var fd = new FormData($('#formdatipreventivo')[0]);
    console.log(fd);

    /*
    jQuery.ajax({
        url: "{{ url('/table/{id}') }}", MA SERVE L'ID VERO

        method: 'post',
        data: {
            estate_cod: "{{$estate->cod}}"
        },
        dataType: "text",
        success: function (result) {
            // jQuery('.alert').show();
            // jQuery('.alert').html(result.success);
            $(".btnfavoff").toggle();
            $(".btnfavon").toggle();
        },
        error: function (xhr, status, error) {
            if(xhr.status === 403)
            // alert('L'account non è ancora stato verificato: controlla la tua casella di posta elettronica per attivare questa funzionalità.\n
            // Altrimenti ');
                $('#modal403').modal('show')
            else
                alert("Impossibile aggiungere immobile ai preferiti!")
        }


    });
    */
}

//$("#emptyBtn").click(function(){
$(document).on('click', '#emptyBtn', function(){
  $.ajax({
    url: '/table',
    method: 'DELETE',
    data: {table_id: $('h1').data('id')},
    success: function(res){
      $('h2').text('0€');
      $('tbody').html('<tr><td colspan="6">Nessun ordine.</td></tr>');
    }
  })
});

$(document).on('click' , '#gpdf' , function(){
  //var pdfdoc = new jsPDF();
/// TODO: fixa il fukkin font, è per quello che formatta di merda

    //pdfdoc.fromHTML('/resources/print/print_example.html');
/*

    pdfdoc.fromHTML($('#PDFcontent').html(), 10, 10, {
        'width': 200
    });
*/
    //pdfdoc.save('Firstodes.pdf');
});

$(document).on('click' , '#precontoBtn' , function(){
    console.log("Anteprima Preventivo");
  var tr = $('tbody').find('tr');
  var tot = parseInt($('#numTotalePrev').text().split(/[ ,]+/)[1]);

  console.log("Tot: "+ tot);

  // Tenendo : &#8364; si scazza tutto l'output. Con la 'è' no invece. Mettere char speciali in generale fotte tutto.
  $('.modal-body').empty(); // Non si ripetano più tutte le anteprime precedenti a ogni pressione
  if(!tot) return;

  $('.modal-body').append('');
  $('.modal-body').append('<table style="padding: 70px">');
  $('.modal-body').append('<tr> <td height="10px"><b>[Dove], li</b></td> <td></td><td><b>[data] </b></td></tr>');
  $('.modal-body').append('<tr> <td height="40px"><b></b></td> <td></td><td>[destinatario]</td></tr>');

  $('.modal-body').append('<tr> <td><b>Oggetto:</b></td> <td></td><td><b>[testo oggetto] </b></td></tr>');

    $('.modal-body').append('<tr><td></td></tr>');

  var categoria = 'nessuna';
  for(var i=0;i<tr.length;i++){

    var nome = tr.eq(i).children('td').eq(0).text();
    var prezzo = tr.eq(i).children('td').eq(1).text();
    var unita = tr.eq(i).children('td').eq(2).text();
    var quantita = tr.eq(i).children('td').eq(3).text();
    var descrizione = tr.eq(i).children('td').eq(4).text();

    var nuovaCategoria = false;
    if(categoria != tr.eq(i).children('td').eq(5).text()){
        var nuovaCategoria = true;
    }
    categoria = tr.eq(i).children('td').eq(5).text();
    if(nuovaCategoria)
        $('.modal-body').append('<tr> <td> <b>'+ categoria +'</b> </td> </tr>');

    $('.modal-body').append('<tr>' + ' <td width="65" align="right">' + quantita + '</td><td>:        '+ nome +'</td></tr>');

      console.log("Quantita: "+ quantita);

  }

    var prezzoStr = prezzo.slice(0, -1);

  $('.modal-body').append('<p>Totale: EUR' + tot + '</p>');

  $('#myModal').modal();

});


function compare( a, b ) {
  if ( a.categoria < b.categoria ){
    return -1;
  }
  if ( a.categoria > b.categoria ){
    return 1;
  }
  return 0;
}
