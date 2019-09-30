function capturar_data(accion){
	//accion = 0 es previo
	//accion = 1 es siguiente
    //accion = 2 es terminar

    //Obtenemos el id del intento para que en la persistencia se facilite la validación y saber a que intento pertenece la petición
    var intento_id = $("#intento_id").val();
    
    var data = $("#quiz_form").serialize();
    //Si no hay datos, procedemos a paginar, según acción del Estudiante
    if(! data)
        paginacion(accion);

    var respuestas_val = data.replace(/&/g,"-");

    $.get('/persistencia', {respuestas:respuestas_val,intento_id:intento_id}, function(response){
        console.log("success");
        //Luego de almacenar las respuestas procedemos a paginar
        paginacion(accion);
    });
}

function paginacion(accion){
    var location_url = window.location.href;
    var res_arr = location_url.split("=");
    
    var page = 0;

    if(accion == 2)
        window.location.href = $("#finish_btn").attr('href');
    else if(accion == 0)
        window.location.href = res_arr[0] + "=" + (parseInt(res_arr[1], 10) - 1).toString();
    else
        window.location.href = res_arr[0] + "=" + (parseInt(res_arr[1], 10) + 1).toString();
}
