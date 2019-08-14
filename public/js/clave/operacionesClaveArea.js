$(function(){
  $('[data-editar-ca]').on('click', editarClaveArea);
  $('[data-eliminar-ca]').on('click', eliminarClaveArea);
});


function editarClaveArea(){
  var cantidad_preguntas = $(this).parent().prev().find('#id_cantidad').text();
  var peso = $(this).parent().prev().find('#id_peso').text();
  var id_clave_area = $(this).parent().prev().find('#id_clave_area_edit').val();

  $('#cantidad_preguntas_id').attr('value', cantidad_preguntas);
  $('#peso_ca_id').attr('value', peso);
  $('#id_ca').attr('value', id_clave_area);

  $('#editarClaveArea').modal('show');
}

function eliminarClaveArea(){
	var id_clave_area = $(this).parent().prev().find('#id_clave_area_edit').val();

	$('#id_ca_eliminar').attr('value', id_clave_area);
	$('#eliminarClaveArea').modal('show');
}