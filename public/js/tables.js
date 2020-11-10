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

function newTable(){
    var prev = $("#nomePrevInput").val();
    console.log('Inserisco: '+ prev);

    $.ajax({
        url: '/tables',
        method: 'POST',
        data: {prev: prev},

        success: function(tables){
        }
    })
}

$('.creaDaTemplate').click(function(event){
    var target = $(event.target);
    var tr = target.parents('tr');
    var id = tr.data('id');
    var quote_name = prompt('Come vuoi chiamare il nuovo preventivo?', tr.children('td').eq(1).text());
    $.ajax({
        url: '/tables/'+ id + '/' + quote_name,
        method: 'POST',
        success: function(){

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
            location.reload();
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
        $("#quotesTable").find("thead").eq(0).removeClass('bg-dark').addClass('bg-success');
        $('.righe-in-corso').hide(200, function(){
            $('.righe-template').show('slow');
        });

    }
    if(that.attr('id') === "incorso"){
        $("#quotesTable").find("thead").eq(0).removeClass('bg-success').addClass('bg-dark');
        $('.righe-template').hide(200, function(){
            $('.righe-in-corso').show('slow');
        });
    }
});

