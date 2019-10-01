$(document).ready(function() {
    $(function() {
        var control=$("#gpo-preg").data("control");
        var token =$("#gpo-preg").data('token');
        if(control==0){
            var data=['id','descripcion_grupo_emp'];
        }else{
            var data=['id','pregunta'];
        }
        var table = $('#areas').DataTable({
            "processing":true,
            "serverSide": true,
            "ajax": {
                "url":window.location.href,
                "type":"POST",
                "data":{
                     "_token": token
                }
            },
            "columns": [
                { data: data[0]}, 
                { data: data[1]}, 
                {
                    data: 'actions',
                    orderable: false,
                    searchable: false
                },
            ],
            "language": {
                "info": "Mostrando Pagina _PAGE_ de _PAGES_",
                "search": "Buscar:",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior",
                },
                "lengthMenu": 'Mostrar <select class="browser-default custom-select">' + '<option value="5">5</option>' + '<option value="10">10</option>' + '<option value="25">25</option>' + '<option value="50">50</option>' + '<option value="-1">TODOS</option>' + '</select> registros',
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "emptyTable": "No hay datos",
                "zeroRecords": "Lo sentimos, no hay coincidencias.",
                "infoEmpty": "",
                "infoFiltered": "",
            },
            //Centrar datos dentro de una columna target=3
            columnDefs: [{
                'className': 'text-center',
                'targets': 2,
                'width' : "20%"
            },{
                'targets':1,
                'width' : "70%"
            },{
                "searchable": false,
                "orderable": false,
                "targets": 0,
                'width' : "10%"
            }]
        });
        table.on('order.dt search.dt', function() {
            table.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        function exito(datos) {
            $("#message-success").removeAttr("hidden");
            $("#text-success").text(datos.success);
            setTimeout(function() {
                $("#message-success").attr('hidden', true);
            }, 4000);
            //Para mover al inicio de la pagina el control
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
        }
        var id_preg = "";
        var id_gpo = "";
        //Evento para eliminar
        $('body').on('click', '.btn-eliminar', function() {
            id_preg = $(this).data('id');
            id_gpo = $(this).data('gpo');
            $.get('/area/' + id_gpo + '/pregunta/' + id_preg).done(function(data) {
                $("#id_preg_eli").val(data.id);
            }).fail(function() {
                console.log("Error");
            });
        });
        //Peticion para eliminar
        $("#eliminar").click(function() {
            $(this).attr("disabled", true);
            $.ajax({
                url: '/area/' + id_gpo + '/pregunta/' + id_preg,
                type: "DELETE",
                data: $("#form-elim").serialize(),
                dataType: "json"
            }).done(function(datos) {
                $("#salir_eli").click();
                $("#eliminar").removeAttr("disabled");
                table.draw();
                //Mostrando mensaje de exito
                if (datos.type == 2) {
                    exito(datos);
                } else {
                    $("#message-error").removeAttr("hidden");
                    $("#text-error").text(datos.error);
                    setTimeout(function() {
                        $("#message-error").attr('hidden', true);
                    }, 4000);
                    //Para mover al inicio de la pagina el control
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                }
            }).fail(function(xhr, status, e) {
                console.log(e);
            });
        });
        //Evento para crear una pregunta
        var id_area=$("#id-area").data('area');
        $('body').on('click', '#add_pregunta', function() {
            $('#form-edit').trigger("reset");
            $("#modificar").val('Crear');
            $("#title-modal").html("Crear Pregunta");
            $("#pregunta_id").val(id_area);
            $("#pregunta").val("");
        });
        //Evento para crear una edicion de la pregunta
        $('body').on('click', '.btn-editar', function() {
            $('#form-edit').trigger("reset");
            id_preg = $(this).data('id');
            id_gpo = $(this).data('gpo');
            $.get('/area/' + id_gpo + '/pregunta/' + id_preg).done(function(data) {
                $("#title-modal").html("Editar Pregunta");
                $("#pregunta_id").val(data.id);
                $("#pregunta").val(data.pregunta);
                $('#modificar').val('Modificar');
            }).fail(function() {
                console.log("Error");
            });
        });
        //Peticion para modificar
        $("#modificar").click(function() {
            if ($("#pregunta").val().length > 0) {
                $(this).attr("disabled", true);
                if ($(this).val() == "Modificar") {
                    $.ajax({
                        url: '/area/' + id_gpo + '/pregunta/' + id_preg,
                        type: "PUT",
                        data: $("#form-edit").serialize(),
                        dataType: "json"
                    }).done(function(datos) {
                        $("#salir").click();
                        $("#modificar").removeAttr("disabled");
                        $("#modificar").attr('data-type', 'create');
                        table.draw();
                        //Mostrando mensaje de exito
                        exito(datos);
                    }).fail(function(xhr, status, e) {
                        console.log(e);
                    });
                } else {
                    $.ajax({
                        url: '/area/1/pregunta/create',
                        type: "POST",
                        data: $("#form-edit").serialize(),
                        dataType: "json"
                    }).done(function(datos) {
                        $("#salir").click();
                        $("#modificar").removeAttr("disabled");
                        $("#modificar").attr('data-type', 'update');
                        table.draw();
                        //Mostrando mensaje de exito
                        exito(datos);
                    }).fail(function(xhr, status, e) {
                        console.log(e);
                    });
                }
            } else {
                $("#validacion").removeAttr("hidden");
                $("#modificar").removeAttr("disabled");
            }
        });
    });
});