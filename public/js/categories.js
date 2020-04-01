$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function newCat(){
    var nomeCat = new FormData($('#categoriaForm')[0]);


    $.ajax({
        url:'/categories',
        method: "POST",
        data: nomeCat,
        cache: false,
        processData: false,
        contentType: false,
        success: function(cat) {
            console.log(cat);
            $('#catTable tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + cat.id + '</th><td>' + cat["name"] + '</td><td class="text-md-right"> <button type="button" id="deleteCat" class="btn btn-outline-danger mr-2"><i class="far fa-trash-alt"></i></button></td></tr>');
        }
    });
}

function newSec(){
    var nomeSez = new FormData($('#capitoloForm')[0]);
    // Cambiare il sezcat = cat, prenderlo in input

    $.ajax({
        url:'/categories/create_section',
        method: "POST",
        data: nomeSez,
        cache: false,
        processData: false,
        contentType: false,
        success: function(cat) {
            console.log(cat);
            $('#secTable tbody').append('<tr><th scope="row" class="d-none d-md-table-cell">' + cat.id + '</th><td>' + cat["name"] + '</td><td class="text-md-right"> <button type="button" id="deleteCat" class="btn btn-outline-danger mr-2"><i class="far fa-trash-alt"></i></button></td></tr>');
        }


    });

    //delete food
    $("#deleteSec").on("click" , "tr .btn-outline-danger", function(event){

        var resp = confirm("Confermare la cancellazione?");
        if(resp == true){
            var target = $(event.target);
            console.log(target.parents('tr'));

            var tr = target.parents('tr');
            console.log(tr.children('th').text());

            $.ajax({
                url: '/categories',
                method: "DELETE",
                data: {id: tr.children('th').text()},
                success: function(){
                    tr.remove();
                }
            })
        }
    })


}
