$('#areasModal').on('show.bs.modal', function(event){
    var modal = $(this)
    var link = $(event.relatedTarget)

    var idTurno = link.data('id-turno')
    var idClave = link.data('id-clave')
    var peso = link.data('peso-turno')

    modal.find('.modal-body #peso_turno').val(peso)
    modal.find('.modal-body #turno_id').val(idTurno)
    modal.find('.modal-body #clave_id').val(idClave)
});

$('#asignarModal').on('show.bs.modal', function(event){

    var modal = $(this)
    var link = $(event.relatedTarget)
    var idTurno = link.data('id-turno')
    var idClave = link.data('id-clave')
    var idArea = link.data('id-area')
    var preguntas = link.data('preguntas-area')
    var titulo = link.data('titulo')
    var peso = link.data('peso-turno')
    var tipo = link.data('tipo')

    var peso_restante = 100-peso

    modal.find('.modal-body #turno_id').val(idTurno)
    modal.find('.modal-body #clave_id').val(idClave)
    modal.find('.modal-body #area_id').val(idArea)
    modal.find('.modal-body #preguntas_area').val(preguntas)  
    modal.find('.modal-body #titulo').val(titulo)
    modal.find('.modal-body #peso_turno').val(peso)
    modal.find('.modal-body #cantidad_preguntas').val(preguntas)
    modal.find('.modal-body #peso_restante').val(peso_restante)
    modal.find('.modal-body #tipo_item_d').val(tipo)

    modal.find('.modal-body #titulo_s').text(String(titulo))
    modal.find('.modal-body #cantidad_preguntas_s').text(String(preguntas))
    modal.find('.modal-body #cantidad_preguntas_s2').text("*(Menor o igual a "+String(preguntas)+")")
    modal.find('.modal-body #peso_turno_s').text(String(peso))
    
    switch (tipo) {
        case 1:
            modal.find('.modal-body #tipo_s').text("Opción Múltiple")
        break;
        case 2:
            modal.find('.modal-body #tipo_s').text("Verdadero/Falso")
        break;
        case 3:
            modal.find('.modal-body #tipo_s').text("Emparejamiento")
        break;
        case 4:
            modal.find('.modal-body #tipo_s').text("Respuesta Corta")
        break;
    }
    
    modal.find('.modal-body #peso_restante_s').text("*(Menor o igual a "+String(peso_restante)+")")


});

$('#eliminarModal').on('show.bs.modal', function(event){

    var modal = $(this)
    var link = $(event.relatedTarget)
    var idArea = link.data('id-area')
    modal.find('.modal-body #id_clave_area').val(idArea)

});

$(document).ready(function() {

    if (document.getElementById("aleatorio-no").checked) {
        document.getElementById("divAleatorias").style.display = "none";
    }else{
        document.getElementById("divAleatorias").style.display = "";
    }

    var peso_total = $('#peso_total').val();
    $('#peso_s').text(peso_total);

    var total_preguntas = $('#total_preguntas').val();
    $('#total_preguntas_s').text(total_preguntas);

    var areas_sin_preguntas = $('#areas_sin_preguntas').val()

    var visibilidad = $('#visibilidad').val()

    if(peso_total<100 || areas_sin_preguntas>0){
        $('#estado_s').text('No Publicable')
        $('#div-info-estado').attr('class','alert alert-dismissible alert-danger pb-0')
        if(peso_total<100){$('#peso_info').attr('hidden',false)}
        if(areas_sin_preguntas>0){$('#preguntas_info').attr('hidden',false)}        
    }else if(peso_total==100 && visibilidad != 1){
        $('#estado_s').text('Publicable')
        $('#div-info-estado').attr('class','alert alert-dismissible alert-success pb-0')
    }else{
        $('#estado_s').text('Publicado')
        $('#div-info-estado').attr('class','alert alert-dismissible alert-info pb-0')
    }

});

function aleatorioSi(){
    document.getElementById("divAleatorias").style.display = ""
}

function aleatorioNo(){
    document.getElementById("divAleatorias").style.display = "none"
}