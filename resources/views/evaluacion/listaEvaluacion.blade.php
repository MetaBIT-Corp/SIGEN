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
    <li class="breadcrumb-item"><a href="{{route('materias')}}">Materia</a></li>
    <li class="breadcrumb-item">Evaluaciones</li>
@endsection

@section("main")
            <!-- Notificacion  -->
            @if (session('notification'))
                  <div class="alert alert-success">
                        {!!session('notification')!!}
                  </div>
            @endif
            <!-- Notificacion -->
            <!--Mostrará mensaje de éxito-->
            @if (session('exito'))
              <div class="alert alert-success">
                {!!session('exito')!!}
              </div>
            @endif

            <!--Mostrará mensaje de error -->
            @if (session('error'))
              <div class="alert alert-danger">
                {!!session('error')!!}
              </div>
            @endif

            <!--Mostrará mensaje de informacion -->
            @if (session('info'))
              <div class="alert alert-info">
               {!!session('info')!!}
              </div>
            @endif

            <!--Mostrará mensaje de advertencia -->
            @if (session('warning'))
              <div class="alert alert-warning">
               {!!session('warning')!!}
              </div>
            @endif

  <div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">
      <!-- DataTables Example -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fas fa-table"></i>
          Evaluaciones | Materia
          </div>
        <div class="card-body">
         
            @if(auth()->user()->role==1) 
            <a class="btn btn-sm mb-3" href="{{ URL::signedRoute('gc_evaluacion', ['id' => $id_carga]) }}" title="Agregar">
              <span class="icon-add-solid "></span>
              <b>Nueva Evaluacion</b>
            </a>
            
          @endif
          @if(auth()->user()->role==1 | auth()->user()->role==0)
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
                        
                        @if(auth()->user()->IsTeacher)
                         <a class="btn btn-option btn-sm mb-1" title="Editar Evaluación" href="
                          {{ URL::signedRoute('gu_evaluacion', ['id_eva' => $evaluacion->id]) }}">
                          <span class="icon-edit"></span>
                         </a>

                         <a class="btn btn-danger btn-sm mb-1" title="Deshabilitar Evaluación" href="#" data-deshabilitar-evaluacion="{{ $evaluacion->id }}">
                          <span class="icon-minus-circle"></span>
                         </a>
                        <!-- Opcion de publicar evaluacion-->
                         <a class="btn btn-option btn-sm mb-1" title="Publicar Evaluación" href="#" data-id-evaluacion="{{ $evaluacion->id}}">
                          <span class="icon-upload"></span>
                         </a>
                         <!-- Opcion de publicar evaluacion-->
                       @endif
                       
                       <a class="btn btn-sm btn-option mb-1" title="Listado de turnos" href="{{ URL::signedRoute('listado_turnos', ['id' => $evaluacion->id]) }}">
                           <span class="icon-calendar-plus-o"></span>
                       </a>

                       <a class="btn btn-option btn-sm mb-1" title="Estadísticas" 
                        href="{{ URL::signedRoute('estadisticas_evaluacion', ['evaluacion_id' => $evaluacion->id]) }}">
                        <span class="icon-grafico"></span>
                       </a>

                       @if(count($evaluacion->turnos) > 0)
                       <a class="btn btn-option btn-sm mb-1" title="Estudiantes en evaluación" 
                        href="{{ URL::signedRoute('estudiantes_en_evaluacion', ['evaluacion_id' => $evaluacion->id]) }}">
                        <span class="icon-users"></span>
                       </a>
                       @endif
                  </td>
                  
                </tr>
                @endforeach
                
              </tbody>
            </table>
          </div>
          @endif
           
		<!--Estudiante-->
   @if(auth()->user()->role==2)
  		<div class="list-group">
        @forelse($evaluaciones as $evaluacion)
          @if($evaluacion->turnos)
            <h4 class="ml-2 mb-3" style="color: gray">{{$evaluacion->nombre_evaluacion}}</h4>
            <div class="row">
              @foreach($evaluacion->turnos as $turno) <!-- recorrecomos los turnos por evaluacion -->
                @if($turno->visibilidad == 1 && $turno->fecha_final_turno > $fecha_hora_actual)
                  <div class="col-md-6">
              		  <span class="list-group-item  flex-column align-items-start mb-3">
              		    <div class="d-flex w-100 justify-content-between">
              		      <h5 class="mb-1">
                          {{$evaluacion->nombre_evaluacion}} | Turno {{$loop->iteration}}
                        </h5>
              		      <small class="text-muted">Intentos diponibles: {{$turno->CantIntentos}}</small>
              		    </div>
              		    <!--<p class="mb-1">{{$evaluacion->descripcion_evaluacion}}</p>-->
              		    <small class="text-muted">Duración: {{$evaluacion->duracion}} minutos.</small>
                      <br>
                      <small class="text-muted">Intentos: {{$evaluacion->intentos}}.</small>
                      <br>
                      <small>Disponible desde: {{$turno->fecha_inicio_turno}} hasta: {{$turno->fecha_final_turno}} </small>
                      <br><br>
                      @if($turno->CantIntentos > 0)
                      
                        <div class="alert alert-primary" style="background: #f5efee">
                            <button type="button" class="btn btn-info mt-1 mr-3" data-acceder-evaluacion="{{ $turno->id }}" data-descripcion-evaluacion="{{ $evaluacion->descripcion_evaluacion }}" >Acceder</button>
                        </div>
                      @else
                      <div class="alert alert-danger" style="background: #f5efee">
                            <button type="button" class="btn btn-info mt-1 mr-3" data-acceder-evaluacion="{{ $turno->id }}" data-descripcion-evaluacion="{{ $evaluacion->descripcion_evaluacion }}" disabled="true">Acceder</button>
                            <a class="alert-link" style="color: #da4727;font-size: 13px">Ha realizado todos sus intentos!</a>
                      </div>
                        
                      @endif
              		  </span>
                  </div>
                @endif
              @endforeach
              </div>
          @else
            <div class="alert alert-info">
              No se encuentran evaluaciones disponibles
          </div>
          @endif
          <hr class="" style="color: #B0AFAF; background-color: #E1DEDE; width:100%;">
        @empty
          <div class="alert alert-info">
              No se encuentran evaluaciones disponibles
          </div>
        @endforelse
  		</div>
    @endif
  
		<!--Estudiante-->

        </div>
        <div class="card-footer small text-muted">
          @if(auth()->user()->IsTeacher)
          <a class="btn btn-sm float-right btn-default" href="{{ URL::signedRoute('reciclaje_evaluacion', ['id' =>  $id_carga]) }}" title="">
                  <span class="icon-recycler h5 mr-1"></span>
                  <b>Restablecer Evaluación</b>
            </a>
          @endif
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Modal para desabilitar las evaluaciones -->
<div class="modal fade" id="deshabilitarEvaluacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deshabilitarModalCenterTitle">Deshabilitar Evaluación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="deshabilitar-encuesta">
          <h5><strong>¿Desea deshabilitar esta Evaluación?</strong></h5>
        </div>
        <div class="modal-footer">
          <form action="{{ route('deshabilitar_evaluacion')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="" id="id_evaluacion" name="id_evaluacion">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Deshabilitar</button>
          </form>
        </div>
    </div>
  </div>
</div>

<!-- Modal publicar turnos -->
<div class="modal fade" id="publicarEvaluacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Seleccione los turnos que desea publicar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('publicar_evaluacion') }}" method="POST">
        {{ csrf_field() }}
        <strong class="ml-3">Turnos publicados</strong>
        <div class="modal-body" id="desplegar-turnos-publicos">
          
        </div>
        <strong class="ml-3">Turnos sin publicar</strong>
        <div class="modal-body" id="desplegar-turnos-nopublicos">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" onclick="this.form.submit();this.disabled=true;" class="btn btn-primary">Publicar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para acceder a evaluacion -->
<div class="modal fade" id="accederEvaluacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="accederModalCenterTitle">Acceso a Evaluación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" >
        
         <form action="{{ route('acceso_evaluacion') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" value="" id="id_turno_acceso" name="id_turno_acceso">
        <div class="row">
          
          <div class="col-md-12">
            <div class="alert alert-info" id="descripcion_acceso">
           Indicaciones 
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1 ml-3">Ingrese la contraseña:</label>
              <input type="password" name="contraseña" class="form-control"  id="contraseñas" placeholder="Contraseña" >
            </div>
          </div>
        </div>
      </div>   
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Acceder</button>
        </div>
      </form>
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
      $('[data-deshabilitar-evaluacion]').on('click', function(){
          $('#id_evaluacion').attr('value', $(this).data('deshabilitar-evaluacion'));
          $('#deshabilitarEvaluacion').modal('show');
      });
    </script>
    <script>
      $('[data-acceder-evaluacion]').on('click', function(){
          var indicaciones = "<strong>Indicaciones: </strong>";
          $('#id_turno_acceso').attr('value', $(this).data('acceder-evaluacion'));
          $('#descripcion_acceso').html( indicaciones.concat($(this).data('descripcion-evaluacion')));
          
          $('#accederEvaluacion').modal('show');
      });
    </script>
    <script src="/js/turno/desplegarturno.js"> </script>
@endsection


@endsection



