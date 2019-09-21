$(function(){
  $('[data-id-evaluacion]').on('click', desplegarTurno);
});

function desplegarTurno(){
  var id_evaluacion = $(this).data('id-evaluacion');
  //AJAX
  $.get('/api/evaluacion/'+id_evaluacion+'/turnos', function(data){
    var html_modal1 ='';
    var html_modal2 ='';
    var turnos = data['turnos'];
    var checked = false;
    var turnos_publicados = false;
    var turnos_no_publicados = false;

    //Verifica si vienen turnos de la consulta
    if(turnos.length>0){
      for (var i = 0; i < turnos.length; ++i) {

        //Verifica si alguno de los turnos ya hizo publicado
        for (var j = 0; j < turnos.length; j++) {
          if(turnos[i].visibilidad==1) checked = true;
        }

      //Si el turno ya ha sido publicado no le permitirá volverlo a publicar
      if(checked){
        turnos_publicados = true;
        html_modal1 += '- '+
       '<label name="turnospublicos[]" '+
          'value="'+turnos[i].id+'"> <strong> Inicio: </strong>'+
          turnos[i].fecha_inicio_turno+
          '<strong> Final : </strong>'+
          turnos[i].fecha_final_turno+
       '</label> <br>';
      }else{
        turnos_no_publicados = true;
        html_modal2 += ' '+
       '<label>'+
        '<input class="ml-2" type="checkbox" name="turnosnopublicos[]" '+
          'value="'+turnos[i].id+'"> <strong> Inicio: </strong>'+
          turnos[i].fecha_inicio_turno+ 
          '<strong> Final : </strong>'+
          turnos[i].fecha_final_turno+ 
       '</label> <br>';
      }

       checked=false;
    }
    }
     if(turnos_publicados == false){
        html_modal1 = '<div class="alert alert-info">Esta evaluación no posee turnos publicos</div>'
       }
     if(turnos_no_publicados == false){
      html_modal2 = '<div class="alert alert-info">Esta evaluación no posee turnos sin publicar</div>'
     }

    //Asignando el resultado de la consulta al body del modal
  $('#desplegar-turnos-publicos').html(html_modal1);
     //Asignando el resultado de la consulta al body del modal
  $('#desplegar-turnos-nopublicos').html(html_modal2);

  });

  //Mostrar el modal
  $('#publicarEvaluacion').modal('show');

  }
  
