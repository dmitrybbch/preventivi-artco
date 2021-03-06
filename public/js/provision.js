$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function newProvision() {
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
        success: function (pvsn) {
            //console.log("Cerco il tr #" + (pvsn.capitolo + pvsn.categoria).replace(/ /g,"_"));

            //Inserisco la sintesi nella tabella sopra, degli appena inseriti
            $('#inseritiTable').append('<tr>' +
                    '<th scope="row">' + pvsn.id + '</th>' +
                    '<td>' + pvsn.nome + '</td>' +
                    '<td class="d-none d-sm-table-cell">' + pvsn.descrizione + '</td>' +
                    '<td class="d-none d-sm-table-cell">' +
                    '<img src="/img_uploads/' + pvsn.immagine + '" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">' + '</td> ' +
                '</tr>');

            // Se non esiste la sezione del capitolo la creo
            var capUscore = pvsn.capitolo.replace(/ /g,"_");
            if(!$("#" + capUscore).length){
                console.log('Non esiste il capitolo. Creo il capitolo ' + capUscore);
                $('#foodTable').prepend(
                    '<tr id="'+ capUscore +'" class="table-active">' +
                    '<th scope="row" colspan="8" class="d-none d-md-table-cell">'+ pvsn.capitolo +'</th>' +
                    '</tr>'
                );
            }

            // Se non esiste la categoria, la creo
            var capUscoreCat = capUscore+pvsn.categoria;
            if( !$("#" + capUscoreCat).length){
                console.log('Non esiste la categoria per questo capitolo. Creo la categoria per il cap. ' + capUscore);
                $('#' + capUscore).after(
                    '<tr id="'+ capUscoreCat +'" class="thead-light text-white">' +
                    '<th><i class="fas fa-long-arrow-alt-right "></i></th>' +
                    '<th scope="row" colspan="7" class="d-none d-md-table-cell">'+ pvsn.categoria +'</th>' +
                    '</tr>'
                );
            }

            // Inserisco la fornitura
            $('#'+capUscoreCat).after('' +
                '<tr>' +
                '<th scope="row">' + pvsn.id + '</th>' +
                '<td>' + pvsn.nome + '</td>' +
                '<td>€ ' + pvsn.prezzo + ' x ' + pvsn.unita + '</td>' +
                '<td class="d-none d-sm-table-cell">' + pvsn.descrizione + '</td>' +
                '<td class="d-none d-sm-table-cell">' +
                '<img src="/img_uploads/' + pvsn.immagine + '" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">' +
                '</td>' +
                '<td>' +
                '<i class="far fa-trash-alt" style="font-size: 20px;cursor: pointer"></i>&nbsp;&nbsp;&nbsp;' +
                '<i class="far fa-edit" style="font-size: 20px;cursor: pointer"></i>&nbsp;&nbsp;&nbsp;' +
                '<i class="far fa-copy" style="font-size: 20px;cursor: pointer"></i>'+
                '</td>' +
                '</tr>');
        }
    });

}

//delete pvsn
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

// Copy pvsn
$(document).on("click", "tr .fa-copy", function (event) {
    // TODO: copia i valori nel modal di aggiunta fornitura
    var target = $(event.target);
    console.log(target.parents('tr'));

    var tr = target.parents('tr');

    $('#chapterForm').val(tr.data('capitolo'));
    $('#categoryForm').val(tr.data('categoria'));
    $('#name').val(tr.children('td').eq(0).text());

    var prezzoUnitaSplit = tr.children('td').eq(1).text().split(" ");
    var prezzo = prezzoUnitaSplit[1];
    $('#prezzo').val(prezzo);

    if(prezzoUnitaSplit.length > 3) {
        var unita = prezzoUnitaSplit[5]; // Per qualche motivo la posizione 5 è quella dell'unità
        $('#unit').val(unita);
    } else $('#unit').val("");

    $('#description').val(tr.children('td').eq(2).text());

    $("#form").fadeOut(200).fadeIn(200).fadeOut(200).fadeIn(200);

    console.log(tr.children('td').eq(0).text());
    console.log(tr.children('td').eq(1).text());
    console.log(tr.children('td').eq(2).text());
    console.log(tr.data('capitolo'));



})

// conferma modifica prodotto
$(document).on("click", "#btnSave", function () {

    var id = $('#idModal').val();
    var name = $('#nameModal').val();
    var cost = $('#costModal').val();
    var unit = $('#unitModal').val();
    var description = $('#descriptionModal').val();
    var chapter = $('#chapterModal').val();
    var category = $('#categoryModal').val();
    var imnage = $('#imageModal').val();


    $.ajax({
        url: '/menu',
        method: "PATCH",
        data: {
            id: id,
            name: name,
            cost: cost,
            unit: unit,
            description: description,
            chapter: chapter,
            category: category,
            image: image
        },
        success: function (res) { //recupero valori aggiornati
            tempr.children('td').eq(0).text(res.name);
            tempr.children('td').eq(1).text(res.cost + " x " + res.unit);
            tempr.children('td').eq(2).text(res.description);
            tempr.children('td').eq(3).text(res.chapter);
            tempr.children('td').eq(4).text(res.category);
            tempr.children('td').eq(5).text(res.image);
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
                res.results.forEach(function (pvsn) {
                    $('tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + pvsn.id + '</th><td>' + pvsn.name + '</td><td>' + pvsn.cost + ' x ' + pvsn.unit + '</td><td class="d-none d-sm-table-cell">' + (pvsn.description ? pvsn.description : "") + '</td><td class="d-none d-sm-table-cell">' + (pvsn.chapter ? pvsn.chapter : "") + '</td><td class="d-none d-sm-table-cell">' + (pvsn.category ? pvsn.category : "") + '</td><td class="d-none d-sm-table-cell">' + (pvsn.image ? pvsn.image : "") + '</td><td><button type="button" class="btn btn-outline-danger mr-2"> <i class="far fa-trash-alt"></i></button> <button type="button" class="btn btn-outline-info"><i class="far fa-edit"></i></button></td></tr>');
                })
            } else {
                $('tbody').html('<tr><td colspan="8">Nessun risultato per "' + input + '"</td></tr>');
            }
        }
    })
}
