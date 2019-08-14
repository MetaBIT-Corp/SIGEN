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
        $(this).attr("disabled",true);
        if ($("#input_titulo").val().length > 0) {
            $.ajax({
                url: "/api/area/edit",
                type: "POST",
                data: $("#form-edit").serialize(),
                dataType: "html"
            }).done(function(datos) {
                $("#salir").click();
                $("#accordion").html(datos);
                $("#modificar").removeAttr("disabled");
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
        $(this).attr("disabled",true);
        $.ajax({
            url: "/api/area/delete",
            type: "POST",
            data: $("#form-elim").serialize(),
            dataType: "html"
        }).done(function(datos) {
            $("#salir_eli").click();
            $("#accordion").html(datos);
            $("#eliminar").removeAttr("disabled");
            botones();
        }).fail(function(xhr, status, e) {
            console.log(e);
        });
    });
    //Buscador
    var type_method="";
    $("#find").keyup(function() {
        if ($("#find").val().length>0) {
            type_method="POST";
        }else{
            type_method="GET";
        }

        $.ajax({
                url: '/api/respuesta/0',
                type: type_method,
                data: $("#form-find").serialize(),
                dataType: "html",
            }).done(function(datos) {
                $("#accordion").html(datos);
                botones();
            }).fail(function(xhr, status, e) {
                console.log(e);
            });
    });
});