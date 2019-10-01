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
    })

});