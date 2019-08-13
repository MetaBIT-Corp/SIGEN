$(document).ready(function() {
    //Funcion que asigna el nuevo valor y el id de la pregunta a sus respectivos inputs
    //Tanto para la funcion de liminar como de editar
    function botones() {
        $(".btn-editar").click(function() {
            var id_area = $(this).attr("name");
            $("#id_area").val("" + id_area);
            var titulo = $("#" + id_area).text();
            titulo = titulo.split(".");
            var valor = $("#input_titulo").val($.trim(titulo[1]));
        });
        $(".btn-eliminar").click(function() {
            var id_area = $(this).attr("name");
            $("#id_area_eli").val("" + id_area);
            var titulo = $("#" + id_area).text();
            titulo = titulo.split(".");
            var valor = $("#input_titulo").val($.trim(titulo[1]));
        });
        setTimeout(function() {
            $(".alert").alert('close');
        }, 3000);
    }
    //Llamada al metodo construido anteriormente
    botones();
    //Peticion asincrona donde se envian los datos
    $("#modificar").click(function() {
        if ($("#input_titulo").val().length > 0) {
            $.ajax({
                url: "/api/area/edit",
                type: "POST",
                data: $("#form-edit").serialize(),
                dataType: "html"
            }).done(function(datos) {
                $("#salir").click();
                $("#accordion").html(datos);
                botones();
            }).fail(function(xhr, status, e) {
                console.log(e);
            });
        } else {
            $("#validacion").removeAttr("hidden");
        }
    });
    //Peticion para eliminar
    $("#eliminar").click(function() {
        $.ajax({
            url: "/api/area/delete",
            type: "POST",
            data: $("#form-elim").serialize(),
            dataType: "html"
        }).done(function(datos) {
            $("#salir_eli").click();
            $("#accordion").html(datos);
            botones();
        }).fail(function(xhr, status, e) {
            console.log(e);
        });
    });
});