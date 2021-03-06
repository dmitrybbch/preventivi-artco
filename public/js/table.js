// CSRF AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var cart = 0;

$('#statusTable button').click(function () {
    var that = $(this);

    $('#badgeTable').text(that.text());
    var id = window.location.href;
    id = id.split("/");

    $.ajax({
        url: '/table/' + id,
        method: 'PATCH',
        dataType: 'html',
        data: {id: $('h1').data('id'), stato: that.val()},
        success: function (res) {
            that.addClass('active').siblings().removeClass('active');
        }
    })
});

$('#cartBtn').click(function (e) {
    var that = $(this);

    if (cart) {
        that.text('Fornisci');
        cart = 0;

        $.ajax({
            url: '/orders/' + $('h1').data('id'),
            method: 'GET',
            success: function (orders) {
                if (orders.length) {
                    /*
                    orders.sort(compare);

                    orders.forEach(function (food) {
                        $('tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + food.id + '</th>' +
                            '<td>' + food.nome + '</td>' +
                            '<td>' + food.prezzo + '€</td>' +
                            '<td>' + food.unita + '</td>' +
                            '<td>' + food.total + '</td>' +
                            '<td class="d-none d-sm-table-cell">' + (food.descrizione ? food.descrizione : "") + '</td>' +
                            '<td class="d-none d-sm-table-cell">' + (food.categoria ? food.categoria : "") + '</td>' +
                            '<td class="d-none d-sm-table-cell">' + '<img src="/img_uploads/' + food.immagine + '" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">' + '</td>' +
                            '<td><button class="btn btn-outline-danger mr-1"><i class="fas fa-minus-circle"></i></button><button class="btn btn-outline-success"><i class="fas fa-plus-circle"></i></button></td></tr>');
                    })*/
                } else {
                    $('tbody').html('<tr><td colspan="5">Nessun ordine.</td></tr>');
                }
            }
        })

        $('#searchBox').hide(250);
        $('#fornitureTable').html("");
    } else {
        that.text('Fine');
        cart = 1;
        $('#searchBox').show(250);

        var input = $('#searchBox').val();
        doSearch(input);
    }
})


$("#salvaDatiAggiuntivi").click(function () {

    //var formDati = new FormData($('#formdatipreventivo')[0]);
    //console.log(formDati[0].text());
    var formDati = $('#formdatipreventivo');
    var isvalid = formDati[0].checkValidity();
    if (isvalid) {
        // Cose del Gando
        event.preventDefault();
        // Trasforma un array di 3 elementi, ognuno array associativo,
        // in un unico array associativo con 3 indici e 3 valori
        var dati = formDati.serializeArray();
        var arrayAssociativo = [];
        for (var i = 0; i < 4; i++)
            arrayAssociativo[dati[i]['name']] = dati[i]['value'];
    }

    var obj = $.extend({}, arrayAssociativo);
    var urlPathname = window.location.pathname;
    console.log('Creato array associativo. Stringa splittata: ' + urlPathname); // /table/1

    jQuery.ajax({
        url: urlPathname,
        method: 'post', //Richiama la funzione updateData
        data: obj,
        success: function (result) {
            console.log("Chebello");
        },
        error: function (xhr, status, error) {
            console.log("Chebrutto :((((((((((");
        }
    });

})

//settaggio dei risultati della ricerca con un timeout
$("#searchBox").keyup(function () {
    var input = $(this).val();
    setTimeout(function () {
        doSearch(input); //richiamo la funzione di ricerca di un prodotto
    }, 800);
})

//funzione per la ricerca
function doSearch(input) {
    console.log(input);

    $.ajax({
        url: '/menu/search',
        method: "POST",
        data: {input: input},
        success: function (res) {

            if (res.results.length) {
                var capitoloTabella = "Errore: Cap. Vuoto";
                var categoriaTabella = "Errore: Cat. Vuota";
                res.results.forEach(function (food) {
                    var capAttuale = food.chapter;
                    if(capitoloTabella.localeCompare(capAttuale)){
                        $('#fornitureTable').append('' +
                            '<tr class="table-active">' +
                            '<th scope="row" colspan="6" class="d-none d-md-table-cell">' + capAttuale + '</th>' +
                            '</tr>');
                        capitoloTabella = capAttuale;
                    }
                    var catAttuale = food.category;
                    if(categoriaTabella.localeCompare(catAttuale)){
                        $('#fornitureTable').append('' +
                            '<tr class="thead-light text-white">' +
                            '<th><i class="fas fa-long-arrow-alt-right "></i></th><th scope="row" colspan="5" class="d-none d-md-table-cell">' + catAttuale + '</th>' +
                            '</tr>');
                        categoriaTabella = catAttuale;
                    }

                    $('#fornitureTable').append('' +
                        '<tr data-capitolo="'+ food.chapter +'" data-categoria="'+ food.category +'" data-price="' + food.cost + '">' +
                        '<td><i class="fas fa-chevron-left inputRicerca" style="cursor: pointer"></i></td>' +
                        '<th scope="row" class="d-none d-md-table-cell">' + food.id + '</th>' +
                        '<td>€ ' + food.cost + '</td>' +
                        '<td>' + food.name + '</td>' +
                        '<td class="d-none d-sm-table-cell">' + '<img src="/img_uploads/' + food.image + '" class="align-middle" alt="ArtCO" style="max-height: 60px; width:auto">' + '</td>' +
                        '</tr>');
                })
            } else {
                $('#fornitureTable').html('<tr><td colspan="9">Nessun risultato per "' + input + '"</td></tr>');
            }
        }

    })
}

//funzione click del bottone di aggiunta di un prodotto all'ordine
$(document).on('click', 'tr .inputRicerca', function (event) {
    //TODO: aggiornare la tabella di sinistra. Non c'è verso, qui aggiornare è cancro.
    ////TODO: mostrare in qualche modo le forniture già messe in preventivo.
    var target = $(event.target);
    var tr = target.parents('tr');

    // Recupero valori del prodotto
    var id = tr.children('th').text();
    var price = tr.data('price');
    console.log("Aggiunta in preventivo della fornitura dall'ID: " + id);
    addFood(id, price); // Richiamo la funzione di aggiunta

})

// Funzione di aggiunta prodotto all'ordine
function addFood(id, price) {
    $.ajax({
        url: '/table',
        method: 'POST',
        data: {table_id: $('h1').data('id'), provision_id: id},
        success: function (res) {
            // res['order'] = ordine, res['fornitura'] = fornitura
            $('#totaleOrdini').text(res["total"] + ' €');
            $('#totaleConRicarico').text(res["totalWithMargin"] + ' €');

            // TODO: aggiorna il totale in display
            // TODO: aggiornare la tabella

            console.log("Ammontare da scrivere in tabella grafica:" + res["order"]["amount"]);
            console.log(res["fornitura"]["nomeTavolo"]);

            // Se NON c'è il capitolo lo creo
            var capUscore = res["fornitura"]["chapter"].replace(/ /g,"_");
            if(!$("#" + capUscore).length){
                console.log("NON ho trovato il capitolo " + capUscore);
                $('#foodTable').prepend(
                    '<tr id="'+ capUscore +'" class="table-active">' +
                    '<th scope="row" colspan="8" class="d-none d-md-table-cell">'+ res["fornitura"]["chapter"] +'</th>' +
                    '</tr>'
                );
            }

            // Se NON c'è la categoria la creo
            var capUscoreCat = capUscore + res["fornitura"]["category"];
            if(!$("#" + capUscoreCat).length){
                console.log("NON ho trovato la categoria " + res["fornitura"]["category"]);
                $('#' + capUscore).after(
                    '<tr id="'+ capUscoreCat +'" class="thead-light text-white">' +
                    '<th><i class="fas fa-long-arrow-alt-right "></i></th>' +
                    '<th scope="row" colspan="7" class="d-none d-md-table-cell">'+ res["fornitura"]["category"] +'</th>' +
                    '</tr>'
                );
            }

            //Inserisco il prodotto nella sua categoria. WARNING: magic numbers (quantità, parziale, ricarico)
            $('#'+capUscoreCat).after('' +
                '<tr data-capitolo=' + res["fornitura"]["chapter"] +' data-categoria='+ res["fornitura"]["category"] +'>' +
                '<th scope="row">' + id + '</th>' +
                '<td class="amount">' +
                    '<div class="col-md-8">' +
                        '<input class="form-control amount" type="number" step="1" name="quantitaTab" value='+ 1 +'>'+
                    '</div>' +
                '</td>' +
                '<td>' + res["fornitura"]["description"]+ '</td>' +
                '<td class="total">€ '+ res["fornitura"]["cost"] +'</td>' +
                '<td class="add_percent">' +
                    '<div class="col-md-9">' +
                        '<input class="form-control add_percent" type="number" step="0.1" name="addTab" value="0.0">' +
                    '</div>' +
                '</td>' +
                '<td class="totalR">€ '+res["fornitura"]["cost"] +'</td>' + //Semi-magic number, operazioni non svolte
                '<td><i class="fas fa-eraser togliFornitura" style="cursor:pointer"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
                '</tr>' +
            '');
        }
    })
}

//funzione click del bottone di cancellazione un prodotto dall'ordine
$(document).on('click', 'tr .togliFornitura', function (event) {

    var target = $(event.target);
    var tr = target.parents('tr');

    //recupero valori del prodotto
    var id = tr.children('th').text();
    var amount = tr.children('td').eq(2);
    console.log('delete: ' + id);

    deleteFood(id); //richiamo la funzione di cancellazione
    tr.remove();
})


//funzione di cancellazione di un prodotto dall'ordine
function deleteFood(id /*, total*/) {
    $.ajax({
        url: '/orders',
        method: 'DELETE',
        data: {table_id: $('h1').data('id'), food_id: id},
        success: function (res) {
            // TODO: Aggiornare il totale
            /*
            $('h2').text(res.total + '€');
            res = parseInt(res.order);
            if (parseInt(total.text()) - res)
                total.text(parseInt(total.text()) - res);
            else
                total.parents('tr').remove();
            */
            $('#totaleOrdini').text(res["total"] + ' €');
            $('#totaleConRicarico').text(res["totalWithMargin"] + ' €');
        }
    })
}

function updateOrder(id, newVal, amountOrAddpercent) {
    // TODO: fatto sta maialata per semplicità. Fixare unendo i due casi e mettendo un check nel controller
    console.log("UpdateOrder: table " + $('h1').data('id') + ", food: " + id);
    if(amountOrAddpercent.localeCompare("amount") == 0)
        $.ajax({
            url: '/ordersamount',
            method: 'patch',
            data: {table_id: $('h1').data('id'), food_id: id, amount: newVal},
            success: function (res) {

            }
        })
    else
        $.ajax({
            url: '/ordersaddpercent',
            method: 'patch',
            data: {table_id: $('h1').data('id'), food_id: id, add_percent: newVal},
            success: function (res) {

            }
        })
}

//$("#emptyBtn").click(function(){
$(document).on('click', '#emptyBtn', function () {
    $.ajax({
        url: '/table',
        method: 'DELETE',
        data: {table_id: $('h1').data('id')},
        success: function (res) {
            $('h2').text('0€');
            $('tbody').html('<tr><td colspan="6">Nessun ordine.</td></tr>');
        }
    })
});

$(document).on('click', '#gpdf', function () {

});

$(document).on('click', '#anteprima', function (event) {

    var formDati = $('#formdatipreventivo');
    var isvalidate = formDati[0].checkValidity();
    if (isvalidate) {
        // Cose del Gando
        event.preventDefault();
        // Trasforma un array di 3 elementi, ognuno array associativo,
        // in un unico array associativo con 3 indici e 3 valori
        var dati = formDati.serializeArray();

        var arrayAssociativo = [];
        for (var i = 0; i < dati.length; i++) {
            arrayAssociativo[dati[i]['name']] = dati[i]['value'];
        }

    }
    var obj = $.extend({}, arrayAssociativo);

    var urlPathname = window.location.pathname;
    console.log('Creato array associativo: ' + urlPathname); // /table/1
    alert(JSON.stringify(obj, null, 4));

    jQuery.ajax({
        url: urlPathname,
        method: 'POST',
        data: obj,
        success: function (result) {
            // jQuery('.alert').show();
            // jQuery('.alert').html(result.success);
            var id = window.location.href;

            id = id.split("/");
            console.log("Chiamata Ajax effettuata con successo. Spostamento alla pagina di anteprima " + id[id.length - 1]);
            window.location = '/pdf_view/' + id[id.length - 1];
        },
        error: function (xhr, status, error) {
            console.log("Chebrutto :((((((((((");
        }
    });

});

$(document).on('change', '.amount', function (event) {
    var newVal = $(event.target).val();

    //recupero id del prodotto
    var target = $(event.target);
    var tr = target.parents('tr');
    var id = tr.children('th').text();

    console.log("Cambio numero. Id: " + id + " newVal: " + newVal);
    if (newVal == 0) {
        console.log('Cancellazione ordine: ' + id);
        deleteFood(id);
    } else {
        updateOrder(id, newVal, "amount");
    }
});

$(document).on('change', '.add_percent', function (event) {
    var newVal = $(event.target).val();

    //recupero id del prodotto
    var target = $(event.target);
    var tr = target.parents('tr');
    var id = tr.children('th').text();

    console.log("Cambio numero. Id: " + id + " newVal: " + newVal);

    updateOrder(id, newVal, "addpercent");
});


function compare(a, b) {
    if (a.chapter < b.chapter) {
        return -1;
    }
    if (a.chapter > b.chapter) {
        return 1;
    }
    return 0;
}
