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

	// Restricts input for the given textbox to the given inputFilter.
	function setInputFilter(textbox, inputFilter) {
	  	["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
	    	textbox.addEventListener(event, function() {
	      	if (inputFilter(this.value)) {
	        	this.oldValue = this.value;
		        this.oldSelectionStart = this.selectionStart;
	    	    this.oldSelectionEnd = this.selectionEnd;
	      	} else if (this.hasOwnProperty("oldValue")) {
	        	this.value = this.oldValue;
	        	this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
	      	}
	    	});
	  	});
	}

	setInputFilter(document.getElementById("cantidad"), function(value) {
  		return /^\d*$/.test(value);
	});

	setInputFilter(document.getElementById("peso"), function(value) {
  		return /^\d*$/.test(value);
	});

	
    // Integer values (positive only):
    // /^\d*$/.test(value)
    // Integer values (positive and up to a particular limit):
    // /^\d*$/.test(value) && (value === "" || parseInt(value) <= 500)
    // Integer values (both positive and negative):
    // /^-?\d*$/.test(value)
    // Floating point values (allowing both . and , as decimal separator):
    // /^-?\d*[.,]?\d*$/.test(value)
    // Currency values (i.e. at most two decimal places):
    // /^-?\d*[.,]?\d{0,2}$/.test(value)
    // A-Z only (i.e. basic Latin letters):
    // /^[a-z]*$/i.test(value)
    // Latin letters only (i.e. English and most European languages, see https://unicode-table.com for details about Unicode character ranges):
    // /^[a-z\u00c0-\u024f]*$/i.test(value)
    // Hexadecimal values:
    // /^[0-9a-f]*$/i.test(value)

});