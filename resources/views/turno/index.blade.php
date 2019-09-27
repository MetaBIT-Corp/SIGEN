@extends("../layouts.plantilla")

@section("css")
    <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
@endsection

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route('materias')}}">Materias</a></li>
    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->carga_academica->id_carg_aca]) }}">Evaluaciones</a></li>
    <li class="breadcrumb-item">Listado de turnos</li>
@endsection

@section("main")
    <div id="wrapper">
      <div id="content-wrapper">
        <div class="container-fluid">
          @if(session('notification-message') and session('notification-type'))
          <div class="alert alert-{{ session('notification-type') }} text-center alert-dismissible fade show" role="alert">
            <h5>{{ session('notification-message') }}</h5>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif
          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Listado de Turnos | <b>{{ $nombre_evaluacion }}</b></div>
            <div class="card-body">
            <div class="row">
                <a id="btn_add" class="btn btn-sm" href="{{ URL::signedRoute('crear_turno', ['id' => $evaluacion_id]) }}" title="Agregar">
                    <span class="icon-add"></span>
                </a>
                <b id="b_add">Agregar turno</b>
            </div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Fecha/Hora de inicio</th>
                      <th>Fecha/Hora de fin</th>
                      <th>Visible</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($turnos))
                       <input type="hidden" value="{{ $i = 0 }}">
                        @foreach($turnos as $turno)
                            <tr>
                                <th scope="row">{{ ++$i }}</th>
                                <td>{{ $turno->fecha_inicio_turno }}</td>
                                <td>{{ $turno->fecha_final_turno }}</td>
                                <td><input type="checkbox" @if($turno->visibilidad) checked @endif disabled></td>
                                <td>
                                    @if($turno->acciones)
                                      <a  class="btn btn-sm btn-secondary" title="Duplicar Turno" href="{{ URL::signedRoute('duplicar', ['id' => $evaluacion_id, 'turno_id' =>$turno->id ]) }}">
                                           <span class="icon-copy"></span>
                                       </a>
                                       <a id="btn_editar" class="btn btn-sm" title="Editar" href="{{ URL::signedRoute('editar_turno', ['id' => $evaluacion_id, 'turno_id' =>$turno->id ]) }}">
                                           <span class="icon-edit"></span>
                                       </a>
                                          @if($turno->accion_delete)
                                               <a id="btn_eliminar" class="btn btn-sm" title="Eliminar" onclick="modal('{{ $turno->id }}', '{{ $evaluacion_id }}','{{ $turno->fecha_inicio_turno }}', '{{ $turno->fecha_final_turno }}')">
                                                   <span class="icon-delete"></span>
                                               </a>
                                          @endif
                                    @else 
                                        No acciones 
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
            <div class="card-footer small text-muted"><b>*No acciones:</b> El turno ya no esta disponible para hacer cambios, esto se debe a que el turno ya fue o se está  llevando a cabo.</div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-wrapper -->
    </div>
    <div id="modal_delete" class="modal" tabindex="-1" role="dialog"> 
      <div class="modal-dialog" role="document"> 
        <div class="modal-content"> 
          <div class="modal-header"> 
            <h5 class="modal-title">Alerta!</h5> 
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
              <span aria-hidden="true">&times;</span> 
            </button> 
          </div> 
          <div class="modal-body"> 
            <p id="p_mensaje_body"></p> 
          </div> 
          <div class="modal-footer"> 
            <form id="delete_form" method="post">
                @csrf
                @method('delete')
                <button type="submit" class="btn btn-danger">Confirmar</button> 
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>    
            </form>
          </div> 
        </div> 
      </div> 
    </div> 
@endsection
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/turno/index/main.js')}}"></script>

    <!-- Page level plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/datatables/jquery.dataTables.js' )}}"></script>
    <script type="text/javascript" src="{{asset('vendor/datatables/dataTables.bootstrap4.js' )}}"></script>
    <script type="text/javascript" src="{{asset('js/demo/datatables-demo.js')}}"></script>
@endsection
