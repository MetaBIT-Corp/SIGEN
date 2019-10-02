$(document).ready(function(){

	if($('#encuesta').val()!=1){

		var fecha_inicio_intento = new Date($('#fecha-inicio-intento').attr('value'))
		var fecha_final_intento = new Date()
		var duracion = parseInt($('#duracion-intento').attr('value'))

		fecha_final_intento.setTime(fecha_inicio_intento.getTime() + (duracion * 60 * 1000))

		var fecha = fecha_final_intento.getFullYear()+'-'+('0'+(fecha_final_intento.getMonth()+1)).slice(-2)+'-'+fecha_final_intento.getDate()
		var hora = fecha_final_intento.getHours() + ":" + fecha_final_intento.getMinutes() + ":" + fecha_final_intento.getSeconds()
		var fecha_final_intento_formateada = fecha+' '+hora
		$('#fecha-final-intento').attr('value',fecha_final_intento_formateada)

		// Declarando la Fecha final del conteo.
		var fecha_fin_conteo = fecha_final_intento.getTime();	//Obteniendo el valor entero. Tiempo desde 01/01/1970

		// Actualizando la cuenta cada segundo.
		var x = setInterval(function() {

			// Obteniedo fecha y hora actuales
			var ahora = new Date().getTime()

			// Encontrando la distancia de tiempo entre el momento actual y la fecha final.
			var distancia = fecha_fin_conteo - ahora

			// Cálculo de tiempo para hora, minutos y segundos
			var horas = Math.floor((distancia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
			var minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60))
			var segundos = Math.floor((distancia % (1000 * 60)) / 1000)

			// Desplegando el resultado en el contador del HTML
			document.getElementById("contador").innerHTML =horas + ":"+ minutos + ":" + segundos

			// If the count down is finished, write some text
			if (distancia < 0) {
				clearInterval(x)
				document.getElementById("contador").innerHTML = "Finalizado"
				// $(location).attr('href', '/calificar/')

				capturar_data(2)
			}
		}, 1000)
	}


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

});