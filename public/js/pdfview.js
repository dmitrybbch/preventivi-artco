// CSRF AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }


});


$("#bottoneBlasfemo").click(function() {


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
