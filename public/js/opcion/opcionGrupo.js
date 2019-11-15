$(document).ready(function() {

    var btn_agregar = $('#btn-agregar')
    var btn_crear = $('#btn-crear')
    var btn_guardar = $('#btn-guardar')

    var incorrecta = $('#incorrecta')
    var incorrecta_edit = $('#incorrecta-edit')
    
    var alerta = $('#alerta')
    var alerta_edit = $('#alerta-edit')

    incorrecta.hide()
    incorrecta_edit.hide()


    $('#editModal').on('show.bs.modal', function(event){

        var link = $(event.relatedTarget)

        var idPregunta = link.data('id-pregunta')
        var idOpcion = link.data('id-opcion')
        var pregunta = link.data('pregunta')
        var opcion = link.data('opcion')
        var opcionIncorrecta = link.data('opcion-incorrecta')

        var modal = $(this)

        modal.find('.modal-body #idPregunta').val(idPregunta)
        modal.find('.modal-body #idOpcion').val(idOpcion)
        modal.find('.modal-body #pregunta').val(pregunta)
        modal.find('.modal-body #opcion').val(opcion)
        modal.find('.modal-body #opcionincorrectaedit').val(opcionIncorrecta)
    });

    $('#deleteModal').on('show.bs.modal', function(event){

        var link = $(event.relatedTarget)

        var idPregunta = link.data('id-pregunta')
        var idOpcion = link.data('id-opcion')

        var modal = $(this)

        modal.find('.modal-footer #idPregunta').val(idPregunta)
        modal.find('.modal-footer #idOpcion').val(idOpcion)
    });

    // $('#btn-incorrecta').on('click',function(event){

    //     event.preventDefault()

    //     $('#btn-incorrecta').hide()
    //     incorrecta.show()

    // });

    $('#btn-incorrecta-edit').on('click',function(event){

        event.preventDefault()

        $('#btn-incorrecta-edit').hide()
        incorrecta_edit.show()

    });    

    // $('#cancelar-incorrecta').on('click',function(event){

    //     event.preventDefault()

    //     $('#opcionincorrecta').val("")

    //     $('#btn-incorrecta').show()
    //     incorrecta.hide()

    // });

    $('#eliminar-incorrecta-edit').on('click',function(event){

        event.preventDefault()

        $('#opcionincorrectaedit').val("")

        $('#btn-incorrecta-edit').show()
        incorrecta_edit.hide()

    });

    $('.btn-editar').on('click',function(event){

        var opcion_incorrecta = ($(this).attr('data-opcion-incorrecta'));

        alerta_edit.hide()

        if(opcion_incorrecta!=''){
            incorrecta_edit.show()
            $('#btn-incorrecta-edit').hide()
        }else{
            incorrecta_edit.hide()
            $('#btn-incorrecta-edit').show()
        }

    });

    btn_agregar.on('click',function(event){

        alerta.hide()

    });

    btn_crear.click(function(e){

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
                
                btn_crear.attr("disabled", true)
                location.reload(true)

            }else{

                alerta.show()

                /*Limpiamos la lista del Div de Alerta.*/
                var child = document.getElementById("ul-alert").lastElementChild
                while (child) {
                    document.getElementById("ul-alert").removeChild(child)
                    child = document.getElementById("ul-alert").lastElementChild
                }

                /*Gestionamos cada tipo de error usando condicionales.*/
                if(datos.errors.pregunta){

                    for (var i = datos.errors.pregunta.length - 1; i >= 0; i--) {

                        var li = document.createElement('li')
                        liContent = document.createTextNode(datos.errors.pregunta[i])
                        li.appendChild(liContent)
                        document.getElementById("ul-alert").appendChild(li)
                        
                    }
                }

                if (datos.errors.opcion) {
                
                    for (var j = datos.errors.opcion.length - 1; j >= 0; j--) {
                        
                        var li = document.createElement('li')
                        liContent = document.createTextNode(datos.errors.opcion[j])
                        li.appendChild(liContent)
                        document.getElementById("ul-alert").appendChild(li)
                    }
                }
            }

        }).fail(function(xhr, status, e) {
            console.log(e)
        })

    });

    // btn_guardar.click(function(e){
        
    //     e.preventDefault()

    //     var form = $(this).parents('form')
    //     var url = form.attr('action')

    //     $.ajax({
    //         type: 'POST',
    //         url: url,
    //         data: form.serialize(),
    //         dataType: "json"
    //     }).done(function(datos){

    //         if (!(datos.errors)){
                
    //             btn_guardar.attr("disabled", true)
    //             location.reload(true)

    //         }else{

    //             alerta_edit.show()

    //             /*Limpiamos la lista del Div de Alerta.*/
    //             var child = document.getElementById("ul-alert-edit").lastElementChild
    //             while (child) {
    //                 document.getElementById("ul-alert-edit").removeChild(child)
    //                 child = document.getElementById("ul-alert-edit").lastElementChild
    //             }

    //             /*Gestionamos cada tipo de error usando condicionales.*/
    //             if(datos.errors.pregunta){

    //                 for (var i = datos.errors.pregunta.length - 1; i >= 0; i--) {

    //                     var li = document.createElement('li')
    //                     liContent = document.createTextNode(datos.errors.pregunta[i])
    //                     li.appendChild(liContent)
    //                     document.getElementById("ul-alert-edit").appendChild(li)
                        
    //                 }
    //             }

    //             if (datos.errors.opcion) {
                
    //                 for (var j = datos.errors.opcion.length - 1; j >= 0; j--) {
                        
    //                     var li = document.createElement('li')
    //                     liContent = document.createTextNode(datos.errors.opcion[j])
    //                     li.appendChild(liContent)
    //                     document.getElementById("ul-alert-edit").appendChild(li)
    //                 }
    //             }
    //         }
    //     }).fail(function(xhr, status, e) {
    //         console.log(e)
    //     })

    // });

    $('#btn-incorrecta').click(function(e){

        //Contador de las opciones incorrectas que se agregaron. Aumentando su valor en uno.
        var contador_incorrectas = document.getElementById("incorrectas-contador");
        contador_incorrectas.value++;

        e.preventDefault();

        //Creación de div que contendrá input para opción incorrecta.
        var incorrecta_div = document.createElement('div');
        incorrecta_div.id = 'incorrecta-div'+contador_incorrectas.value;                //Asignando id, tomando en cuenta el valor actual del contador.
        incorrecta_div.setAttribute('class','col-form-label my-1 py-0');                //Asignando clase de bootstrap.
        document.getElementById('incorrectas-div').appendChild(incorrecta_div);         //Asignado el nuevo div a el div que contiene a todas las opciones incorrectas.

        //Creación de Label para identificar que número de opción incorrecta se está agregando.
        var incorrecta_label = document.createElement('label');
        incorrecta_label.setAttribute('class','col-form-label my-1 py-0');

        //Creación de Input para envio de opcion incorrecta
        var incorrecta_input = document.createElement('input');
        incorrecta_input.setAttribute('id','incorrecta'+contador_incorrectas.value);    //Asignando id, tomando en cuenta el valor actual del contador.
        incorrecta_input.setAttribute('name','incorrecta'+contador_incorrectas.value);  //Asignando name, tomando en cuenta el valor actual del contador.
        incorrecta_input.setAttribute('type','text');                                   //Asignando tipo de Input.
        incorrecta_input.setAttribute('class','form-control');                          //Asignando clase de bootstrap.
        incorrecta_input.setAttribute('placeholder','Inserte texto de la Respuesta Incorrecta.');                          //Asignando clase de bootstrap.

        incorrecta_label.setAttribute('for','incorrecta'+contador_incorrectas.value);

        document.getElementById('incorrecta-div'+contador_incorrectas.value).appendChild(incorrecta_label);

        incorrecta_label.innerHTML='Respuesta Incorrecta '+contador_incorrectas.value+':';

        document.getElementById('incorrecta-div'+contador_incorrectas.value).appendChild(incorrecta_input);

    });

});