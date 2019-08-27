$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function newFood(){
    $.ajax({
      url: '/menu',
      method: "POST",
      data: {id: $('#id').val(), nome: $('#nome').val(), prezzo: $('#prezzo').val(), descrizione: $('#descrizione').val()},
      success: function(food){
        console.log(food);
        $('#foodTable tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + food.id + '</th><td>' + food.nome + '</td><td>' + food.prezzo + '</td><td class="d-none d-sm-table-cell">' + food.descrizione + '</td><td><button type="button" class="btn btn-outline-danger mr-2"> <i class="far fa-trash-alt"></i></button> <button type="button" class="btn btn-outline-info"><i class="far fa-edit"></i></button></td></tr>');
      }
    })
}

//delete food
$(document).on("click" , "tr .btn-outline-danger" , function(event){

    var resp = confirm("sei sicuro?");
    if(resp == true){
      var target = $(event.target);
      console.log(target.parents('tr'));

      var tr = target.parents('tr');
      console.log(tr.children('th').text());

      $.ajax({
        url: '/menu',
        method: "DELETE",
        data: {id: tr.children('th').text() },
        success: function(){
          tr.remove();
        }
      })
    }
})

var tempr;

//edit
$(document).on("click" , "tr .btn-outline-info" , function(event){

  var target = $(event.target);
  console.log(target.parents('tr'));

  var tr = target.parents('tr');
  console.log(tr.children('th').text());
  tempr = tr;
  //recupero valori del prodotto
  var id = tr.children('th').text();
  var nome = tr.children('td').eq(0).text();
  var prezzo = tr.children('td').eq(1).text();
  var descrizione = tr.children('td').eq(2).text();
  //console.log(nome);

  $('#idModal').val(id);
  $('#nomeModal').val(nome);
  $('#prezzoModal').val(prezzo);
  $('#descrizioneModal').val(descrizione);
  $('#foodModal').modal("show");
})

// conferma modifica prodotto
$(document).on("click" , "#btnSave" , function(){

  var id = $('#idModal').val();
  var nome = $('#nomeModal').val();
  var prezzo = $('#prezzoModal').val();
  var descrizione = $('#descrizioneModal').val();

  $.ajax({
    url: '/menu',
    method: "PATCH",
    data: {id : id ,nome : nome , prezzo : prezzo , descrizione : descrizione },
    success: function(res){ //recupero valori aggiornati
      tempr.children('td').eq(0).text(res.nome);
      tempr.children('td').eq(1).text(res.prezzo);
      tempr.children('td').eq(2).text(res.descrizione);
    }
  })

  $('#foodModal').modal('toggle');

})

$("#searchBox").keyup(function(){
  var input = $(this).val();
  setTimeout(function() {
    doSearch(input);
  }, 800);

})

function doSearch(input){

  $.ajax ({
    url: '/menu/search',
    method: "POST",
    data: {input:input},
    success: function(res){
      console.log(res);
      if(res.results.length){
        $('tbody').html("");
        res.results.forEach(function(food){
          $('tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + food.id + '</th><td>' + food.nome + '</td><td>' + food.prezzo + '</td><td class="d-none d-sm-table-cell">' + (food.descrizione ? food.descrizione : "") + '</td><td><button type="button" class="btn btn-outline-danger mr-2"> <i class="far fa-trash-alt"></i></button> <button type="button" class="btn btn-outline-info"><i class="far fa-edit"></i></button></td></tr>');
        })
      }
      else{
        $('tbody').html('<tr><td colspan="5">Nessun risultato per "' + input + '"</td></tr>');
      }
    }

  })

}
