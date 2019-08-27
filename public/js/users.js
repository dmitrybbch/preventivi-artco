$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

//nuovo utente
function newUser(){

  $.ajax({
    url: '/users',
    method: "POST",
    data: {username: $('#username').val(), password: $('#password').val(), type: $('#adminCheck').is(':checked')},
   success: function(user){
     
    console.log(user);  
    //console.log(user);
      $('#userTable tbody').append('<tr><th scope="row">' + user.id + '</th><td>' + user.username + '</td><td>' + user.type + '</td><td><button type="button" class="btn btn-outline-danger mr-2"> <i class="far fa-trash-alt"></i></button> <button type="button" class="btn btn-outline-info"><i class="far fa-edit"></i></button></td></tr>');
    },

    error: function(data) {
      if( data.status === 422 ) {
        var res = $.parseJSON(data.responseText);

        if(res.errors.password && res.errors.username)
          alert(res.errors.password[0] + res.errors.username[0])
        if(res.errors.password && !res.errors.username)
          alert(res.errors.password[0]);
        if(!res.errors.username && res.errors.username)
          alert(res.errors.username[0]);

      }
    }
  })
}

//rimuovi utente
$(document).on("click" , "tr .btn-outline-danger", function(event){


  var target = $(event.target);
  console.log(target.parents('tr'));

  var tr = target.parents('tr');
  //console.log(tr.children('th').text());

  $.ajax({
    url: '/users',
    method: "DELETE",
    data: {id: tr.children('th').text() },
    success: function(res){
      if(res.messaggio == 'utente eliminato'){
        alert(res.messaggio);
        tr.remove();
      }
      else{
        alert(res.messaggio);
      }
    }
  })
}
)

var temptr;


//modfica utente
$(document).on("click" , "tr .btn-outline-info", function(event){
  
  var target = $(event.target);
  console.log(target.parents('tr'));

  var tr = target.parents('tr');
  console.log(tr.children('th').text());
  temptr = tr;
  //recupero valori dell'utente
  var id = tr.children('th').text();
  var username = tr.children('td').eq(0).text();
  var type = tr.children('td').eq(1).text();
  var password = tr.children('td').eq(2).text();

  $('#idModal').val(id);
  $('#usernameModal').val(username);
  if(type=="admin"){
    $('#adminCheckModal').prop('checked', true);
  }
  else{
    $('#adminCheckModal').prop('checked', false);
  }
  $('#passwordModal').val(password);
  $('#userModal').modal("show");
})

//conferma modifica utente
$(document).on("click" , "#btnSave", function(){
//$('#btnSave').on('click' , function(){
  var id = $('#idModal').val();
  var username = $('#usernameModal').val();
  var password = $('#passwordModal').val();
  var type = $('#adminCheckModal').prop('checked');

  $.ajax({
    url: '/users',
    method: "PATCH",
    data: {id : id , username : username , password : password , type : type },
    success: function(res){
      temptr.children('td').eq(0).text(res.username);
      temptr.children('td').eq(1).text(res.type);
    }
  })

  $('#userModal').modal('toggle');
})