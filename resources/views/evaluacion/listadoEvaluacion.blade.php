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
    <li class="breadcrumb-item">Evaluación</li>
@endsection
@section("main")
  <div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">
      <!-- DataTables Example -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fas fa-table"></i>
          Evaluaciones | Materia</div>
        <div class="card-body">
          @if(auth()->user()->role==1) 
          <a class="btn btn-sm mb-3" href="#" title="Agregar">
                <span class="icon-add-solid "></span>
                <b>Nueva Evaluación</b>
          </a>
          @endif
          @if(auth()->user()->role==1 | auth()->user()->role==0)
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Título</th>
                  <th>Descripción</th>
                  <th>Estado</th>
                  <th>Duración</th>
                  <th>Intentos</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Título</th>
                  <th>Descripción</th>
                  <th>Estado</th>
                  <th>Duración</th>
                  <th>Intentos</th>
                  <th>Acciones</th>
                </tr>
              </tfoot>
              <tbody>
                @if($evaluaciones)
                 @foreach($evaluaciones as $evaluacion)
                <tr>
                 
                  <td>{{$evaluacion->nombre_evaluacion}}</td>
                  <td>{{$evaluacion->descripcion_evaluacion}}</td>
                  <td>
                    <span class="badge badge-success">Pública</span>
                    <span class="icon-eye"></span>
                  </td>
                  <td>{{$evaluacion->duracion}}</td>
                  <td>{{$evaluacion->intentos}}</td>
                  <td>
                      <a class="btn btn-sm btn-option" title="Listado de turnos" href="{{ URL::signedRoute('listado_turnos', ['id' => $evaluacion->id]) }}">
                           <span class="icon-calendar-plus-o"></span>
                       </a>
                       <a class="btn btn-sm btn-secondary" title="Detalle de evaluación" href="{{ URL::signedRoute('detalle_evaluacion', ['id' => $evaluacion->id]) }}">
                           <span class="icon-information-solid"></span>

                       <a class="btn btn-option btn-sm" title="Publicar Evaluación" href="#">
                        <span class="icon-upload"></span>
                       </a>

                       <a class="btn btn-option btn-sm" title="Editar Evaluación" href="#">
                        <span class="icon-edit"></span>
                       </a>

                       <a class="btn btn-danger btn-sm" title="Deshabilitar Evaluación" href="#">
                        <span class="icon-minus-circle"></span>
                       </a>

                       <a class="btn btn-info btn-sm" title="Estadísticas" href="#">
                        <span class="icon-grafico"></span>
                       </a>
                  </td>
                  
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>
          @endif
           
		<!--Estudiante-->
   @if(auth()->user()->role==2)
    @if($evaluaciones)
  		<div class="list-group">
        @forelse($evaluaciones as $evaluacion)
          @if($evaluacion->turnos)
            <div class="row">
              @foreach($evaluacion->turnos as $turno) <!-- recorrecomos los turnos por evaluacion -->
                @if($turno->visibilidad == 1)
                  <div class="col-md-6">
              		  <span class="list-group-item list-group-item-action flex-column align-items-start mb-3">
              		    <div class="d-flex w-100 justify-content-between">
              		      <h5 class="mb-1">
                          {{$evaluacion->nombre_evaluacion}} | Turno {{$loop->iteration}}
                        </h5>
              		      <small class="text-muted">Intentos diponibles: {{$evaluacion->intentos}}</small>
              		    </div>
              		    <p class="mb-1">{{$evaluacion->descripcion_evaluacion}}</p>
              		    <small class="text-muted">Duración: {{$evaluacion->duracion}}.</small>
                      <br>
                      <small class="text-muted">Intentos: {{$evaluacion->intentos}}.</small>
                      <br>
                      <button type="button" class="btn btn-info mt-1">Acceder</button>
              		  </span>
                  </div>
                @endif
             
              @endforeach
              </div>
          @else
            <h5 class="mb-1">No se encuentran evaluaciones disponibles</h5>
          @endif
        @empty
          <h5 class="mb-1">No se encuentran evaluaciones disponibles</h5>
        @endforelse
  		</div>
    @endif
  @endif

		<!--Estudiante-->

        </div>
        <div class="card-footer small text-muted"></div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->


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
@endsection


@endsection


@section("footer")
@endsection
