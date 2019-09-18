@extends("../layouts.plantilla")
@section("head")
@section("css")
	 <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
	 <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.css')}}">
	 <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.min.css')}}">
    <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
@endsection
@endsection


@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Materia</a></li>
    <li class="breadcrumb-item">Evaluaciones</li>
@endsection

@section("main")
            <!-- Notificacion  -->
            @if (session('notification'))
                  <div class="alert alert-success">
                        {{session('notification')}}
                  </div>
            @endif
            <!-- Notificacion -->
            <!--Mostrará mensaje de éxito-->
            @if (session('exito'))
              <div class="alert alert-success">
                <ul>
                  <h4 class="text-center">{{session('exito')}}</h4>
                </ul>
              </div>
            @endif

            <!--Mostrará mensaje de error -->
            @if (session('error'))
              <div class="alert alert-danger">
                <ul>
                  <h4 class="text-center">{{session('error')}}</h4>
                </ul>
              </div>
            @endif
  <div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">
      <!-- DataTables Example -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fas fa-table"></i>
          Evaluaciones Deshabilitadas | Materia</div>
        <div class="card-body">
 
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Título</th>
                  <th>Duración (min)</th>
                  <th>Intentos</th>
                  <th>Paginación</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Título</th>
                  <th>Duración (min)</th>
                  <th>Intentos</th>
                  <th>Paginación</th>
                  <th>Acciones</th>
                </tr>
              </tfoot>
              <tbody>
                @if($evaluaciones)
                 @foreach($evaluaciones as $evaluacion)
                <tr>
                 
                  <td>{{$evaluacion->nombre_evaluacion}}</td>
                  <td>{{$evaluacion->duracion}}</td>
                  <td>{{$evaluacion->intentos}}</td>
                  <td>{{$evaluacion->preguntas_a_mostrar}}</td>
                  <td>
                      <a class="btn btn-sm btn-secondary mb-1" title="Detalle de evaluación" href="{{ URL::signedRoute('detalle_evaluacion', ['id' => $evaluacion->id]) }}">
                           <span class="icon-information-solid"></span>
                      </a>
                        
                       <a class="btn btn-option btn-sm mb-1" title="Habilitar Evaluación" href="#" data-habilitar-evaluacion="{{ $evaluacion->id }}">
                        <span class="icon-restore"></span>
                       </a>
                  </td>      
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">
          <a class="btn btn-sm float-right btn-default" href="{{route('listado_evaluacion', $id_carga)}}" title="">
                  <span class="icon-file-text h5 mr-1"></span>
                  <b>Ver Evaluaciones</b>
            </a>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Modal para desabilitar las encuestas -->
<div class="modal fade" id="habilitarEvaluacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="habilitarModalCenterTitle">Habilitar Evaluación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="habilitar-encuesta">
          <h5><strong>¿Desea habilitar esta evaluación?</strong></h5>
        </div>
        <div class="modal-footer">
          <form action="{{ route('habilitar_evaluacion')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="" id="id_evaluacion" name="id_evaluacion">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Habilitar</button>
          </form>
        </div>
    </div>
  </div>
</div>


@endsection

@section('js')
	<script type="text/javascript" src="{{asset('js/sb-admin.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/sb-admin.min.js')}}"></script>
  <!-- Bootstrap core JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js' )}}"></script>

    <!-- Core plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/jquery-easing/jquery.easing.min.js' )}}"></script>

    <!-- Page level plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/datatables/jquery.dataTables.js' )}}"></script>
    <script type="text/javascript" src="{{asset('vendor/datatables/dataTables.bootstrap4.js' )}}"></script>
  	<script type="text/javascript" src="{{asset('js/demo/datatables-demo.js')}}"></script>

    <script>
      $('[data-habilitar-evaluacion]').on('click', function(){
          $('#id_evaluacion').attr('value', $(this).data('habilitar-evaluacion'));
          $('#habilitarEvaluacion').modal('show');
      });
    </script>
@endsection


@endsection


@section("footer")
@endsection