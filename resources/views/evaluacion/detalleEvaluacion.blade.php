@extends("../layouts.plantilla")
@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Materia</a></li>
    <li class="breadcrumb-item"><a href="listado_estudiante">Listado de estudiantes</a></li>
    <li class="breadcrumb-item">Detalle de estudiante</li>
@endsection

@section("main")

@if($evaluacion)
  <div id="wrapper" style="width:800px; margin: 0 auto;">
    <div id="content-wrapper">
      <div class="container-fluid">
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Evaluación  |  Detalle de la evaluación </div>
          <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" style="width:700px; margin: 0 auto;">
                  <tr>
                      <th scope="row" style="width:250px;">Título</th>
                      <td>{{ $evaluacion->nombre_evaluacion }}</td>
                  </tr>
                  <tr>
                      <th scope="row">Duración</th>
                      <td>{{ $evaluacion->duracion }} minutos</td>
                  </tr>
                  <tr>
                      <th scope="row">Cantidad de intentos</th>
                      <td>{{ $evaluacion->intentos }}</td>
                  </tr>
                  <tr>
                      <th scope="row">Creada por</th>
                      <td>{{ $evaluacion->carga_academica->docente->nombre_docente }}</td>
                  </tr>
                  <tr>
                      <th scope="row">Descripción</th>
                      <td>{{ $evaluacion->descripcion_evaluacion }}</td>
                  </tr>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Actualizado: {{ $evaluacion->updated_at ?: 'No ha sido modificada' }}</div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content-wrapper -->
  </div>
 @endif

@endsection
@endsection