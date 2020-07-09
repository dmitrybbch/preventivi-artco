$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function newFood() {
    var fd = new FormData($('#form')[0]);
    console.log('Inserisco una fornitura di capitolo: ' + fd.get("nome"));
    //fd.append("capitolo_categoria", fd.get("capitolo") + "_" + fd.get("categoria"));
    $('#form')[0].reset();

    $.ajax({
        url: '/menu',
        method: "POST",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        success: function (food) {
            console.log("Cerco l'header #" + (food.capitolo+food.categoria).replace(/ /g,"_"));

            //Inserisco nella tabella sopra, degli appena inseriti
            $('#inseritiTable').append('<tr><th scope="row">' + food.id + '</th><td>' + food.nome + '</td><td>€ ' + food.prezzo + ' x ' + food.unita + '</td><td class="d-none d-sm-table-cell">' + food.descrizione + '</td><td class="d-none d-sm-table-cell">' + food.capitolo + '</td><td class="d-none d-sm-table-cell">' + food.categoria + '</td><td class="d-none d-sm-table-cell">' + '<img src="/img_uploads/' + food.immagine + '" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">' + '</td> <td><button type="button" class="btn btn-outline-danger mr-2"> <i class="far fa-trash-alt"></i></button> <button type="button" class="btn btn-outline-info"><i class="far fa-edit"></i></button></td></tr>');


            // Se trovo l'header giusto (il capitolo), inserisco.
            $('#'+(food.capitolo+food.categoria).replace(/ /g,"_")).insertAfter('' +
                '<tr>' +
                '<th scope="row">' + food.id + '</th>' +
                '<td>' + food.nome + '</td>' +
                '<td>€ ' + food.prezzo + ' x ' + food.unita + '</td>' +
                '<td class="d-none d-sm-table-cell">' + food.descrizione + '</td>' +

                '<td class="d-none d-sm-table-cell">' + '<img src="/img_uploads/' + food.immagine + '" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">' + '</td>' +
                '</tr>');

        }

    });


}

//delete food
$(document).on("click", "tr .fa-trash-alt", function (event) {

    var resp = confirm("Confermare la cancellazione?");
    if (resp == true) {
        var target = $(event.target);
        console.log(target.parents('tr'));

        var tr = target.parents('tr');
        console.log(tr.children('th').text());

        $.ajax({
            url: '/menu',
            method: "DELETE",
            data: {id: tr.children('th').text()},
            success: function () {
                tr.remove();
            }
        })
    }
})

var tempr;

//edit
$(document).on("click", "tr .fa-edit", function (event) {

    var target = $(event.target);
    //console.log(target.parents('tr'));

    var tr = target.parents('tr');
    //console.log(tr.children('th').text());
    tempr = tr;
    //recupero valori del prodotto
    var id = tr.children('th').text();
    var nome = tr.children('td').eq(0).text();
    // Prendo prima della x e tolgo gli spazi
    var prezzoCad = tr.children('td').eq(1).text().split(" ");
    var prezzo = prezzoCad[1];
    var unita = prezzoCad[prezzoCad.length - 2];
    console.log(prezzoCad + ". Prezzo: " + prezzo + ". Unità: " + unita);
    var descrizione = tr.children('td').eq(2).text();
    var capitolo = tr.children('td').eq(3).text();
    var categoria = tr.children('td').eq(4).text();
    var immagine = tr.children('td').eq(5).text();

    $('#idModal').val(id);
    $('#nomeModal').val(nome);
    $('#prezzoModal').val(prezzo);
    $('#unitaModal').val(unita);
    $('#descrizioneModal').val(descrizione);
    $('#capitoloModal').val(capitolo);
    $('#categoriaModal').val(categoria);
    $('#immagineModal').val(immagine);

    $('#foodModal').modal("show");

})

// Copy food
$(document).on("click", "tr .fa-copy", function (event) {
    // TODO: copia i valori nel modal di aggiunta fornitura
    var target = $(event.target);
    console.log(target.parents('tr'));

    var tr = target.parents('tr');

    $('#capitoloForm').val(tr.data('capitolo'));
    $('#categoriaForm').val(tr.data('categoria'));
    $('#nome').val(tr.children('td').eq(0).text());

    var prezzoUnitaSplit = tr.children('td').eq(1).text().split(" ");
    var prezzo = prezzoUnitaSplit[1];
    $('#prezzo').val(prezzo);

    if(prezzoUnitaSplit.length > 3) {
        var unita = prezzoUnitaSplit[5]; // Per qualche motivo la posizione 5 è quella dell'unità
        $('#unita').val(unita);
    } else $('#unita').val("");

    $('#descrizione').val(tr.children('td').eq(2).text());

    $("#form").fadeOut(200).fadeIn(200).fadeOut(200).fadeIn(200);

    console.log(tr.children('td').eq(0).text());
    console.log(tr.children('td').eq(1).text());
    console.log(tr.children('td').eq(2).text());
    console.log(tr.data('capitolo'));



})

// conferma modifica prodotto
$(document).on("click", "#btnSave", function () {

    var id = $('#idModal').val();
    var nome = $('#nomeModal').val();
    var prezzo = $('#prezzoModal').val();
    var unita = $('#unitaModal').val();
    var descrizione = $('#descrizioneModal').val();
    var capitolo = $('#capitoloModal').val();
    var categoria = $('#categoriaModal').val();
    var immagine = $('#immagineModal').val();


    $.ajax({
        url: '/menu',
        method: "PATCH",
        data: {
            id: id,
            nome: nome,
            prezzo: prezzo,
            unita: unita,
            descrizione: descrizione,
            capitolo: capitolo,
            categoria: categoria,
            immagine: immagine
        },
        success: function (res) { //recupero valori aggiornati
            tempr.children('td').eq(0).text(res.nome);
            tempr.children('td').eq(1).text(res.prezzo + " x " + res.unita);
            tempr.children('td').eq(2).text(res.descrizione);
            tempr.children('td').eq(3).text(res.capitolo);
            tempr.children('td').eq(4).text(res.categoria);
            tempr.children('td').eq(5).text(res.immagine);
        }
    })

    $('#foodModal').modal('toggle');

})

$("#searchBox").keyup(function () {
    var input = $(this).val();
    setTimeout(function () {
        doSearch(input);
    }, 800);

})

function doSearch(input) {

    $.ajax({
        url: '/menu/search',
        method: "POST",
        data: {input: input},
        success: function (res) {
            console.log(res);
            if (res.results.length) {
                $('tbody').html("");
                res.results.forEach(function (food) {
                    $('tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + food.id + '</th><td>' + food.nome + '</td><td>' + food.prezzo + ' x ' + food.unita + '</td><td class="d-none d-sm-table-cell">' + (food.descrizione ? food.descrizione : "") + '</td><td class="d-none d-sm-table-cell">' + (food.capitolo ? food.capitolo : "") + '</td><td class="d-none d-sm-table-cell">' + (food.categoria ? food.categoria : "") + '</td><td class="d-none d-sm-table-cell">' + (food.immagine ? food.immagine : "") + '</td><td><button type="button" class="btn btn-outline-danger mr-2"> <i class="far fa-trash-alt"></i></button> <button type="button" class="btn btn-outline-info"><i class="far fa-edit"></i></button></td></tr>');
                })
            } else {
                $('tbody').html('<tr><td colspan="8">Nessun risultato per "' + input + '"</td></tr>');
            }
        }
    })
}
