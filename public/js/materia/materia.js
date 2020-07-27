$(document).ready(function(){

    $('#materiaModal').on('show.bs.modal', function(event){

        $('#errorDiv').hide();

        var modal = $(this);
        var link = $(event.relatedTarget);

        var id = link.data('id');
        var materia = link.data('materia');
        var codigo = link.data('codigo');
        var tipo = link.data('tipo');
        var preguntas = link.data('preguntas');


        modal.find('.modal-body #materia-id').val(id);
        modal.find('.modal-body #materia').val(materia);
        modal.find('.modal-body #materia-codigo').val(codigo);
        modal.find('.modal-body #materia-preguntas').val(preguntas);
        if(tipo!=1){
            modal.find('.modal-body #materia-obligatoria').prop("checked", true);
            modal.find('.modal-body #materia-electiva').prop("checked", false);
        }else{
            modal.find('.modal-body #materia-obligatoria').prop("checked", false);
            modal.find('.modal-body #materia-electiva').prop("checked", true);
        }

    });

    $('#materiaEditBtnSubmit').click(function(e){

        e.preventDefault();

        var form = $(this).parents('form');
        var url = form.attr('action');

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            dataType: "json"
        }).done(function(datos){

            if (!(datos.errors)){
                $('#materiaEditBtnSubmit').attr("disabled", true);
                location.reload(true);
            }else{
                
                $('#errorDiv').show();

                /*Limpiamos la lista del Div de Alerta.*/
                var child = document.getElementById("errorUl").lastElementChild;
                while (child) {
                    document.getElementById("errorUl").removeChild(child);
                    child = document.getElementById("errorUl").lastElementChild;
                }

                /*Gestionamos cada tipo de error usando condicionales.*/
                if(datos.errors.materia){

                    for (var i = datos.errors.materia.length - 1; i >= 0; i--) {

                        var li = document.createElement('li');
                        var liContent = document.createTextNode(datos.errors.materia[i]);
                        li.appendChild(liContent);
                        document.getElementById("errorUl").appendChild(li);
                        
                    }
                }

                if (datos.errors.materia_codigo) {
                
                    for (var j = datos.errors.materia_codigo.length - 1; j >= 0; j--) {
                        
                        var li = document.createElement('li');
                        var liContent = document.createTextNode(datos.errors.materia_codigo[j]);
                        li.appendChild(liContent);
                        document.getElementById("errorUl").appendChild(li);
                    }
                }

                if (datos.errors.materia_preguntas) {
                
                    for (var k = datos.errors.materia_preguntas.length - 1; k >= 0; k--) {
                        
                        var li = document.createElement('li');
                        var liContent = document.createTextNode(datos.errors.materia_preguntas[k]);
                        li.appendChild(liContent);
                        document.getElementById("errorUl").appendChild(li);
                    }
                }

            }

        }).fail(function(xhr, status, e) {
            console.log(e);
        });

    });

});