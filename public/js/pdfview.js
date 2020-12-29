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

$("#generaPdf").click(function() {
    // Devo prendere TUUUTTI i mannaggia di dati del tavolo
    console.log("Generazione PDF");

    // Trasforma un array di 3 elementi, ognuno array associativo,
    // in un unico array associativo con 3 indici e 3 valori
    //var dati = formDati.serializeArray();


    //var obj = $.extend({}, arrayAssociativo);

    var urlPathname = window.location.pathname;
    console.log('Creato array associativo. Stringa splittata: ' + urlPathname); // /pdf_view/1

    jQuery.ajax({
        url: urlPathname,
        method: 'post',
        data: urlPathname,
        success: function (result) {
            // jQuery('.alert').show();
            // jQuery('.alert').html(result.success);

            //id = id.split("/");
            console.log("Chiamata Ajax effettuata con successo.");

        },
        error: function (xhr, status, error) {
            console.log("Chebrutto :((((((((((");
        }
    });

})
