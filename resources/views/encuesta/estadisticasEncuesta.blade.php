@extends("../layouts.plantilla")
@section("head")
@section("css")
	 <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
	 <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.css')}}">
	 <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.min.css')}}">
   <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
@endsection
@endsection


@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href=" {{ URL::signedRoute('listado_encuesta') }}">Listado Encuestas</a></li>
    <li class="breadcrumb-item">Estadísticas</li>
@endsection
@section("main")

<!--Mostrará mensaje de éxito en caso que la petición en clave-area se haya realizado correctamente-->
@if (session('exito'))
  <div class="alert alert-success">
    {!!session('exito')!!}
  </div>
@endif

<!--Mostrará mensaje de eror en caso que la petición en clave-area no se haya realizado correctamente-->
@if ($estadisticas == null)
  <div class="alert alert-warning">
    No se encuentran resultados en esta encuesta
  </div>
@endif

<div id="content-wrapper">

      <div class="container-fluid">

        <div class="row">
          @foreach($preguntas as $pregunta)
          @if(!$estadisticas[$pregunta->id]['respuesta_corta'])
          <div class="col-lg-4">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fas fa-chart-pie"></i>
                Pregunta {{$loop->index + 1}} </div>
              <div class="card-body">
                @if($estadisticas[$pregunta->id]['encuestados'] > 0)
                  <canvas id="{{"pregunta".$pregunta->id}}" width="100%" height="100"></canvas>
                @else
                  <div class="alert alert-info">
                    Info: No hay respuestas.
                  </div>
                @endif
              </div>
              <div class="card-footer small text-muted">SIGEN</div>
            </div>
          </div>
          <div class="col-lg-8">
             <div class="card mb-3">
              <div class="card-header">
                <i class="fas fa-chart-pie"></i>
                Descripción de pregunta </div>
              <div class="card-body">
            {{$estadisticas[$pregunta->id]['pregunta']}}
            </div>
            </div>

            <div class="card mb-3">
              <div class="card-header">
                <i class="fas fa-chart-pie"></i>
                Opciones </div>
              <div class="card-body">
              @foreach($estadisticas[$pregunta->id]['opciones'] as $opcion)
                   {{$opcion['opcion']}} <br>
              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{$opcion['porcentaje']}}%;" aria-valuenow="{{$opcion['porcentaje']}}" aria-valuemin="0" aria-valuemax="100">{{$opcion['porcentaje']}}%</div>
              </div>
             @endforeach
              <br>
             <span >Cantidad de respuestas: {{$estadisticas[$pregunta->id]['encuestados']}}</span>
            </div>
            </div>
      

          </div>
          
          @else
          <!-- Preguntas de respuesta Corta-->
            <div class="col-lg-12">
             <div class="card mb-3">
              <div class="card-header">
                <i class="fas fa-chart-pie"></i>
                Pregunta {{$loop->index + 1}} </div>
              <div class="card-body">
              Pregunta: {{$estadisticas[$pregunta->id]['pregunta']}}
              <br>
              respuestas:
               @foreach($estadisticas[$pregunta->id]['respuestas'] as $respuesta)
              <span class="badge badge-primary mb-3 mt-3" style="font-size: 14px">
                {{ str_replace("%20" ," ", $respuesta->texto_respuesta) }}
              </span>
               @endforeach
            <br>
             <span >Cantidad de respuestas: {{$estadisticas[$pregunta->id]['encuestados']}} </span>
            

            </div>
            <div class="card-footer small text-muted">SIGEN</div>
            </div>
          </div>
          <!-- Preguntas de respuesta Corta-->
          @endif
          <hr class="" style="color: #B0AFAF; background-color: #E1DEDE; width:90%;">
          @endforeach
        </div>
      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->

@section('js')

  
	<script type="text/javascript" src="{{asset('js/sb-admin.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/sb-admin.min.js')}}"></script>
  <!-- Bootstrap core JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js' )}}"></script>

    <!-- Core plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/jquery-easing/jquery.easing.min.js' )}}"></script>

     <!-- Page level plugin JavaScript-->
    <script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>

    @foreach($preguntas as $pregunta)

    <script>
      // Set new default font family and font color to mimic Bootstrap's default styling
      Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
      Chart.defaults.global.defaultFontColor = '#292b2c';

      // Pie Chart Example
      var ctx = document.getElementById("{{"pregunta".$pregunta->id}}");
      var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: [
          @foreach($estadisticas[$pregunta->id]['opciones'] as $opcion)
                @if($loop->last)
                   "{{$opcion['opcion']}}"
                @else
                   "{{$opcion['opcion']}}",
                @endif
              
             @endforeach
          ],
          datasets: [{
            data: [
            @foreach($estadisticas[$pregunta->id]['opciones'] as $opcion)
                @if($loop->last)
                   "{{$opcion['cantidad']}}"
                @else
                   "{{$opcion['cantidad']}}",
                @endif
             @endforeach
            ],
            backgroundColor: [
             '#007bff',
             '#dc3545', 
             '#ffc107',
             '#CC2EFA',
             '#40FF00',
             '#FACC2E',
             '#FF0040'
             ],

          }],
        },
      });
    </script>

  @endforeach
@endsection

@endsection