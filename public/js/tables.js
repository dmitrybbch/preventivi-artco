// CSRF AJAX
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

var mod = false;

$('#modBtn').click(function (){
  var btn = $(this);
  btn.attr("disabled","disabled");


  if(mod){
    mod = false;

    var tables = [];
    $('.card').each(function(card){
      var id = $(this).data('id');
      var nome = $(this).find('input[type=text]').val();
      if(id != undefined)
        tables.push({id: id, nome: nome});

      console.log('id: ' + id + ' title: ' + nome);
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

    tables = getTables();
    $('.row').html("");
    tables.forEach(function(table){
      var html = '<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">\n';
          switch(table.stato){
            case 'occupato':
            html += '<div class="card text-white bg-danger" data-id="' + table.id + '">\n';
            break;
            case 'servito':
            html += '<div class="card text-white bg-success" data-id="' + table.id + '">\n';
            break;
            default:
            html += '<div class="card" data-id="' + table.id + '">\n';
          }
            if(table.nomeTavolo) html += '<div class="card-header">' + table.nomeTavolo + '</div>\n';
            else html += '<div class="card-header">Tavolo ' + table.id + '</div>\n';
            html +=  '<div class="card-body">\n' +
                table.countOrders + ' portate\n' +
              '</div>\n' +
              '<div class="card-footer">\n' +
                'Totale: ' + table.totalOrders + '€\n' +
              '</div>\n' +
          '</div>\n' +
      '</div>\n';
      $('.row').append(html);
    })

    $('.card').click(function(event){
      if(!mod){
        var id = $(this).data('id');
        window.location = '/table/' + id;
      }
    });

    btn.text('Modifica');
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
                if(table.nomeTavolo) html += '<input type="text" class="form-control" value="' + table.nomeTavolo + '">\n';
                else html += '<input type="text" class="form-control" value="Tavolo ' + table.id + '">\n';

                html +=  '<div class="input-group-append">\n' +
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
      $('.row').append(html);
    })
    var html = '<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3" id="newTable">' +
                    '<div class="card">' +
                      '<div class="card-body">' +
                        '<h5 class="card-title">Nuovo tavolo</h5>' +
                        '<p class="card-text"><input type="number" class="form-control" step="1" id="nTable" value="1"></p>' +
                        '<a href="#" onclick="newTable()" class="btn btn-primary">Crea</a>' +
                      '</div>' +
                    '</div>' +
                '</div>';
    $('.row').append(html);

    //pulsante cancella tavolo
    $('.deleteBtn').click(function(btn){
      var resp = confirm("sei sicuro?");
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
                    '<input type="text" class="form-control" value="Tavolo ' + table.id + '">\n' +
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

