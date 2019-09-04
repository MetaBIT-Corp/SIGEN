$(document).ready(function() {
    $(function() {
        var table = $('#areas').DataTable({
            "serverSide": true,
            "ajax": window.location.href,
            "columns": [{
                data: 'id'
            }, {
                data: 'titulo'
            }, {
                data: 'tipo_item'
            }, {
                data: 'actions',
                orderable: false,
                searchable: false
            }, ],
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
                'targets': 3
            }, ]
        });

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
        $('body').on('click', '.btn-editar', function() {
            var id_area = $(this).data('name');
            $.get(window.location.href + '/' + id_area + '/edit').done(function(data) {
                $("#id_area").val(data.id);
                $("#input_titulo").val(data.titulo);
            }).fail(function() {
                console.log("Error");
            });
        });
        $('body').on('click', '.btn-eliminar', function() {
            var id_area = $(this).data('name');
            $.get(window.location.href + '/' + id_area + '/edit').done(function(data) {
                $("#id_area_eli").val(data.id);
            }).fail(function() {
                console.log("Error");
            });
        });

        //Peticion para modificar
        $("#modificar").click(function() {
            if ($("#input_titulo").val().length > 0) {
                $(this).attr("disabled", true);
                $.ajax({
                    url: window.location.href + '/0',
                    type: "PUT",
                    data: $("#form-edit").serialize(),
                    dataType: "json"
                }).done(function(datos) {
                    $("#salir").click();
                    $("#modificar").removeAttr("disabled");
                    table.draw();
                    //Mostrando mensaje de exito
                    exito(datos);
                }).fail(function(xhr, status, e) {
                    console.log(e);
                });
            } else {
                $("#validacion").removeAttr("hidden");
                $("#modificar").removeAttr("disabled");
            }
        });
        
        //Peticion para eliminar
        $("#eliminar").click(function() {
            $(this).attr("disabled", true);
            $.ajax({
                url: window.location.href + '/0',
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
    });
});