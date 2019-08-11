var $jq = jQuery.noConflict();
$jq(document).ready(function() {

    $jq('#datetimepicker1').datetimepicker({
        format: 'DD/MM/YYYY h:mm A',
    });

    $jq('#datetimepicker1input').blur(function(){

        var evaluacion_id = $jq(this).data('evaluacion_id');
        var fecha_hora_inicio = $jq(this).val();
        
        if(fecha_hora_inicio == ""){
            $jq('#datetimepicker2input').val("");
            return;
        }

        $jq.ajax({
            url: '/api/evaluacion/'+evaluacion_id+'/duracion/',
            type: 'GET',
            success: function(data){ 
                var date = moment(fecha_hora_inicio,'DD/MM/YYYY h:mm A').add(data,'hours').format('DD/MM/YYYY h:mm A');
                $jq('#datetimepicker2input').val(date);
            },
            error: function(xhr, status, error) {
                console.log('status:'+status+', error:'+error);
            }
        });

    });

});