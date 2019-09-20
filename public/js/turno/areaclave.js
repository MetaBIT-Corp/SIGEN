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

    var peso_restante = 100-peso

    modal.find('.modal-body #turno_id').val(idTurno)
    modal.find('.modal-body #clave_id').val(idClave)
    modal.find('.modal-body #area_id').val(idArea)
    modal.find('.modal-body #preguntas_area').val(preguntas)  
    modal.find('.modal-body #titulo').val(titulo)
    modal.find('.modal-body #peso_turno').val(peso)
    modal.find('.modal-body #cantidad_preguntas').val(preguntas)
    modal.find('.modal-body #peso_restante').val(peso_restante)

    modal.find('.modal-body #titulo_s').text(String(titulo))
    modal.find('.modal-body #cantidad_preguntas_s').text(String(preguntas))
    modal.find('.modal-body #cantidad_preguntas_s2').text("*(Menor o igual a "+String(preguntas)+")")
    modal.find('.modal-body #peso_turno_s').text(String(peso))
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

});

function aleatorioSi(){
    document.getElementById("divAleatorias").style.display = ""
}

function aleatorioNo(){
    document.getElementById("divAleatorias").style.display = "none"
}