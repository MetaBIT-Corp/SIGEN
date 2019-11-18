$(document).ready(porcentajeAprobadosReprobados('pie'));
$('[name="chart"]').on('change', changeTypeChart)
$('#rango').on('change', function(){ 
    rangosNotas($(this).val() )
});
$(document).ready(function(){ rangosNotas(1); });

function changeTypeChart(){
    var seleccion = $('[name="chart"]:checked').val();

    if(seleccion==0){
        porcentajeAprobadosReprobados('bar');
    }
    else{
        porcentajeAprobadosReprobados('pie');
    }
}

function rangosNotas(intervalo){
    var body = $('#canvasRangoNotas');
    body.html('<canvas id="rangosNotas" width="400" height="400"></canvas>');

    var ctx2 = document.getElementById('rangosNotas').getContext('2d');
    var evaluacion_id = $('#evaluacion').data('evaluacion-id');

    $.get('/evaluacion/'+evaluacion_id+'/estadisticos/intervalo/'+intervalo, function(data){
        var dataValues = data.cantidad;
        var dataLabels = data.etiquetas;
        var max = data.max;
        var max_x = data.max_x;
        var max_y = data.max_y;

        var myChart = new Chart(ctx2, {
          type: 'bar',
          data: {
            labels: dataLabels,
            datasets: [{
              label: 'Cantidad',
              data: dataValues,
              backgroundColor: '#16D1FF',
            }]
          },
          options: {
            tooltips: {
                enabled: false,
                footerFontColor: '#000000',
                bodyFontColor: '#000000'

            },
            scales: {
              xAxes: [{
                display: false,
                barPercentage: 1.3,
                ticks: {
                    max: max_x,
                }
             }, {
                display: true,
                ticks: {
                    autoSkip: false,
                    max: max,
                }
              }],
              yAxes: [{
                ticks: {
                  min: 0,
                  max: max_y,
                  stepZise: 1,
                  beginAtZero:true
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Cantidad de estudiantes',
                    fontColor: 'black',
                    fontSize: 15
                }
              }]
            },
            legend: {
                display: false
            }
          }
        });
    })
};

function porcentajeAprobadosReprobados(tipo){
    var body = $('#canvasAprobadosReprobados');
    var showScale = true;
    var showAxis = true;
    var showTooltip = false;
    body.html('<canvas id="aprovadosReprobados" width="400" height="400"></canvas>');

    var evaluacion_id = $('#evaluacion').data('evaluacion-id');
    var ctx = document.getElementById('aprovadosReprobados').getContext('2d');

    if(tipo == 'pie'){
        showScale = false;
        showAxis = false;
        showTooltip = true;
    }

    $.get('/evaluacion/'+evaluacion_id+'/estadisticos/porcentajes', function(data){
         var porcentaje_aprobados = data.porcentaje_aprobados;
         var porcentaje_reprobados = data.porcentaje_reprobados;
         var porcentaje_evaluados = data.porcentaje_evaluados;
         var porcentaje_no_evaluados = data.porcentaje_no_evaluados;

         var myChart = new Chart(ctx, {
            type: tipo,
            data: {
                labels: ['Aprobados', 'Reprobados', 'Evaluados', 'No evaluados'],
                datasets: [{
                    label: 'Procentaje',
                    data: [
                        porcentaje_aprobados, 
                        porcentaje_reprobados, 
                        porcentaje_evaluados, 
                        porcentaje_no_evaluados
                    ],
                    backgroundColor: [
                        '#00D903',
                        '#EA1313',
                        '#00AEFF',
                        '#FFC100'
                    ],
                    borderColor: [
                        '#02A200',
                        '#A20016',
                        '#0080A2',
                        '#FF9700'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                tooltips: {
                    enabled: showTooltip
                },
                scales: {
                    yAxes: [{
                        display:showAxis,
                        gridLines: {
                            zeroLineColor: "black",
                            zeroLineWidth: 2
                        },
                        ticks: {
                            min: 0,
                            max: 100,
                            stepZise: 10,
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: showScale,
                            labelString: 'Porcentajes',
                            fontColor: 'black',
                            fontSize: 15
                        }
                    }]
                },
                legend: {
                    display: false,
                    position: 'top',
                    labels: {
                      boxWidth: 80,
                      fontColor: 'black'
                    }
                }
            }
        }); 
    })
};