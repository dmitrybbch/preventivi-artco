// CSRF AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }


});


$("#correggi").click(function() {

    var id = window.location.href;
    id = id.split("/");

    console.log("Torniamo indietro al tavolo "+ id[id.length - 1]);

    window.location = '/table/' + id[id.length -1];


})
