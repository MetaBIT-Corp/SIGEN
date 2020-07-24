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

    <div class="offset-8 btn-group" role="group" aria-label="Basic example">
        <!--Boton para agregar Docente-->
        <a class="btn btn-secondary" href="{{ route('gc_docente')}}" title="Agregar Docente">
            <h6 class="mb-0">
                <span class="icon-add-solid"></span>
            </h6>
        </a>

        <!--Icono para descargar plantilla-->
        <a class="btn btn-secondary" href="{{ route('plantilla_docentes') }}" title="Descargar Plantilla Excel">
            <h6 class="mb-0">
                <span class="icon-download"></span>
            </h6>
        </a>

        <!--Icono para importar Docentes -->
        <a class="btn btn-secondary" href="" title="Importar Docentes" id="importExcel">
            <h6 class="mb-0">
                <span class="icon-importExcel"></span>
            </h6>
        </a>
    </div>
    <!--Formulario para subida de archivos de excel-->
    <form method="POST" id="form-excel" enctype="multipart/form-data">
            @csrf
        <input type="file" name="archivo" accept=".xlsx" id="fileExcel" hidden="" />
    </form>
   
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

        <div class="text-center" id="spinner" hidden="true">
            <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;" role="status" >
            </div><br>
            <span class="">Importando ...</span>
        </div>
        <div id="message-success" class="alert alert-success alert-dismissible fade show text-center" role="alert" hidden>
            <strong id="text-success">Exito</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div id="message-error" class="alert alert-danger alert-dismissible fade show text-center" role="alert" hidden>
            <strong id="text-error">Error</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
          
            <!-- DataTables Example -->
            <div class="card mb-3">
                <div class="card-header">
                <i class="fas fa-table"></i>
                Listado de Docentes | Global</div>
                <div class="card-body">
                    
                @if(auth()->user()->IsAdmin)
                <!--
                    <a class="btn btn-md mb-3" href="{{ route('gc_docente')}}" title="Agregar">
                        <span class="icon-add-solid" style="color: #007bff"></span>
                        <b>Agregar Docente</b>
                    </a>
                -->
                @endif
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
                                      @if(auth()->user()->IsAdmin)  
                                        <button id="btn_eliminar" class="btn btn-sm" title="Eliminar"
                                            data-docente_id="{{ $docente->id_pdg_dcn }}" data-docente_carnet="{{ $docente->carnet_dcn }}"
                                            onclick="activateModalDestroy(this);">
                                            <span class="icon-delete"></span>
                                        </button>

                                        <a href="{{ URL::signedRoute('gu_docente', ['docente_id' => $docente->id_pdg_dcn]) }}" class="btn btn-option btn-sm" title="Editar" style="text-decoration:none; color: white;">
                                          <span class="icon-edit "></span>
                                        </a>
                                      @endif
                                      @if($docente->usuario->enabled == 1)
                                        <button class="btn btn-sm btn-danger" title="Bloquear docente" value="0" 
                                          data-user-id="{{ $docente->id_pdg_dcn }}" data-user-carnet="{{ $docente->carnet_dcn }}">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-success" title="Desbloquear docente" value="1" 
                                          data-user-id="{{ $docente->id_pdg_dcn }}" data-user-carnet="{{ $docente->carnet_dcn }}">
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
            <form action="{{ route('docente_change_state') }}" method="post">
                @csrf
                <input type="hidden" id="user_id" name="docente_id" value="">
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
            var docente_id = $(param).attr("data-docente_id");
	        var docente_carnet = $(param).attr("data-docente_carnet");
            document.getElementById("p_mensaje_body").innerHTML = "<p>¿Esta seguro que desea eliminar al Docente con carnet: <b>" + docente_carnet + "</b>?";
            $("#docente_id").val(docente_id);
	        $("#modal_destroy").modal();
        }

        $(document).ready(function() {
            function exito(datos) {
                $("#message-success").removeAttr("hidden");
                $("#text-success").text(datos.success);
                //Para mover al inicio de la pagina el control
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                setTimeout(function() {
                    $("#message-success").attr('hidden', true);
                    location.reload();
                }, 2000);
            }

            $('#importExcel').click(function(e) {
                //Evita que se recarge la pagina, porque sino no guarda el archivo en la variable input type file.
                e.preventDefault();
                $('#fileExcel').click();
            });
            $('#fileExcel').on("change", function() {
                var data = new FormData($("#form-excel")[0]);
                //Mostrando Spinner
                $("#spinner").removeAttr("hidden");
                $.ajax({
                    url: '/docentes/upload-excel/',
                    type: "POST",
                    data: data,
                    contentType: false, //Importante para enviar el archivo
                    processData: false, //Importante para enviar el archivo
                    dataType: "json"
                }).done(function(datos) {
                    $('#fileExcel').val("");
                    console.log(datos.type);
                    if (datos.type == 2) {
                        exito(datos);
                    } else {
                        $("#message-error").removeAttr("hidden");
                        $("#text-error").text(datos.error);
                        //Para mover al inicio de la pagina el control
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        setTimeout(function() {
                            $("#message-error").attr('hidden', true);
                        }, 2000);
                    }
                    //Para ocultar spinner
                    $("#spinner").attr("hidden",true);

                }).fail(function(xhr, status, e) {
                    //Para ocultar spinner
                    $("#spinner").attr("hidden",true);
                    console.log(e);
                });
            });
        });

    </script>
@endsection
