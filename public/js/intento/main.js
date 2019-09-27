function capturar_data(accion){
	//accion = 0 es previo
	//accion = 1 es siguiente
    var data = $("#quiz_form").serialize();
    //Si no hay datos, procedemos a paginar, según acción del Estudiante
    if(! data)
        paginacion(accion);

    var respuestas_val = data.replace(/&/g,"-");

    $.get('/persistencia', {respuestas:respuestas_val}, function(response){
        console.log("success");
        //Luego de almacenar las respuestas procedemos a paginar
        paginacion(accion);
    });
}

function paginacion(accion){
    var location_url = window.location.href;
    var res_arr = location_url.split("=");
    
    var page = 0;

    if(accion == 0)
        page = parseInt(res_arr[1], 10) - 1;
    else
        page = parseInt(res_arr[1], 10) + 1;

    window.location.href = res_arr[0] + "=" + page.toString();
}
