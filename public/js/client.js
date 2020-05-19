$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function newClient() {
    var fd = new FormData($('#formCliente')[0]);
    console.log('Inserisco una fornitura di capitolo: ' + fd.get("nome"));

    $.ajax({
        url: '/clients',
        method: "POST",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        success: function (cliente) {
            $('#clientsTable tr:last').after('' +
                '<td>' + cliente.id + '</td>' +
                '<td>' + cliente.nome + '</td>' +
                '<td>' + cliente.email + '</td>' +
                '<td>' + cliente.telefono + '</td>' +
                '<td>' + cliente.indirizzo + '</td>' +
                '<td>' + cliente.capCittaProv + '</td>' +
                '');
        }

    });


}
