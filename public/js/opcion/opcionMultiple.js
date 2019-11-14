$(document).ready(function() {

    var input = document.getElementById("contador");
    var contador = parseInt(input.value);
    var btnDel = document.getElementsByClassName("btnDel");

    $('#alerta').hide();

    $('#enviar').attr('disabled',true);

    if(($('#id_dcn').val())!=""){

        if (contador<=2) {

            for (i = 0; i < btnDel.length; i++) {
                btnDel[i].style.color = "white";
                btnDel[i].style.pointerEvents = "none";
                btnDel[i].style.background = "grey";
            }

        }else{

            for (i = 0; i < btnDel.length; i++) {
                btnDel[i].style.color = "white";
            }

        }
    
    }else{
        if (contador<=3) {

            for (i = 0; i < btnDel.length; i++) {
                btnDel[i].style.color = "white";
                btnDel[i].style.pointerEvents = "none";
                btnDel[i].style.background = "grey";
            }

        }else{

            for (i = 0; i < btnDel.length; i++) {
                btnDel[i].style.color = "white";
            }

        }

    }

    $('#enviar').click(function(e){

        e.preventDefault();

        var form = $(this).parents('form');
        var url = form.attr('action');

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            dataType: "json"
        }).done(function(datos){

            $('#enviar').attr("disabled", true);
            location.reload(true);

            if((datos.errors)==''){

                $('#alerta').show();
                var child = document.getElementById("ul-alert").lastElementChild;

                while (child) {
                    document.getElementById("ul-alert").removeChild(child);
                    child = document.getElementById("ul-alert").lastElementChild;
                }

                var li = document.createElement('li');
                liContent = document.createTextNode(datos.errors);
                li.appendChild(liContent);
                document.getElementById("ul-alert").appendChild(li);
                
            }

        }).fail(function(xhr, status, e) {
            console.log(e);
        });
    });

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

        if (($('#esencuesta').val())==0) {
            var celda4 = fila.insertCell(3);
        }

        celda1.innerHTML = fila.rowIndex-2;
        celda1.setAttribute('scope','row');

        var input = document.createElement("input");
        input.type = "text";
        input.name = "opcion" + indice;
        input.id = "opcion" + indice;
        input.setAttribute('class','form-control');
        celda2.appendChild(input);

        if (($('#esencuesta').val())==0) {
            var radio = document.createElement("input");
            radio.type = "radio";
            radio.name = "correcta" + indice;
            radio.id = "correcta" + indice;
            radio.setAttribute('class','custom-control-label');
            celda3.appendChild(radio);
        }

        indice++;

        inputI.setAttribute('value',indice);
        contador.setAttribute('value',fila.rowIndex-2);

        if(($('#id_dcn').val())!=""){

            if (contador.value>=2) {
                $('#enviar').attr('disabled',false)
            }

        }else{

            if (contador.value>=3) {
                $('#enviar').attr('disabled',false)
            }

        }
    });



});