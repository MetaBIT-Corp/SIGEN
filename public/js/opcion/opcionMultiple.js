$(document).ready(function() {

    $('#editModal').on('show.bs.modal', function(event){

        var link = $(event.relatedTarget)

        var opcion = link.data('opcion')
        var tipo_opcion = link.data('tipo')
        var correcta = link.data('correcta')
        var id = link.data('id')

        var modal = $(this)

        console.log(correcta)

        if (correcta==1) {
            modal.find(".modal-body #correctaSiEdit").prop('checked',true);
        }else{
            modal.find(".modal-body #correctaNoEdit").prop('checked',true);
        }

        modal.find('.modal-body #opcion').val(opcion)
        modal.find('.modal-body #tipo_opcion').val(tipo_opcion)
        modal.find('.modal-body #id').val(id)
    });

    $('#deleteModal').on('show.bs.modal', function(event){

        var link = $(event.relatedTarget)
        var id = link.data('id')
        var modal = $(this)
        console.log(id)
        modal.find('.modal-footer #id').val(id)
    });

    $(document).ready(function() {

        var input = document.getElementById("contador");
        var contador = parseInt(input.value);

        var btnDel = document.getElementsByClassName("btnDel");

        if (contador<=3) {

            for (i = 0; i < btnDel.length; i++) {
                btnDel[i].style.color = "grey";
                btnDel[i].style.pointerEvents = "none";
            }

        } else{

            document.getElementById("infoP").style.display="none";

            for (i = 0; i < btnDel.length; i++) {
                btnDel[i].style.color = "rgb(200,10,50)";
            }

        }
        
    });

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
    

});