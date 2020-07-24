$('[data-user-id]').on('click', function(){
  var title = $('#titleId');
  var body = $('#bodyId');
  var id_user = $(this).data('user-id');
  var carnet = $(this).data('user-carnet');
  var locked = $(this).val();
  $('#user_id').val(id_user);

  if(locked == 1){
    title.text('Desbloquear usuario');
    body.html('¿Está seguro que desea desbloquear al usuario con carnet <em><b>' + carnet + '</b></em>?');
    $('#btnConfirm').prop('class', "btn btn-primary");
  }else{
    title.text('Bloquear usuario');
    body.html('¿Está seguro que desea bloquear al usuario con carnet <em><b>' + carnet + '</b></em>?');
    $('#btnConfirm').prop('class', "btn btn-danger");
  }

  $('#modal_change_state').modal('show');
})

setTimeout(function() {
    $(".alert").fadeOut();           
},3000);