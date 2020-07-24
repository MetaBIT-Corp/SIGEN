@extends("../layouts.plantilla")

@section("css")
    <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
@endsection

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route('home')}}">Home</a></li>
    <li class="breadcrumb-item">Estudiantes</li>
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

          @if (session('message'))
            <div class="alert alert-success">
                <h6 class="text-center">{!! session('message') !!}</h6>
            </div>
          @endif
            <!-- DataTables Example -->
            <div class="card mb-3">
              <div class="card-header">
                <i class="fas fa-table"></i>
                Listado de Estudiantes | Global
              </div>
              <div class="card-body">
                @if(auth()->user()->IsAdmin)
                    <a class="btn btn-md mb-3" href="{{ route('gc_estudiante')}}" title="Agregar">
                        <span class="icon-add-solid" style="color: #007bff"></span>
                        <b>Agregar Estudiante</b>
                    </a>
                @endif
                <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Carnet</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Año de ingreso</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Carnet</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Año de ingreso</th>
                        <th>Acciones</th>
                      </tr>
                    </tfoot>
                    <tbody>
                      @if(count($estudiantes)>0)
                      @foreach($estudiantes as $estudiante)
                      <tr>
                        <td>{{$estudiante->carnet}}</td>
                        <td>{{$estudiante->nombre}}</td>
                        @if($estudiante->activo==1)
                        <td>Activo</td>
                        @else
                        <td>Inactivo</td>
                        @endif
                        <td>{{$estudiante->anio_ingreso}}</td>
                        <td>
                          @if(auth()->user()->IsAdmin)
                          <button id="btn_eliminar" class="btn btn-sm" title="Eliminar"
                            data-estudiante_id="{{ $estudiante->id_est }}" data-estudiante_carnet="{{ $estudiante->carnet }}"
                            onclick="activateModalDestroy(this);">
                              <span class="icon-delete"></span>
                          </button>

                          <a href="{{route('gu_estudiante')}}" class="btn btn-option btn-sm" title="Editar" style="text-decoration:none; color: white;">
                            <span class="icon-edit "></span>
                          </a>
                          @endif

                          @if($estudiante->usuario->enabled == 1)
                          <button class="btn btn-sm btn-danger" title="Bloquear estudiante" value="0" 
                            data-user-id="{{ $estudiante->id_est }}" data-user-carnet="{{ $estudiante->carnet }}">
                              <i class="fas fa-user-slash"></i>
                          </button>
                          @else
                          <button class="btn btn-sm btn-success" title="Desbloquear estudiante" value="1" 
                            data-user-id="{{ $estudiante->id_est }}" data-user-carnet="{{ $estudiante->carnet }}">
                              <i class="fas fa-user-check"></i>
                          </button>
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
              <div class="card-footer small text-muted">
                Actualizado en: {{ date('d-M-Y h:i A', strtotime($last_update)) }}
              </div>
            </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-wrapper -->
    </div>
    
    <div id="modal_destroy" class="modal" tabindex="-1" role="dialog"> 
      <div class="modal-dialog" role="document"> 
        <div class="modal-content"> 
          <div class="modal-header"> 
            <h5 class="modal-title">Eliminar Estudiante</h5> 
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
              <span aria-hidden="true">&times;</span> 
            </button> 
          </div> 
          <div class="modal-body"> 
            <p id="p_mensaje_body"></p> 
          </div> 
          <div class="modal-footer"> 
            <form action="{{ route('estudiantes_destroy') }}" method="post">
                @csrf
                <input type="hidden" id="estudiante_id" name="estudiante_id" value="">
                <button type="submit" class="btn btn-danger">Confirmar</button> 
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>    
            </form>
          </div> 
        </div> 
      </div> 
    </div>

    <div id="modal_change_state" class="modal" tabindex="-1" role="dialog"> 
      <div class="modal-dialog" role="document"> 
        <div class="modal-content"> 
          <div class="modal-header"> 
            <h5 class="modal-title" id="titleId"></h5> 
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
              <span aria-hidden="true">&times;</span> 
            </button> 
          </div> 
          <div class="modal-body"> 
            <p id="bodyId"></p> 
          </div> 
          <div class="modal-footer"> 
            <form action="{{ route('estudiante_change_state') }}" method="post">
                @csrf
                <input type="hidden" id="user_id" name="est_id" value="">
                <button id="btnConfirm" type="submit" >Confirmar</button> 
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>    
            </form>
          </div> 
        </div> 
      </div> 
    </div>
@endsection

@section('js')
    <!-- Page level plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/datatables/jquery.dataTables.js' )}}"></script>
    <script type="text/javascript" src="{{asset('vendor/datatables/dataTables.bootstrap4.js' )}}"></script>
    <script type="text/javascript" src="{{asset('js/demo/datatables-demo.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/user/changeUserState.js')}}"></script>
    <script type="text/javascript">

        function activateModalDestroy(param){
          var estudiante_id = $(param).attr("data-estudiante_id");
          var estudiante_carnet = $(param).attr("data-estudiante_carnet");
          document.getElementById("p_mensaje_body").innerHTML = "<p>¿Esta seguro que desea eliminar al Estudiante con carnet: <b>" + estudiante_carnet + "</b>?";
          $("#estudiante_id").val(estudiante_id);
          $("#modal_destroy").modal();
        }

    </script>
@endsection
