$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function newClient() {
    var fd = new FormData($('#formCliente')[0]);
    console.log('Inserisco una fornitura di capitolo: ' + fd.get("nome"));

    $('#formCliente')[0].reset();

    $.ajax({
        url: '/clients',
        method: "POST",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        success: function (cliente) {
            $('#clientsTable tr:last').after('' +
                '<th>' + cliente.id + '</th>' +
                '<td>' + cliente.nome + '</td>' +
                '<td>' + cliente.email + '</td>' +
                '<td>' + cliente.telefono + '</td>' +
                '<td>' + cliente.indirizzo + '</td>' +
                '<td>' + cliente.capCittaProv + '</td>' +
                '');
        }
    });
}

$(document).on("click", "tr .fa-trash-alt", function (event) {

    var resp = confirm("Confermare la cancellazione?");
    if (resp == true) {
        var target = $(event.target);
        console.log(target.parents('tr'));

        var tr = target.parents('tr');
        console.log(tr.children('th').text());

        $.ajax({
            url: '/clients',
            method: "DELETE",
            data: {id: tr.children('th').text()},
            success: function () {
                tr.remove();
            }
        })
    }
})
