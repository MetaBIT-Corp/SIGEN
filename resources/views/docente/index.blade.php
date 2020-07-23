@extends("../layouts.plantilla")

@section("css")
    <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
@endsection

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route('home')}}">Home</a></li>
    <li class="breadcrumb-item">Docentes</li>
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
                Listado de Docentes | Global</div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                        <th>Carnet</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                        <th>Carnet</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @if(count($docentes)>0)
                            @foreach($docentes as $docente)
                                <tr>
                                    <td>{{ $docente->carnet_dcn }}</td>
                                    <td>{{ $docente->nombre_docente }}</td>
                                    @if( $docente->activo==1)
                                        <td>Activo</td>
                                    @else
                                        <td>Inactivo</td>
                                    @endif
                                    <td>
                                        <button id="btn_eliminar" class="btn btn-sm" title="Eliminar"
                                            data-docente_id="{{ $docente->id_pdg_dcn }}" data-docente_carnet="{{ $docente->carnet_dcn }}"
                                            onclick="activateModalDestroy(this);">
                                            <span class="icon-delete"></span>
                                        </button>
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
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-wrapper -->
    </div>

    <div id="modal_destroy" class="modal" tabindex="-1" role="dialog"> 
      <div class="modal-dialog" role="document"> 
        <div class="modal-content"> 
          <div class="modal-header"> 
            <h5 class="modal-title">Eliminar Docente</h5> 
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
              <span aria-hidden="true">&times;</span> 
            </button> 
          </div> 
          <div class="modal-body"> 
            <p id="p_mensaje_body"></p> 
          </div> 
          <div class="modal-footer"> 
            <form action="{{ route('docentes_destroy') }}" method="post">
                @csrf
                <input type="hidden" id="docente_id" name="docente_id" value="">
                <button type="submit" class="btn btn-danger">Confirmar</button> 
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
    <script type="text/javascript">

        function activateModalDestroy(param){
            var docente_id = $(param).attr("data-docente_id");
	        var docente_carnet = $(param).attr("data-docente_carnet");
            document.getElementById("p_mensaje_body").innerHTML = "<p>¿Esta seguro que desea eliminar al Docente con carnet: <b>" + docente_carnet + "</b>?";
            $("#docente_id").val(docente_id);
	        $("#modal_destroy").modal();
        }

    </script>
@endsection
