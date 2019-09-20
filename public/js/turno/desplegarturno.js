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

    //Verifica si vienen turnos de la consulta
    if(turnos.length>0){
      for (var i = 0; i < turnos.length; ++i) {

        //Verifica si alguno de los turnos ya hizo publicado
        for (var j = 0; j < turnos.length; j++) {
          if(turnos[i].visibilidad==1) checked = true;
        }

      //Si el turno ya ha sido publicado no le permitirá volverlo a publicar
      if(checked){
        html_modal1 += i+1+'. '+
       '<label name="turnospublicos[]" '+
          'value="'+turnos[i].id+'">'+
          turnos[i].fecha_inicio_turno+
       '</label>';
      }else{
        html_modal2 += i+1+'. '+
       '<label>'+
        '<input type="checkbox" name="turnosnopublicos[]" '+
          'value="'+turnos[i].id+'"> '+
          turnos[i].fecha_inicio_turno+
       '</label>';
      }


       $('#modalCenterTitle').html('Seleccione los turnos que desea publicar');

       if(i<turnos.length-1) html_modal2 += '<hr>';
       checked=false;
    }
    }else{
      html_modal1 = '<strong><h5>Esta evaluación no posee turnos publicos</h5></strong>'
      html_modal2 = '<strong><h5>Esta evaluación no posee turnos sin publicar</h5></strong>'
    }

    //Asignando el resultado de la consulta al body del modal
  $('#desplegar-turnos-publicos').html(html_modal1);
     //Asignando el resultado de la consulta al body del modal
  $('#desplegar-turnos-nopublicos').html(html_modal2);

  });

  //Mostrar el modal
  $('#publicarEvaluacion').modal('show');

  }
  
