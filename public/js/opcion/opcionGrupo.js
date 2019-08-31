$('#btnAgregarFila').on('click',function(event){

        var inputI = document.getElementById("indice");
        var contador = document.getElementById("contador");
        var indice = inputI.value;

        var tabla = document.getElementById("tabla1");
        var fila = tabla.insertRow(-1);
        var celda1 = document.createElement('th');

        fila.appendChild(celda1);
        var celda2 = fila.insertCell(1);
        var celda3 = fila.insertCell(2);
        var celda4 = fila.insertCell(3);

        celda1.innerHTML = fila.rowIndex-2;
        celda1.setAttribute('scope','row');

        var input = document.createElement("input");
        input.type = "text";
        input.name = "opcion" + indice;
        input.id = "opcion" + indice;
        input.setAttribute('class','form-control');
        celda2.appendChild(input);

        var radio = document.createElement("input");
        radio.type = "radio";
        radio.name = "correcta" + indice;
        radio.id = "correcta" + indice;
        radio.setAttribute('class','custom-control-label');
        celda3.appendChild(radio);

        indice++;

        inputI.setAttribute('value',indice);
        contador.setAttribute('value',fila.rowIndex-2);

    });