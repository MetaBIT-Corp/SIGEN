function capturar_data(accion){
	//accion = 0 es previo
	//accion = 1 es siguiente
    var data = $("#quiz_form").serialize();
    var respuestas_val = data.replace(/&/g,"-");
    $.get('http://localhost:8000/persistencia', {respuestas:respuestas_val}, function(response){
        console.log("success");
        var location_url = window.location.href;
        var res_arr = location_url.split("=");
        
        var page = 0;

        if(accion == 0)
        	page = parseInt(res_arr[1], 10) - 1;
        else
        	page = parseInt(res_arr[1], 10) + 1;

        window.location.href = res_arr[0] + "=" + page.toString();
    });
}
