$(document).ready(function () {
    $('#dataTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "columnDefs": [
            { "width": "18%", "targets": 1 }
        ]
    });
    
    $('.btn-eliminar').on('click', function () {
        $('#form-delete').attr('action', '/materia_ciclo/' + $(this).data('id'));
    });

    $("#registrar").on("click", function () {
        $.ajax({
            url: '/store/mc',
            type: "POST",
            data: $("#form_register").serialize(),
            dataType: "json"

        }).done(function (data) {
            $("#cerrar").click();
            $("#message-success").removeAttr("hidden");
            $("#text-success").text("El registro se realizó correctamente.");
            //Para mover al inicio de la pagina el control
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
            setTimeout(function () {
                $("#message-success").attr('hidden', true);
                location.reload();
            }, 2000);
        })
            .fail(function (data) {

                var mensaje = "";
                $.each(data.responseJSON, function (key, value) {
                    mensaje = mensaje + "<li>" + value + "</li>"
                });
                $('#error_register').removeAttr('hidden');
                $('#error_register').html(mensaje);
                console.log(mensaje);
            });
    });

    $("#cerrar").on("click", function () {
        $('#error_register').attr('hidden', 'hidden');
        $('#codigo_mat').val("");
        $("#carnet_dcn").val("");
    });

    function exito(datos) {
        $("#message-success").removeAttr("hidden");
        $("#text-success").text(datos.success);
        //Para mover al inicio de la pagina el control
        $('html, body').animate({
            scrollTop: 0
        }, 'slow');
        setTimeout(function () {
            $("#message-success").attr('hidden', true);
            location.reload();
        }, 2000);
    }

    $('#importExcel').click(function (e) {
        //Evita que se recarge la pagina, porque sino no guarda el archivo en la variable input type file.
        e.preventDefault();
        $('#fileExcel').click();
    });
    $('#fileExcel').on("change", function () {
        var ciclo = $(this).data('ciclo');
        var data = new FormData($("#form-excel")[0]);
        //Mostrando Spinner
        $("#spinner").removeAttr("hidden");
        $.ajax({
            url: '/upload-excel/' + ciclo,
            type: "POST",
            data: data,
            contentType: false, //Importante para enviar el archivo
            processData: false, //Importante para enviar el archivo
            dataType: "json"
        }).done(function (datos) {
            $('#fileExcel').val("");
            console.log(datos.type);
            if (datos.type == 2) {
                exito(datos);
            } else {
                $("#message-error").removeAttr("hidden");
                $("#text-error").text(datos.error);
                //Para mover al inicio de la pagina el control
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                setTimeout(function () {
                    $("#message-error").attr('hidden', true);
                }, 2000);
            }
            //Para ocultar spinner
            $("#spinner").attr("hidden", true);

        }).fail(function (xhr, status, e) {
            //Para ocultar spinner
            $("#spinner").attr("hidden", true);
            console.log(e);
        });
    });
});