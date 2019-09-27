$(document).ready(function(){

	var alerta = $('#alerta');				/*Llamando a div de Alerta*/
	var btnAsignar = $('#btn-asignar');		/*Llamando a Botoón de Submit*/

	alerta.hide();							/*Ocultado div de Alerta*/
	
	btnAsignar.click(function(e){			/*On Click para Submit*/

		e.preventDefault();					/*Obviando comportamiento por defecto del Submit*/

		var form = $(this).parents('form');	/*Llamando al formulario padre del Submit*/
		var url = form.attr('action');		/*Llamando a la url de action especificada en el formulario*/

		$.ajax({
			type: 'POST',
			url: url,									/*La "segunda" url es la que definimos antes.*/
			data: form.serialize(),						/*Serializamos todos los campos del formulario*/
			dataType: "json"
		}).done(function(datos){						/*Especificando que pasará cuando se haga el POST*/

			if (!(datos.errors)){						/*SIN ERRORES HACEMOS LO QUE SIGUE A CONTINUACIÓN*/
				btnAsignar.attr("disabled", true);		/*Desabilitamos el botón para no hacer multiples envios*/
				location.reload(true);					/*Recargamos la página web*/
			}else{										/*Y CON ERRORES LO QUE SIGUE A CONTINUACIÓN*/
				
				alerta.show();				/*Mostramos el div de alerta*/

				/*Limpiamos la lista del Div de Alerta.*/
				var child = document.getElementById("ul-alert").lastElementChild;
				while (child) {
					document.getElementById("ul-alert").removeChild(child);
					child = document.getElementById("ul-alert").lastElementChild;
				}

				/*Gestionamos cada tipo de error usando condicionales.*/
				if(datos.errors.cantidad){

					for (var i = datos.errors.cantidad.length - 1; i >= 0; i--) {

						var li = document.createElement('li');
						liContent = document.createTextNode(datos.errors.cantidad[i]);
						li.appendChild(liContent);
						document.getElementById("ul-alert").appendChild(li);
						
					}
				}

				if (datos.errors.peso) {
				
					for (var j = datos.errors.peso.length - 1; j >= 0; j--) {
						
						var li = document.createElement('li');
						liContent = document.createTextNode(datos.errors.peso[j]);
						li.appendChild(liContent);
						document.getElementById("ul-alert").appendChild(li);
					}
				}
			}

		}).fail(function(xhr, status, e) {
			console.log(e);
		});
	});
});