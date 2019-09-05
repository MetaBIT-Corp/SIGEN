$('#areasModal').on('show.bs.modal', function(event){
    var modal = $(this)
    var link = $(event.relatedTarget)

    var idTurno = link.data('id-turno')
    var idClave = link.data('id-clave')

    modal.find('.modal-body #turno_id').val(idTurno)
    modal.find('.modal-body #clave_id').val(idClave)
});

$('#asignarModal').on('show.bs.modal', function(event){

    var modal = $(this)
    var link = $(event.relatedTarget)
    var idTurno = link.data('id-turno')
    var idClave = link.data('id-clave')
    var idArea = link.data('id-area')

    modal.find('.modal-body #turno_id').val(idTurno)
    modal.find('.modal-body #clave_id').val(idClave)
    modal.find('.modal-body #area_id').val(idArea)

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