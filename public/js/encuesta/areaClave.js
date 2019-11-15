$(document).ready(function(){

    var btnAsignar = $('.btn-asignacion');

    btnAsignar.click(function(e){           /*On Click para Submit*/
        btnAsignar.attr("disabled", true);

        e.preventDefault();

        var dats = {
            area_id:$(this).attr("data-id-area"),
            clave_id:$(this).attr("data-id-clave"),
            numero_preguntas:$(this).attr("data-preguntas-area"),
            _token:$(this).attr("data-token-a")
        }

        var url = $(this).attr("data-url");

        console.log(url);

        $.ajax({
            type: 'POST',
            url: url,
            data: dats,                     /*Serializamos todos los campos del formulario*/
            dataType: "json"
        }).done(function(datos){
            location.reload(true);
        }).fail(function(xhr, status, e) {
            console.log(e);
        });

    });

    $('#eliminarModal').on('show.bs.modal', function(event){

        var modal = $(this)
        var link = $(event.relatedTarget)
        var idArea = link.data('id-area')
        modal.find('.modal-body #id_clave_area').val(idArea)
    });

});