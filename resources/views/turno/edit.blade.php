@extends("turno.base")

@section("nombre_vista") Editar turno @endsection
   
@section("titulo_card") Editar turno @endsection

@section("formulario")
<form action="/evaluacion/{{ $turno->evaluacion_id }}/turnos/{{ $turno->id }}" method="post">
  @csrf
  @method('PATCH')
  <div class="form-group">
    <label for="exampleInputEmail1">Fecha + Hora de inicio:</label>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input id="datetimepicker1input" type="text" name="fecha_inicio_turno" class="form-control datetimepicker-input" data-target="#datetimepicker1" placeholder="dd/mm/yyyy hh:mm tt" value="{{ old('fecha_inicio_turno',$turno->fecha_inicio_turno) }}" data-evaluacion_id="{{ $turno->evaluacion_id }}" @if($turno->iniciado) readonly @endif/>
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Fecha + Hora de fin:</label>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        <input id="datetimepicker2input" type="text" name="fecha_final_turno" class="form-control datetimepicker-input" data-target="#datetimepicker2" placeholder="dd/mm/yyyy hh:mm tt" value="{{ old('fecha_final_turno',$turno->fecha_final_turno) }}"/>
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Contraseña:</label>
    <input type="password" name="contraseña" class="form-control margen_form" style="margin-left:15px" id="exampleInputPassword1" placeholder="Contraseña" value="{{ old('contraseña',"") }}">
    <small class="form-text text-muted" style="margin-left:15px">La contraseña será la misma sino se digita una nueva.</small>
  </div>
  <div class="form-check">
    <input type="checkbox" name="visibilidad" class="form-check-input" @if($turno->visibilidad) checked @endif>
    <label class="form-check-label" for="exampleCheck1">Visible</label>
    <small class="form-text text-muted">Al marcarlo el turno será visible para los estudiantes.</small>
  </div><br>
  <div class="row">
     <div class="form-group">
         <button type="submit" class="btn btn-primary">Guardar</button>                 
     </div>
     <div class="form-group offset-1">
        <a class="btn btn-secondary" href="{{ URL::signedRoute('listado_turnos', ['id' => $turno->evaluacion_id]) }}">Cancelar</a> 
     </div>
  </div>
  <input type="hidden" name="iniciado" value="{{ $turno->iniciado }}">
</form>
@endsection

@section('clave-area')
  <!-- /#wrapper -->
<div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">
      <!-- DataTables Example -->
      <div class="card mb-3">
        <div class="card-header">
          <div class="row">
            <div class="col-8">
              <i class="fas fa-table"></i>
              Listado de Docentes | Materia
            </div>
            <div class="col-4" style="text-align: right;">
              <strong class="mb-3">Agregar Area</strong>
              <button class="btn" data-id-turno="{{$turno->id}}" data-id-clave="{{$claves[0]->id}}" data-toggle="modal" data-target="#areasModal" onclick="$('#areasModal').modal();" title="Asignar Área a Turno">
                <span class="icon-add text-primary">
                </span>
              </button>
            </div>
          </div>
        </div>
        
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Modalidad</th>
                  <th>Cantidad de preguntas</th>
                  <th>Peso</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Modalidad</th>
                  <th>Cantidad de preguntas</th>
                  <th>Peso</th>
                  <th>Opciones</th>
                </tr>
              </tfoot>
              <tbody>
                @if(count($claves[0]->clave_areas) > 0 )
                @foreach($claves[0]->clave_areas as $clave_area)
                <tr>
                  <input type="hidden" value="{{ $clave_area->id}}" id="id_clave_area_edit">
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    @if($clave_area->aleatorio)
                      <i class="icon-dice" title="Aleatorio">&nbsp;&nbsp;</i> 
                    @else
                      <i class="icon-list" title="Manual">&nbsp;&nbsp;</i> 
                    @endif
                    {{ $clave_area->area->tipo_item->nombre_tipo_item }}
                  </td>
                <!--El atributo cantidad_preguntas es un campo calculado en el modelo Clave_Area apartado de accessors-->
                  <td id="id_cantidad" class="text-center">{{ $clave_area->cantidad_preguntas }}</td>
                  <td id="id_peso">{{ $clave_area->peso }}</td>
                  <td>
                    <button class="icon-delete btn btn-danger" href="#" title="Eliminar Área" data-eliminar-ca="{{ $clave_area->id }}"></button>
                      <button class="icon-edit btn btn-primary" href="#" title="Editar Área" data-editar-ca="{{ $clave_area->id }}" data-aleatorio="{{ $clave_area->aleatorio }}"></button>
                    @if(!$clave_area->aleatorio)
                      <button class="icon-information-solid btn  btn-secondary" href="#" title="Ver preguntas agregadas" data-preguntas="{{ $clave_area->id }}"></button>
                      <button class="icon-add-solid btn btn-info" title="Agregar preguntas" data-id-clave-area="{{ $clave_area->id }}"></button>
                    @endif
                  </td>
                </tr>
                 @endforeach
                 @else
                  <tr>
                    <td colspan="5">No se encuentran resultados disponibles</td>
                </tr>
                 @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->
</div>

@include('turno.areasclave')

<!-- Modal agregar preguntas-->
<div class="modal fade" id="asignarPreguntasClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Asiganar preguntas a la clave</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('agregar_clave_area') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="clave_area" value="" id="id_clave_area_add">
        <div class="modal-body" id="asignar-preguntas">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <!-- Modal listar preguntas-->
<div class="modal fade" id="listarPreguntasClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="listarModalCenterTitle">Preguntas asignadas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="listar-preguntas">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Aceptar</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal editar Asignación de área a clave-->
<div class="modal fade" id="editarClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarModalCenterTitle">Editar asignación de área a clave</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('editar_clave_area')}}" method="POST">
        {{ csrf_field() }}
        <div class="modal-body" id="editar-preguntas">
          <input type="hidden" value="" id="id_ca" name="id_clave_area">
          <div class="form-group">
            <label for="cantidad_preguntas_id" id="msj_cant_preg">Cantidad de preguntas</label>
            <input type="number"  min="1" class="form-control" id="cantidad_preguntas_id" name="numero_preguntas">
          </div>
          <div class="form-group">
            <label for="peso_ca_id">Peso del área</label>
            <input type="number" min="0" max="100" class="form-control" id="peso_ca_id" name="peso">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal elimanr Asignación de área a clave-->
<div class="modal fade" id="eliminarClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarModalCenterTitle">Eliminar área</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="elimanr-preguntas">
          <h3><strong>¿Desea eliminar esta área de la clave?</strong></h3>
        </div>
        <div class="modal-footer">
          <form action="{{ route('eliminar_clave_area')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="" id="id_ca_eliminar" name="id_clave_area">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
    </div>
  </div>
</div>
@endsection
     
@section("extra_js")
  <script src="/js/clave/cargarPreguntas.js"> </script>
  <script src="/js/clave/operacionesClaveArea.js"> </script>
  <script type="text/javascript" src="{{ asset('js/turno/areaclave.js') }}"></script>
   
   @if($turno->iniciado) 
       <script type="text/javascript" src="{{ asset('js/turno/edit/main.js') }}"></script>
   @else 
       <script type="text/javascript" src="{{ asset('js/turno/main.js') }}"></script>
   @endif
    
@endsection    