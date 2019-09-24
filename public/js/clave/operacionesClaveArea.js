$(function(){
  $('[data-editar-ca]').on('click', editarClaveArea);
  $('[data-eliminar-ca]').on('click', eliminarClaveArea);
});


function editarClaveArea(){
  var cantidad_preguntas = $(this).parent().parent().find('#id_cantidad').text();
  var peso = $(this).parent().parent().find('#id_peso').text();
  var aleatorio = $(this).data('aleatorio');
  var id_clave_area = $(this).parent().siblings('input').val();

  //AJAX
  $.get('/api/clave-area/'+id_clave_area+'/validar-peso', function(data){
    $('#val_peso').html('<b>Peso actual del turno:</b> '+data);
    $('#val_peso_actual').attr('value', data);

    if(data >= 100){
      $('#val_asignable').html('<b>No se puede asignar un peso mayor al actual</b>');
    }else{
      $('#val_asignable').html('<b>Peso asignable:</b> '+(100-data));
    }
  });

  $('#cantidad_preguntas_id').attr('value', cantidad_preguntas);

  if(aleatorio==1){
    $('#msj_cant_preg').show();
    $('#cantidad_preguntas_id').show();
  }else{
    $('#cantidad_preguntas_id').hide();
    $('#msj_cant_preg').hide();
  }

  $('#peso_ca_id').attr('value', peso);
  $('#id_ca').attr('value', id_clave_area);

  $('#editarClaveArea').modal('show');
}

function eliminarClaveArea(){
	var id_clave_area = $(this).parent().siblings('input').val();

	$('#id_ca_eliminar').attr('value', id_clave_area);
	$('#eliminarClaveArea').modal('show');
}