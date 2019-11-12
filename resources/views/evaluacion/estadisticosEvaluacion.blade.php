@extends("../layouts.plantilla")
@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>

<!--Css para Datatable-->
<script src="/vendor/chart.js/Chart.js"></script>

@endsection

@section("body")
@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route('materias') }}">Materia</a></li>
    <li class="breadcrumb-item"><a 
      href=" {{ URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->carga_academica->id_carg_aca]) }}">Evaluaciones</a></li>
    <li class="breadcrumb-item">Estadísdticos</li>
@endsection

@section("main")

@if ($notification == 1)
  <div class="alert alert-dark">
@else
  <div class="alert alert-warning">
@endif
    <ul>
      <h4 class="text-center">{{ $message }}</h4>
    </ul>
  </div>

@if($notification==1)
<div class="row" id="evaluacion" data-evaluacion-id={{ $evaluacion->id }}>
  <div class="col-lg-6">
    <div class="card mb-3">
      <div class="card-header">
        <div class="row">
            <div class="col-md-8">
                Procentajde de Aprobados y Reprobados
            </div>
            <div class="col-md-4">
                <div class="custom-control custom-radio custom-control-inline">
                 <input type="radio" id="bar_chart" name="chart" class="custom-control-input" value="0">
                <label class="custom-control-label" for="bar_chart">
                <i class="fas fa-chart-bar"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="pie_chart" name="chart" class="custom-control-input" value="1">
                <label class="custom-control-label" for="pie_chart">
                <i class="fas fa-chart-pie"></i></label>
                </div>
            </div>
        </div>
        </div>
      <div class="card-body">
        <canvas id="aprovadosReprobados" width="400" height="400"></canvas>
      </div>
      <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card mb-3">
      <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <i class="fas fa-chart-bar"> </i>Rangos de notas
            </div>
            <div class="col-md-6">
                <select class="form-control" id="rango">
                  <option value="">Seleccione un intervalo</option>
                  <option value="1">Intervalos de 1</option>
                  <option value="2">Intervalos de 2</option>
                  <option value="5">Intervalos de 5</option>
                </select>
            </div>
        </div>

      </div>
      <div class="card-body">
        <canvas id="rangosNotas" width="400" height="400"></canvas>
      </div>
      <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
    </div>
  </div>
</div>
@endif
@endsection
<!--Scripts para datatables con Laravel-->
@section("js")
@if($notification==1)
<script src="/js/evaluacion/estadisticos.js"></script>
@endif
@endsection