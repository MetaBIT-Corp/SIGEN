$(document).ready(function() {

	var alerta_div = $('#alerta-div').hide();
    var agregar_opcion = $('#agregar-opcion');

    $('.no-rc').hide();
    $('.rc').hide();

	//Evento al mostrar Modal de Edición, se llena Formulario de acuerdo a pregunta seleccionada.
    $('#edit-modal').on('show.bs.modal', function(event){

    	var modal = $(this);
        
    	var link = $(event.relatedTarget);
    	var id_pregunta = link.data('id-pregunta');                //Obtención de Id de pregunta desde botón.
    	var tipo_pregunta = link.data('tipo-pregunta');            //Obtención de tipo de pregunta desde botón.
    	var cantidad_opciones = link.data('cantidad-opciones');    //Obtención de Cnntidad de opciones de pregunta desde botón.

    	var pregunta = link.data('pregunta');                      //Obtención de texto de pregunta.

    	modal.find('.modal-body #id-pregunta').val(id_pregunta);               //Asignando valore de Id de pregunta a input correspondiente.
    	modal.find('.modal-body #tipo-pregunta').val(tipo_pregunta);           //Asignando valor de Tipo de pregunta a input correspondiente.
    	modal.find('.modal-body #cantidad-opciones').val(cantidad_opciones);   //Asignando valor de Cantidad de Opciones a input correspondiente.

    	document.getElementById('pregunta-edit').innerHTML=pregunta;           //Obteniendo el parrafo donde se muestra el texto de la pregunta.

    	var opciones_div = document.getElementById('opciones-div');            //Obteniendo el div que contendrá todas las opciones de la pregunta.

        if (tipo_pregunta != 4){                                                //Adecuando el encabezado de la 'tabla' segun sea respuesta corta o no.
            $('.no-rc').show();
        }else{
            $('.rc').show();
        }

        var cantidad_nuevas = document.getElementById('cantidad-nuevas');
        cantidad_nuevas.value=0;

    	for (var i = 1; i <= cantidad_opciones; i++) {                         //Recorriendo Opciones

    		var opcion_div = document.createElement('div');                   //Creando div que contendrá la opción de la iteración correspondiente.
    		opcion_div.setAttribute('class','row col-form-label my-1 py-0');                           //Seteando la clase a div.
    		opciones_div.appendChild(opcion_div);                             //Agregando div de opcion a div de todas las opciones.

    		label_input = document.createElement('label');				      //Creación de label que mostrará el texto de la opción.
    		label_input.setAttribute('id','opcion'+i);                        //Asiganndo id al label (No Necesario).
    		label_input.setAttribute('name','opcion'+i);                      //Asignando name al label (No Necesario).
            if (tipo_pregunta != 4){                                                //Asignando clase a label según sea respuesta corta o no.
                label_input.setAttribute('class','form-control-plaintext col-8');
                label_input.setAttribute('for','radio-opcion'+i);               //Asignado for al label, para que referencie al radio button.
            }else{
                label_input.setAttribute('class','form-control-plaintext col-11');
            }

    		label_input.innerHTML=link.data('opcion'+i);                      //Asignando el contenido del label que muestra el texto de la opción.

            id_input = document.createElement('input');                         //Creando input que contiene el id de la opción de la iteración.
            id_input.setAttribute('type','text');
    		id_input.setAttribute('id','id-opcion'+i);                        //Asignando Id al input de Id de Opción.
    		id_input.setAttribute('name','id_opcion'+i);                      //Asignando Name a Id de Opción.
    		id_input.setAttribute('class','d-none');                          //Asignando Class a Id de Opción (Este no se muestra).
    		id_input.setAttribute('value',link.data('id-opcion'+i));          //Asignando el contenido del Id de Opción.
    		id_input.setAttribute('readonly','true');                         //Solamente lectura.

    		correcta_input = document.createElement('input');                 //Creando radio button para seleccionar la opción como correcta.
    		correcta_input.setAttribute('type','radio');                      //Definiedo que será un radiobutton
            correcta_input.setAttribute('id','radio-opcion'+i);               //Id de Radio
    		correcta_input.setAttribute('name','correcta');                   //Name de Radio
    		correcta_input.setAttribute('value',i);                           //Valor de Radio
    		if((link.data('correcta-opcion'+i))==1){                          //Mostrar chequeado si es la opción correcta.
    			correcta_input.setAttribute('checked','true');
    		}
    		correcta_input.setAttribute('class','form-control col-3');        //Asignando Class a Radio

            var correlativo_label = document.createElement('label');
            correlativo_label.setAttribute('class','form-control-plaintext col-1 font-weight-bold border-right');
            if (tipo_pregunta != 4){
                correlativo_label.setAttribute('for','radio-opcion'+i);               //Asignado for al label, para que referencie al radio button.
            }
            correlativo_label.innerHTML=i;

            opcion_div.appendChild(correlativo_label);
    		opcion_div.appendChild(label_input);                              //Agregando Label a div que contiene la opción
    		opcion_div.appendChild(id_input);                                 //Agregando id de input a div que contiene la opción
            if (tipo_pregunta != 4){
                opcion_div.appendChild(correcta_input);                       //Si no es Respuesta Corta agrega el RadioButton al Div que contiene la opción
            }
        }
    });

    $('#edit-modal').on('hidden.bs.modal', function (event) {
        document.getElementById('opciones-div').innerHTML="";               //Eliminando contenido de div de opciones cada vez que modal se oculta
        document.getElementById('opciones-nuevas-div').innerHTML="";               //Eliminando contenido de div de opciones cada vez que modal se oculta
        $('.no-rc').hide();                                                 //Escondiendo el encabezado de la 'tabla' cada vez que modal se oculta
        $('.rc').hide();
    });

    agregar_opcion.click(function(e){

        var cantidad_opciones = document.getElementById('cantidad-opciones');

        var cantidad_nuevas = document.getElementById('cantidad-nuevas');
        cantidad_nuevas.value++;

        e.preventDefault();

        var opciones_nuevas_div = document.getElementById('opciones-nuevas-div');

        var nueva_div = document.createElement('div');        
        nueva_div.setAttribute('class','row col-form-label my-1 py-0');

        var nueva_input = document.createElement('input')
        nueva_input.setAttribute('type','text');
        nueva_input.setAttribute('id','nueva'+cantidad_nuevas.value);
        nueva_input.setAttribute('name','nueva'+cantidad_nuevas.value);
        nueva_input.setAttribute('class','form-control my-2 mx-3 col-10');
        nueva_input.setAttribute('placeholder','Ingrese la nueva Opción');

        var correlativo_label = document.createElement('label');
        correlativo_label.setAttribute('class','form-control-plaintext col-1 font-weight-bold border-right');
        correlativo_label.innerHTML = parseInt(cantidad_nuevas.value)+parseInt(cantidad_opciones.value);

        
        opciones_nuevas_div.appendChild(nueva_div);
        nueva_div.appendChild(correlativo_label);
        nueva_div.appendChild(nueva_input);

    });

});