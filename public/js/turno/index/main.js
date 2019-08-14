function modal(id, evaluacion_id, fecha_inicio_turno, fecha_final_turno){
        document.getElementById('delete_form').action = '/evaluacion/' + evaluacion_id + '/turnos/' + id;
        document.getElementById('p_mensaje_body').innerHTML = "¿Está seguro que desea eliminar el turno de <b>"+fecha_inicio_turno+"</b> a <b>"+fecha_final_turno+"</b>?";
        $("#modal_delete").modal();
    }