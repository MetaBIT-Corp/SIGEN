$(function(){
  $('[data-preguntas]').on('click', listarPregntas);
  $('[data-preguntas-emp]').on('click', listarPregntasEmparejamiento);
  $('[data-id-clave-area]').on('click', cargarPreguntas);
  $('[data-id-clave-area-emp]').on('click', cargarPreguntasEmparejamiento);
  $('#todas').on('click', seleccionarTodas);
});

function listarPregntas(){
  var id_clave_area = $(this).data('preguntas');

  //AJAX
  $.get('/api/preguntas-agregadas/'+id_clave_area, function(data){
    html_preguntas = '';

    if(data.length > 0 ){
      for (var i = 0; i < data.length; i++) {
        html_preguntas += '<strong>'+(i+1)+'. '+data[i].pregunta+'</strong>';

        if(i<data.length-1) html_preguntas += '<hr>';
      }

      $('#listar-preguntas').html(html_preguntas);

    }else{
      html_preguntas = '<strong><h3>No hay preguntas asignadas</h3></strong>';
      $('#listar-preguntas').html(html_preguntas);
    }

  });

  $('#listarPreguntasClaveArea').modal('show');
}

function listarPregntasEmparejamiento(){
  var id_clave_area = $(this).data('preguntas-emp');

  //AJAX
  $.get('/api/preguntas-agregadas-emp/'+id_clave_area, function(data){
    html_preguntas = '';

    if(data.length > 0 ){
      for (var i = 0; i < data.length; i++) {
        html_preguntas += '<strong>'+(i+1)+'. '+data[i].descripcion_grupo_emp+'</strong>';

        if(i<data.length-1) html_preguntas += '<hr>';
      }

      $('#listar-preguntas').html(html_preguntas);

    }else{
      html_preguntas = '<strong><h3>No hay preguntas asignadas</h3></strong>';
      $('#listar-preguntas').html(html_preguntas);
    }

  });

  $('#listarPreguntasClaveArea').modal('show');
}

function cargarPreguntas(){
  var id_clave_area = $(this).data('id-clave-area');

  $("#todas").prop("checked", false); //Quitar check de 'Seleccionar Todas'
  $('#id_clave_area_add').attr("value", id_clave_area);
  $('#id_clave_area_add_emp').removeAttr("value");

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
        '<input type="checkbox" class="cb-seleccion" checked="true" name="preguntas[]" '+
          'value="'+preguntas[i].id+'"> '+
         preguntas[i].pregunta+
       '</label>';
      }else{
        html_modal += i+1+'. '+
       '<label>'+
        '<input type="checkbox" class="cb-seleccion" name="preguntas[]" '+
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
}

function cargarPreguntasEmparejamiento(){
  var id_clave_area = $(this).data('id-clave-area-emp');

  $('#id_clave_area_add').attr("value", id_clave_area);
  $('#id_clave_area_add_emp').attr("value", 3);

  //AJAX
  $.get('/api/area-emparejamiento/'+id_clave_area+'/preguntas', function(data){
    var html_modal ='';
    var preguntas = data['preguntas'];
    var p_asignadas = data['p_asignadas'];
    var checked = false;

    console.log(preguntas);
    console.log(p_asignadas);

    //Verifica si vienen preguntas de la consulta
    if(preguntas.length>0){
      for (var i = 0; i < preguntas.length; ++i) {

        //Verifica si alguna de las preguntas de esa area ya fue asiganada a la clave
        for (var j = 0; j < p_asignadas.length; j++) {
          if(preguntas[i].id==p_asignadas[j].id) checked = true;
        }

      //Si la pregunta ya fue asignala a la calve se marcará con checked=true
      if(checked){
        html_modal += i+1+'. '+
       '<label>'+
        '<input type="checkbox" checked="true" name="preguntasEmp[]" '+
          'value="'+preguntas[i].id+'"> '+
         preguntas[i].descripcion_grupo_emp+
       '</label>';
      }else{
        html_modal += i+1+'. '+
       '<label>'+
        '<input type="checkbox" name="preguntasEmp[]" '+
          'value="'+preguntas[i].id+'"> '+
         preguntas[i].descripcion_grupo_emp+
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
}

function seleccionarTodas(){
  var seleccion = $('.cb-seleccion');

  if($(this).prop('checked')){
    seleccion.prop('checked', true);
  }else{
    seleccion.prop('checked', false);
  }

  /*if(this.checked){
    seleccion.attr('checked', 'true');
  }else{
    seleccion.removeAttr('checked');
  }*/

}