// CSRF AJAX
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

var mod = false;

$('#modBtn').click(function (){
    console.log('Chiamato la funzione!');
    var btn = $(this);
    btn.attr("disabled","disabled");


  if(mod){
    mod = false;

    var tables = [];
    $('.card').each(function(card){
      var id = $(this).data('id');
      var nome = $(this).find('input[id=scimmiaCodeNome]').val();
      var cliente = $(this).find('input[id=scimmiaCodeCliente]').val();

      if(id != undefined)
        tables.push({id: id, nome: nome, cliente: cliente});

      console.log( cliente);
    })

    var jsonString = JSON.stringify(tables)
    var res = $.ajax({
      url: '/tables',
      method: 'PATCH',
      dataType: 'html',
      data: {tables: tables},
      async: false
    }).responseText;

    console.log(res);

    window.location = '/tables';
    btn.removeAttr('disabled');
  } else {
    mod = true;
    var tables = getTables();
    $('.row').html("");

    tables.forEach(function(table){
      var html = '<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">\n' +
          '<div class="card" data-id="' + table.id + '">\n' +
              '<div class="card-header">\n' +
                '<div class="input-group">\n';
                if(table.nomeTavolo) html += '<input type="text" class="form-control" id ="scimmiaCodeNome" value="' + table.nomeTavolo + '">\n';
                else html += '<input type="text" class="form-control" id ="scimmiaCodeNome" value="Prev."' + table.id + '">\n';
                html +=  '<div class="input-group-append">\n' +
                    '<button class="btn btn-outline-danger deleteBtn" type="button"><i class="far fa-trash-alt"></i></button>\n' +
                  '</div>\n' +
                '</div>\n' +
              '</div>\n' +
              '<div class="card-body">\n' +

              '</div>\n' +
              '<div class="card-footer">\n <b>'
                    + table.cliente +
              '</b> </div>\n' +
          '</div>\n' +
      '</div>\n';
      $('#rowone').append(html);
    })
    var html = '<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3" id="newTable">' +
                    '<div class="card">' +
                      '<div class="card-body">' +
                        '<h5 class="card-title">Nuovi Preventivi (#):</h5>' +
                        '<p class="card-text"><input type="number" class="form-control" step="1" id="nTable" value="1"></p>' +
                        '<a href="#" onclick="newTable()" class="btn btn-primary">Crea</a>' +
                      '</div>' +
                    '</div>' +
                '</div>';
    $('#rowtwo').append(html);

    //pulsante cancella tavolo
    $('.deleteBtn').click(function(btn){
      var resp = confirm("Confermare la cancellazione?");
      if(resp == true){

        $(this).attr("disabled","disabled");
        var card = $(this).parents('div .card');

        $.ajax({
          url: '/tables',
          method: 'DELETE',
          data: {id: card.data('id')},
          success: function(result){
            console.log(result);
            console.log(card.parents('div')[0].remove());
          }
        })
      }
    })

    btn.text('Salva');
    btn.removeAttr('disabled');
  }
})



function getTables(){
  return $.ajax({
    url: '/tables/get',
    method: 'GET',
    async: false
  }).responseJSON;
}

function newTable(){
  console.log('new');
  var n = $('#nTable').val();
  $.ajax({
    url: '/tables',
    method: 'POST',
    data: {n: n},
    success: function(tables){
      //console.log(user);
      tables.forEach(function(table){
        var html = '<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">\n' +
            '<div class="card" data-id="' + table.id + '">\n' +
                '<div class="card-header">\n' +
                  '<div class="input-group">\n' +
                    '<input type="text" class="form-control" value="Prev. ' + table.id + '">\n' +
                    '<div class="input-group-append">\n' +
                      '<button class="btn btn-outline-danger deleteBtn" type="button"><i class="far fa-trash-alt"></i></button>\n' +
                    '</div>\n' +
                  '</div>\n' +
                '</div>\n' +
                '<div class="card-body">\n' +
                  'Vuoto\n' +
                '</div>\n' +
                '<div class="card-footer">\n' +
                  'Totale: 0€\n' +
                '</div>\n' +
            '</div>\n' +
        '</div>\n';
        $(html).insertBefore('#newTable');
      })

      $('.deleteBtn').click(function(btn){
        $(this).attr("disabled","disabled");
        var card = $(this).parents('div .card');

        $.ajax({
          url: '/tables',
          method: 'DELETE',
          data: {id: card.data('id')},
          success: function(result){
            console.log(result);
            console.log(card.parents('div')[0].remove());
          }
        })
      })
    }
  })
}

$('.card').click(function(event){
  if(!mod){
    var id = $(this).data('id');
    window.location = '/table/' + id;
  }
});

