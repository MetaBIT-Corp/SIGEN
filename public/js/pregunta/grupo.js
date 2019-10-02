$(document).ready(function(){

	var alerta = $('#alerta')

	alerta.hide()

	var btn_agregar = $('#btn-agregar')

	btn_agregar.click(function(e){

        e.preventDefault()

        var form = $(this).parents('form')
        var url = form.attr('action')

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            dataType: "json"
        }).done(function(datos){

            if (!(datos.errors)){
                
                btn_agregar.attr("disabled", true)
                location.reload(true)

            }else{

            	alerta.show()

            	/*Limpiamos la lista del Div de Alerta.*/
                var child = document.getElementById("ul-alert").lastElementChild
                while (child) {
                    document.getElementById("ul-alert").removeChild(child)
                    child = document.getElementById("ul-alert").lastElementChild
                }

                if(datos.errors.descripcion){

                    for (var i = datos.errors.descripcion.length - 1; i >= 0; i--) {

                        var li = document.createElement('li')
                        liContent = document.createTextNode(datos.errors.descripcion[i])
                        li.appendChild(liContent)
                        document.getElementById("ul-alert").appendChild(li)
                        
                    }
                }

            }
        }).fail(function(xhr, status, e) {
            console.log(e)
        })
    });

    $('#editmodal').on('show.bs.modal', function(event){

        var link = $(event.relatedTarget)

        var id_grupo = link.data('id-grupo')

        var modal = $(this)

        modal.find('.modal-body #grupoid').val(id_grupo)
        modal.find('.modal-body #descripcionedit').val(link.data('descripcion'))

    });

    $('#areas').on('click', '#btn_editar', function (){

        $('#alertaedit').hide()

    });

    $('#btn-edit-ge').click(function(e){

        e.preventDefault()

        var form = $(this).parents('form')
        var url = form.attr('action')

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            dataType: "json"
        }).done(function(datos){

            if (!(datos.errors)){
                
                $('#btn-edit-ge').attr("disabled", true)
                location.reload(true)

            }else{

                $('#alertaedit').show()

                /*Limpiamos la lista del Div de Alerta.*/
                var child = document.getElementById("ul-alert-edit").lastElementChild
                while (child) {
                    document.getElementById("ul-alert-edit").removeChild(child)
                    child = document.getElementById("ul-alert-edit").lastElementChild
                }

                if(datos.errors.descripcionedit){

                    for (var i = datos.errors.descripcionedit.length - 1; i >= 0; i--) {

                        var li = document.createElement('li')
                        liContent = document.createTextNode(datos.errors.descripcionedit[i])
                        li.appendChild(liContent)
                        document.getElementById("ul-alert-edit").appendChild(li)
                        
                    }
                }

            }
        }).fail(function(xhr, status, e) {
            console.log(e)
        })
    })

});