$(document).ready(function() {
    function exito(datos) {
        $("#message-success").removeAttr("hidden");
        $("#text-success").text(datos.success);
        //Para mover al inicio de la pagina el control
        $('html, body').animate({
            scrollTop: 0
        }, 'slow');
        setTimeout(function() {
            $("#message-success").attr('hidden', true);
            location.reload();
        }, 4000);
    }

    $('#importExcel').click(function(e) {
        //Evita que se recarge la pagina, porque sino no guarda el archivo en la variable input type file.
        e.preventDefault();
        $('#fileExcel').click();
    });
    $('#fileExcel').on("change", function() {
        var modalidad = $(this).data('area');
        var data = new FormData($("#form-excel")[0]);
        $.ajax({
            url: '/upload-excel/' + modalidad,
            type: "POST",
            data: data,
            contentType: false, //Importante para enviar el archivo
            processData: false, //Importante para enviar el archivo
            dataType: "json"
        }).done(function(datos) {
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
                setTimeout(function() {
                    $("#message-error").attr('hidden', true);
                }, 4000);
            }
        }).fail(function(xhr, status, e) {
            console.log(e);
        });
    });
});