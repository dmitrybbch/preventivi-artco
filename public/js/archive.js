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


$('.eliminaPrev').click(function(event){

    var target = $(event.target);
    var tr = target.parents('tr');

    // Recupero valori del prodotto
    var id = tr.children('td').eq(0).text();

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


