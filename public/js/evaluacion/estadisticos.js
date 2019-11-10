$(document).ready(porcentajeAprobadosReprobados('pie'));
$('[name="chart"]').on('change', changeTypeChart)
$('#rango').on('change', function(){ rangosNotas($(this).val() )});
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
              label: 'Notas',
              data: dataValues,
              backgroundColor: 'rgba(255, 99, 132, 1)',
            }]
          },
          options: {
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
                    display: true
                }
              }]
            }
          }
        });
    })
};

function porcentajeAprobadosReprobados(tipo){
    var evaluacion_id = $('#evaluacion').data('evaluacion-id');
    var ctx = document.getElementById('aprovadosReprobados').getContext('2d');

    $.get('/evaluacion/'+evaluacion_id+'/estadisticos/porcentajes', function(data){
         var myChart = new Chart(ctx, {
            type: tipo,
            data: {
                labels: ['Aprobados', 'Reprobados'],
                datasets: [{
                    label: 'Procentaje',
                    data: [data.porcentaje_aprobados, data.porcentaje_reprobados],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
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
                            display: true
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