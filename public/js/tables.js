// CSRF AJAX
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function getTables(){
  return $.ajax({
    url: '/tables/get',
    method: 'GET',
    async: false
  }).responseJSON;
}

$('#newQuoteButton').click(function(event){
    var prev = $("#nomePrevInput").val();
    var stato = 'libero';
    if($('#template').hasClass('active'))
        stato = 'occupato';

    console.log('Inserisco: '+ prev);

    $.ajax({
        url: '/tables',
        method: 'POST',
        data: {prev: prev, stato: stato},

        success: function(newQuote){
            var table_class = (newQuote.stato.localeCompare('libero') ? 'righe-in-corso' : 'righe-template');

            $('#quotesTable').append('' +
            '<tr class="'+ table_class +'" data-id="'+ newQuote.id +'">' +
            '<td class="d-none d-md-table-cell"><b>'+ newQuote.id +'</b></td>' +
            '<td class="prevTr" style="cursor: pointer">'+ newQuote.nomeTavolo +'</td>' +
            '<td></td>' +
            '<td>'+ '0' +'</td>' +
            '<td>0 €</td>' +
            '<td>'+ newQuote.creatoDa +'</td>' +
            '<td>'+ newQuote.creatoInData +'</td>' +
            '<td>'
            );
        }
    })
});

$('.creaDaTemplate').click(function(event){
    var target = $(event.target);
    var tr = target.parents('tr');
    var id = tr.data('id');

    var quote_name = prompt('Come vuoi chiamare il nuovo preventivo?', tr.children('td').eq(1).text());

    $.ajax({
        url: '/tables/'+ id + '/' + quote_name,
        method: 'POST',
        success: function(copia){
            window.location = '/table/' + copia.id;
        }
    })
    console.log(id);
});

$('.eliminaPrev').click(function(event){
    var target = $(event.target);
    var tr = target.parents('tr');
    var id = tr.data('id');

    $.ajax({
        url: '/tables',
        method: 'DELETE',
        data: {id: id},
        success: function(tables){
            //location.reload(); Via ste cose da trogloditi pls
            if(confirm('Vuoi davvero ELIMINARE il preventivo?'))
                tr.remove();
        }
    })
    console.log(id);
});

$('.prevTr').click(function(event){
    var target = $(event.target);
    var tr = target.parents('tr');
    var id = tr.data('id');

    window.location = '/table/' + id;
});

$('#switchTemplateIncorso button').click(function () {
    var that = $(this);
    that.addClass('active').siblings().removeClass('active');

    if(that.attr('id') === "template"){
        $("#quotesTable").find("thead").eq(0).removeClass('bg-secondary').addClass('bg-success');
        $('.righe-in-corso').hide(200, function(){
            $('.righe-template').show('slow');
        });
        $('#newQuoteButton').removeClass('btn-outline-secondary').addClass('btn-outline-success');
    }
    if(that.attr('id') === "incorso"){
        $("#quotesTable").find("thead").eq(0).removeClass('bg-success').addClass('bg-secondary');
        $('.righe-template').hide(200, function(){
            $('.righe-in-corso').show('slow');
        });
        $('#newQuoteButton').removeClass('btn-outline-success').addClass('btn-outline-secondary');
    }
});

