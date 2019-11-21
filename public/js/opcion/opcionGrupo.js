$(document).ready(function() {

    var btn_crear = $('#btn-crear');
    var btn_incorrecta = $('#btn-incorrecta');
    var btn_incorrecta_edit = $('#btn-incorrecta-edit');
    var btn_guardar = $('#btn-guardar');

    var div_alerta = $('#div-alerta');
    var div_alerta_edit = $('#div-alerta-edit');
    div_alerta.hide();
    div_alerta_edit.hide();

    //Evento para crear una nueva Pregunta en Grupo Emparejamiento, haciendo referencia a la URL definida en Formulario.
    btn_crear.click(function(e){

        e.preventDefault();

        var form = $(this).parents('form');
        var url = form.attr('action');

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            dataType: "json"
        }).done(function(datos){

            if(!(datos.errors)){
                
                btn_crear.attr("disabled", true);
                location.reload(true);

            }else{

                div_alerta.show();

                /*Limpiamos la lista del Div de Alerta.*/
                var child = document.getElementById("ul-alert").lastElementChild;
                while (child) {
                    document.getElementById("ul-alert").removeChild(child);
                    child = document.getElementById("ul-alert").lastElementChild;
                }

                /*Gestionamos cada tipo de error usando condicionales.*/
                if(datos.errors.pregunta){

                    for (var i = datos.errors.pregunta.length - 1; i >= 0; i--) {

                        var li = document.createElement('li');
                        var liContent = document.createTextNode(datos.errors.pregunta[i]);
                        li.appendChild(liContent);
                        document.getElementById("ul-alert").appendChild(li);
                        
                    }
                }

                if (datos.errors.opcion) {
                
                    for (var j = datos.errors.opcion.length - 1; j >= 0; j--) {
                        
                        var li = document.createElement('li');
                        var liContent = document.createTextNode(datos.errors.opcion[j]);
                        li.appendChild(liContent);
                        document.getElementById("ul-alert").appendChild(li);
                    }
                }
            }

        }).fail(function(xhr, status, e) {
            console.log(e);
        });

    });

    //Evento al ocultar Modal de Edición, se vacia el contenido de Opciones Incorrectas, así se evita conflicto.
    $('#create-modal').on('hidden.bs.modal', function (event) {
        document.getElementById('incorrectas-div').innerHTML="";
    });

    //Evento para agregar Input de nueva Opción/Respuesta Incorrecta.
    btn_incorrecta.click(function(e){

        e.preventDefault();

        //Contador de las opciones incorrectas que se agregaron. Aumentando su valor en uno.
        var contador_incorrectas = document.getElementById("incorrectas-contador");
        contador_incorrectas.value++;

        //Creación de div que contendrá input para opción incorrecta.
        var incorrecta_div = document.createElement('div');
        incorrecta_div.id = 'incorrecta-div'+contador_incorrectas.value;                //Asignación de id, tomando en cuenta el valor actual del contador.
        incorrecta_div.setAttribute('class','col-form-label my-1 py-0');                //Asignación de clase de bootstrap.
        document.getElementById('incorrectas-div').appendChild(incorrecta_div);         //Asignación de el nuevo div a el div que contiene a todas las opciones incorrectas.

        //Creación de Label para identificar que número de opción incorrecta se está agregando.
        var incorrecta_label = document.createElement('label');
        incorrecta_label.setAttribute('class','col-form-label my-1 py-0 text-danger');

        //Creación de Input para envio de opcion incorrecta
        var incorrecta_input = document.createElement('input');
        incorrecta_input.setAttribute('id','incorrecta'+contador_incorrectas.value);    //Asignación de id, tomando en cuenta el valor actual del contador.
        incorrecta_input.setAttribute('name','incorrecta'+contador_incorrectas.value);  //Asignación de name, tomando en cuenta el valor actual del contador.
        incorrecta_input.setAttribute('type','text');                                   //Asignación de tipo de Input.
        incorrecta_input.setAttribute('class','form-control');                          //Asignación de clase de bootstrap.
        incorrecta_input.setAttribute('placeholder','Inserte texto de la Respuesta Incorrecta.');   //Asignación de placeholder a Input.

        incorrecta_label.setAttribute('for','incorrecta'+contador_incorrectas.value);   //Asignación de for a Label para hacer referencia a Input.

        document.getElementById('incorrecta-div'+contador_incorrectas.value).appendChild(incorrecta_label); //Asignación de Label dentro de div que contiene la opción incorrecta.

        incorrecta_label.innerHTML='Respuesta Incorrecta '+contador_incorrectas.value+':';  //Ingreso de Texto de Label.

        document.getElementById('incorrecta-div'+contador_incorrectas.value).appendChild(incorrecta_input); //Asignación de Input dentro de div.

    });

    //Evento al mostrar Modal de Edición, se llena Formulario de acuerdo a pregunta seleccionada.
    $('#edit-modal').on('show.bs.modal', function(event){

        var link = $(event.relatedTarget);

        var id_pregunta = link.data('id-pregunta');
        var id_correcta = link.data('id-correcta');
        contador = link.data('total-incorrectas');

        var pregunta = link.data('pregunta');
        var correcta = link.data('correcta');


        var modal = $(this);

        modal.find('.modal-body #id-pregunta').val(id_pregunta);
        modal.find('.modal-body #id-correcta').val(id_correcta);
        modal.find('.modal-body #incorrectas-contador-edit').val(contador);

        modal.find('.modal-body #pregunta-edit').val(pregunta);
        modal.find('.modal-body #correcta-edit').val(correcta);
        

        

        if(contador > 0){

            for(var i = 0; i < contador; i++){

                var incorrecta_div = document.createElement('div');
                incorrecta_div.id = 'incorrecta-div-edit'+(i+1);
                incorrecta_div.setAttribute('class','col-form-label my-1 py-0');
                document.getElementById('incorrectas-div-edit').appendChild(incorrecta_div);

                var incorrecta_label = document.createElement('label');
                incorrecta_label.setAttribute('class','col-form-label my-1 py-0 text-danger');

                var incorrecta_input = document.createElement('input');
                incorrecta_input.setAttribute('id','incorrecta'+(i+1));
                incorrecta_input.setAttribute('name','incorrecta'+(i+1));
                incorrecta_input.setAttribute('type','text');
                incorrecta_input.setAttribute('class','form-control');

                var id_incorrecta_input = document.createElement('input');
                id_incorrecta_input.setAttribute('id','id-incorrecta'+(i+1));
                id_incorrecta_input.setAttribute('name','id_incorrecta'+(i+1));
                id_incorrecta_input.setAttribute('type','text');
                id_incorrecta_input.setAttribute('class','form-control d-none');

                id_incorrecta_input.setAttribute('value',link.data('id-incorrecta-'+(i+1)));

                incorrecta_input.setAttribute('value',link.data('incorrecta-'+(i+1)));

                incorrecta_label.setAttribute('for','incorrecta'+(i+1));

                document.getElementById('incorrecta-div-edit'+(i+1)).appendChild(incorrecta_label);
                incorrecta_label.innerHTML='Respuesta Incorrecta '+(i+1)+':';
                document.getElementById('incorrecta-div-edit'+(i+1)).appendChild(incorrecta_input);
                document.getElementById('incorrecta-div-edit'+(i+1)).appendChild(id_incorrecta_input);

            }

        }

    });

    //Evento al ocultar Modal de Edición, se vacia el contenido de Opciones Incorrectas, así se evita conflicto.
    $('#edit-modal').on('hidden.bs.modal', function (event) {
        document.getElementById('incorrectas-div-edit').innerHTML="";
    });

    //Evento para agregar Input de nueva Opción/Respuesta Incorrecta.
    btn_incorrecta_edit.click(function(e){

        e.preventDefault();

        var contador_incorrectas = document.getElementById("incorrectas-contador-edit");
        contador_incorrectas.value++;

        //Creación de div que contendrá input para opción incorrecta.
        var incorrecta_div = document.createElement('div');
        incorrecta_div.id = 'incorrecta-div-edit'+contador_incorrectas.value;                //Asignación de id, tomando en cuenta el valor actual del contador.
        incorrecta_div.setAttribute('class','col-form-label my-1 py-0');                //Asignación de clase de bootstrap.
        document.getElementById('incorrectas-div-edit').appendChild(incorrecta_div);         //Asignación de el nuevo div a el div que contiene a todas las opciones incorrectas.

        //Creación de Label para identificar que número de opción incorrecta se está agregando.
        var incorrecta_label = document.createElement('label');
        incorrecta_label.setAttribute('class','col-form-label my-1 py-0 text-danger');

        //Creación de Input para envio de opcion incorrecta
        var incorrecta_input = document.createElement('input');
        incorrecta_input.setAttribute('id','incorrecta'+contador_incorrectas.value);    //Asignación de id, tomando en cuenta el valor actual del contador.
        incorrecta_input.setAttribute('name','incorrecta'+contador_incorrectas.value);  //Asignación de name, tomando en cuenta el valor actual del contador.
        incorrecta_input.setAttribute('type','text');                                   //Asignación de tipo de Input.
        incorrecta_input.setAttribute('class','form-control');                          //Asignación de clase de bootstrap.
        incorrecta_input.setAttribute('placeholder','Inserte texto de la Respuesta Incorrecta.');   //Asignación de placeholder a Input.

        incorrecta_label.setAttribute('for','incorrecta'+contador_incorrectas.value);   //Asignación de for a Label para hacer referencia a Input.

        document.getElementById('incorrecta-div-edit'+contador_incorrectas.value).appendChild(incorrecta_label); //Asignación de Label dentro de div que contiene la opción incorrecta.

        incorrecta_label.innerHTML='Respuesta Incorrecta '+contador_incorrectas.value+':';  //Ingreso de Texto de Label.

        document.getElementById('incorrecta-div-edit'+contador_incorrectas.value).appendChild(incorrecta_input); //Asignación de Input dentro de div.

    });

    //Evento editar Pregunta de Grupo.
    btn_guardar.click(function(e){

        e.preventDefault();

        var form = $(this).parents('form');
        var url = form.attr('action');

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            dataType: "json"
        }).done(function(datos){

            if(!(datos.errors)){
                
                btn_guardar.attr("disabled", true);
                location.reload(true);

            }else{

                div_alerta_edit.show();

                /*Limpiamos la lista del Div de Alerta.*/
                var child = document.getElementById("ul-alert-edit").lastElementChild;
                while (child) {
                    document.getElementById("ul-alert-edit").removeChild(child);
                    child = document.getElementById("ul-alert-edit").lastElementChild;
                }

                /*Gestionamos cada tipo de error usando condicionales.*/
                if(datos.errors.pregunta_edit){

                    for (var i = datos.errors.pregunta_edit.length - 1; i >= 0; i--) {

                        var li = document.createElement('li');
                        var liContent = document.createTextNode(datos.errors.pregunta_edit[i]);
                        li.appendChild(liContent);
                        document.getElementById("ul-alert-edit").appendChild(li);
                        
                    }
                }

                if (datos.errors.correcta_edit) {
                
                    for (var j = datos.errors.correcta_edit.length - 1; j >= 0; j--) {
                        
                        var li = document.createElement('li');
                        var liContent = document.createTextNode(datos.errors.correcta_edit[j]);
                        li.appendChild(liContent);
                        document.getElementById("ul-alert-edit").appendChild(li);
                    }
                }
            }

        }).fail(function(xhr, status, e) {
            console.log(e);
        });

    });

    $('#delete-modal').on('show.bs.modal', function(event){

        var link = $(event.relatedTarget);

        var id_pregunta = link.data('id-pregunta');


        var modal = $(this);

        modal.find('.modal-body #id-pregunta-delete').val(id_pregunta);

    });

});