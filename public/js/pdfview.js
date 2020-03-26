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
/*
    jQuery.ajax({
        url: urlPathname,
        method: 'post',
        data: obj,
        success: function (result) {
            // jQuery('.alert').show();
            // jQuery('.alert').html(result.success);
            console.log("Chebello");
        },
        error: function (xhr, status, error) {
            console.log("Chebrutto :((((((((((");
        }
    });
*/

})
