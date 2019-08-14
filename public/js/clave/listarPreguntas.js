$('[data-preguntas]').click(function(){
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
      html_preguntas = '<strong><h3>Esta clave no contiene preguntas</h3></strong>';
      $('#listar-preguntas').html(html_preguntas);
    }

  });

  $('#listarPreguntasClaveArea').modal('show');
});