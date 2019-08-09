$('[data-id-clave-area]').click(function(){
  var id_clave_area = $(this).data('id-clave-area');

  $('#id_clave_area').attr("value", id_clave_area);

  //AJAX
  $.get('/api/area/'+id_clave_area+'/preguntas', function(data){
    var html_modal ='';
    var preguntas = data['preguntas'];
    var p_asignadas = data['p_asignadas'];
    var checked = false;

    //Verifica si vienen preguntas de la consulta
    if(preguntas.length>0){
      for (var i = 0; i < preguntas.length; ++i) {

        //Verifica si alguna de las preguntas de esa area ya fue asiganada a la clave
        for (var j = 0; j < p_asignadas.length; j++) {
          if(preguntas[i].id==p_asignadas[j]) checked = true;
        }

      //Si la pregunta ya fue asignala a la calve se marcará con checked=true
      if(checked){
        html_modal += i+1+'. '+
       '<label>'+
        '<input type="checkbox" checked="true" name="preguntas[]" '+
          'value="'+preguntas[i].id+'"> '+
         preguntas[i].pregunta+
       '</label>';
      }else{
        html_modal += i+1+'. '+
       '<label>'+
        '<input type="checkbox" name="preguntas[]" '+
          'value="'+preguntas[i].id+'"> '+
         preguntas[i].pregunta+
       '</label>';
      }


       $('#modalCenterTitle').html('Seleccione las preguntas del area <em>'+preguntas[0].titulo+'</em> que desea asignar');

       if(i<preguntas.length-1) html_modal += '<hr>';
       checked=false;
    }
    }else{
      html_modal = '<strong><h3>Esta área no contiene preguntas</h3></strong>'
    }

    //Asignando el resultado de la consulta al body del modal
  $('#asignar-preguntas').html(html_modal);

  });

  //Mostrar el modal
  $('#asignarPreguntasClaveArea').modal('show');
});