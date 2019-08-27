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
      that.text('Menù');
      cart = 0;

      $.ajax({
        url: '/orders/' + $('h1').data('id'),
        method: 'GET',
        success: function(orders){
          if(orders.length){
            $('thead').html('<tr><th scope="col" class="d-none d-md-table-cell">id Prodotto</th><th scope="col">Nome Prodotto</th><th scope="col">Prezzo</th><th scope="col">Quantità</th><th scope="col" class="d-none d-sm-table-cell">Descrizione</th><th scope="col">Opzioni</th></tr>');
            $('tbody').html('');
            orders.forEach(function(food){
              $('tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + food.id + '</th><td>' + food.nome + '</td><td>' + food.prezzo + '€</td><td>' + food.total + '</td><td class="d-none d-sm-table-cell">' + (food.descrizione ? food.descrizione : "") + '</td><td><button class="btn btn-outline-danger mr-1"><i class="fas fa-minus-circle"></i></button><button class="btn btn-outline-success"><i class="fas fa-plus-circle"></i></button></td></tr>');
            })
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
      console.log(res);
      if(res.results.length){
        $('thead').html('<tr><th scope="col" class="d-none d-md-table-cell">id Prodotto</th><th scope="col">Nome Prodotto</th><th scope="col">Prezzo</th><th scope="col" class="d-none d-sm-table-cell">Descrizione</th><th scope="col">Opzioni</th></tr>')
        $('tbody').html('');
        res.results.forEach(function(food){
          $('tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + food.id + '</th><td>' + food.nome + '</td><td>' + food.prezzo + '€</td><td class="d-none d-sm-table-cell">' + (food.descrizione ? food.descrizione : "") + '</td><td><button class="btn btn-outline-success"><i class="fas fa-plus-circle"></i></button></td></tr>');
        })
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
  var pdfdoc = new jsPDF();

  pdfdoc.fromHTML($('#PDFcontent').html(), 10, 10, {

    'width': 200

  });

  pdfdoc.save('First.pdf');
});

$(document).on('click' , '#precontoBtn' , function(){

  var tr = $('tbody').find('tr');
  var tot = parseInt($('h2').text().slice(0, -1));

  if(!tot) return;
  for(var i=0;i<tr.length;i++){
    var nome = tr.eq(i).children('td').eq(0).text();
    var prezzo = tr.eq(i).children('td').eq(1).text();
    var quantita = tr.eq(i).children('td').eq(2).text();
    $('.modal-body').append('<p>' + quantita + ' x ' + prezzo.slice(0, -1) + ' euro: ' + nome + '</p>');
  }

  //console.log();
  $('.modal-body').append('<p>Totale: ' + tot + ' euro</p>');
  $('#myModal').modal();
});
