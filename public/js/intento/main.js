function capturar_data(){
    var data = $("#quiz_form").serialize();
    respuestas_val = data.replace(/&/g,"-");
    $.get('http://localhost:8000/persistencia', {respuestas:respuestas_val}, function(response){
        console.log("success");
    });
}

$("#previous_btn").click(capturar_data);

$("#next_btn").click(capturar_data);