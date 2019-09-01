$(document).ready(function() {

    $('#datetimepicker1').datetimepicker({
        format: 'DD/MM/YYYY h:mm A',
    });
    
    $('#datetimepicker2').datetimepicker({
        format: 'DD/MM/YYYY h:mm A'
    });
    
    $('#datetimepicker2input').focus(function(){
        var evaluacion_id = $('#datetimepicker1input').data('evaluacion_id');
        var fecha_hora_inicio = $('#datetimepicker1input').val();
        
        $.ajax({
            url: '/api/evaluacion/'+evaluacion_id+'/duracion/',
            type: 'GET',
            success: function(data){
                
                var date_margen = moment(fecha_hora_inicio,'DD/MM/YYYY h:mm A').add(data,'hours').add(10,'minutes').format('DD/MM/YYYY h:mm A');
                
                $('#datetimepicker2').datetimepicker('minDate', moment(date_margen,'DD/MM/YYYY h:mm A'));
            },
            error: function(xhr, status, error) {
                console.log('status:'+status+', error:'+error);
            }
        });

    });

});