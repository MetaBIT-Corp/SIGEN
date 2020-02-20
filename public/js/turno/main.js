$(document).ready(function() {
    
    $('#datetimepicker1').datetimepicker({
        format: 'DD/MM/YYYY h:mm A',
        minDate: moment(),
    });
    
    $('#datetimepicker2').datetimepicker({
        format: 'DD/MM/YYYY h:mm A'
    });
    
    if($('#datetimepicker1input').val() == "")
        $('#datetimepicker2input').val("");
    
    $('#datetimepicker1input').blur(function(){

        var evaluacion_id = $(this).data('evaluacion_id');
        var fecha_hora_inicio = $(this).val();


        if(fecha_hora_inicio == ""){
            $('#datetimepicker2input').val("");
            return;
        }

        $.ajax({
            url: '/api/evaluacion/'+evaluacion_id+'/duracion/',
            type: 'GET',
            success: function(data){
                
                var date_margen = moment(fecha_hora_inicio,'DD/MM/YYYY h:mm A').add(data,'minutes').add(10,'minutes').format('DD/MM/YYYY h:mm A');
                
                $('#datetimepicker2input').val(date_margen);
                                
                $('#datetimepicker2').datetimepicker('minDate', moment(date_margen,'DD/MM/YYYY h:mm A'));
            },
            error: function(xhr, status, error) {
                console.log('status:'+status+', error:'+error);
            }
        });

    });

});

function showPass() {
  var x = document.getElementById("exampleInputPassword1");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

function showPassEdit() {
  var x = document.getElementById("contraseña");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

