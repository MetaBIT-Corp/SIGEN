$(function(){
  $('[data-editar-ca]').on('click', editarClaveArea);
  $('[data-eliminar-ca]').on('click', eliminarClaveArea);
});


function editarClaveArea(){
  var cantidad_preguntas = $(this).parent().parent().find('#id_cantidad').text();
  var peso = $(this).parent().parent().find('#id_peso').text();
  var aleatorio = $(this).data('aleatorio');
  var id_clave_area = $(this).parent().siblings('input').val();

  if(aleatorio==1){
    $('#cantidad_preguntas_id').attr('value', cantidad_preguntas);
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