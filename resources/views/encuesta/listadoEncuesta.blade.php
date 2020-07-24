@extends("../layouts.plantilla")
@section("head")
@section("css")
	 <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
	 <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.css')}}">
	 <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.min.css')}}">
   <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
@endsection
@endsection


@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Listado Encuestas</a></li>
@endsection
@section("main")

<!--Mostrará mensaje de éxito en caso que la petición en clave-area se haya realizado correctamente-->
@if (session('exito'))
  <div class="alert alert-success">
    {!!session('exito')!!}
  </div>
@endif

<!--Mostrará mensaje de eror en caso que la petición en clave-area no se haya realizado correctamente-->
@if (session('error'))
  <div class="alert alert-danger">
    {!!session('error')!!}
  </div>
@endif

  <div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">

      <!-- DataTables Example -->

      <div class="card mb-3">
        <div class="card-header">
          <i class="fas fa-table"></i>
          Encuestas </div>
        <div class="card-body">

          @if(auth()->user()->IsTeacher) 
          <a class="btn btn-md mb-3" href="{{ URL::signedRoute('gc_encuesta') }}" title="Agregar">
              <span class="icon-add-solid " style="color: #007bff"></span>
              <b>Nueva Encuesta</b>
          </a>
          @endif

          @if($encuestas)
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Título</th>
                  <th>Estado</th>
                  <th>Periodo Disponible</th>
                  @if(auth()->user()->IsAdmin)
                  <th>Autor</th>
                  @endif
                  <th>Acciones</th>
                  
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Título</th>
                  <th>Estado</th>
                  <th>Periodo Disponible</th>
                  @if(auth()->user()->IsAdmin)
                  <th>Autor</th>
                  @endif
                  @if(auth()->user()->IsTeacher)
                  <th>Acciones</th>
                  @endif
                </tr>
              </tfoot>
              <tbody>
                
                @foreach($encuestas as $encuesta)
                  <tr>
                    <td>{{$encuesta->titulo_encuesta}}</td>
                    <td>
                      @if($encuesta->visible==1)
                      <span class="badge badge-success ">Pública</span>
                      <span class="icon-eye"></span>
                      @else
                      <span class="badge badge-warning ">No visible</span>
                      <span class="icon-eye-slash"></span> 
                      @endif
                    </td>
                    <td>
                      <b>Desde:</b> {{$encuesta->fecha_inicio_encuesta}} <br> 
                      <b>Hasta:</b> {{$encuesta->fecha_final_encuesta}}
                    </td>
                    @if(auth()->user()->IsAdmin)
                    <td>
                     {{$encuesta->docente->nombre_docente}}
                    </td>
                    @endif
                    <td>
                    @if(auth()->user()->IsTeacher)
                      <a title="Editar" href="
                      {{ URL::signedRoute('gu_encuesta', ['id' => $encuesta->id]) }}" class="btn btn-sm btn-option mb-1">
                        <span class="icon-edit"></span>
                      </a>
                      <a class="btn btn-sm btn-danger mb-1" href="#" title="Eliminar Encuesta" 
                          data-eliminar-encuesta="{{ $encuesta->id }}">
                          <span class="icon-delete"></span>
                      </a>
                      @if($encuesta->visible==0)
                      <a class="btn btn-sm btn-option mb-1" href="#" title="Publicar Encuesta"
                      data-publicar-encuesta="{{ $encuesta->id }}" disabled = "true">
                          <span class="icon-upload"></span>
                      </a>
                      @endif

                      @endif
                      <a title="Estadísticas" href="{{ URL::signedRoute('estadisticas_enc', ['id' => $encuesta->id]) }}" class="btn btn-sm btn-option mb-1">
                        <span class="icon-grafico"></span>
                      </a>

                      @if($encuesta->finalizado)
                        <!-- Opciones de descarga de resultados de Encuesta -->
                         <a class="btn btn-option btn-sm mb-1" title="Descargar resultados Excel" href="{{ route('resultadosExcel', $encuesta->id) }}">
                          <span class="icon-importExcel"></span>
                         </a>

                         <a class="btn btn-option btn-sm mb-1" title="Descargar resultados PDF" href="{{ route('resultadosPdf', $encuesta->id) }}">
                          <span class="icon-pdf"></span>
                         </a>
                      @endif

                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="alert alert-warning" role="alert">
            No se encontraron resultados          
          </div>
        @endif
        </div>
        <div class="card-footer small text-muted"></div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Modal elimanr una encuesta que no haya sido respondida-->
<div class="modal fade" id="eliminarEncuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarModalCenterTitle">Eliminar Encuesta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="elimanr-encuesta">
          <h3><strong>¿Desea eliminar esta Encuesta?</strong></h3><br>
          <h4>Se borrarán todos los objetos asociados a esta encuesnta</h4>
        </div>
        <div class="modal-footer">
          <form action="{{ route('eliminar_encuesta')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="" id="id_encuesta" name="id_encuesta">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
    </div>
  </div>
</div>

<!-- Modal para publicar encuesta -->
<div class="modal fade" id="publicarEncuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="publicarModalCenterTitle">Publicación de Encuesta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="publicar-encuesta">
          <h5><strong>¿Desea Publicar esta Encuesta?</strong></h5>
        </div>
        <div class="modal-footer">
          <form action="{{ route('publicar_encuesta')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="" id="id_encuesta_publicar" name="id_encuesta_publicar">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Publicar</button>
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
      $('[data-eliminar-encuesta]').on('click', function(){
          var id_encuesta = $(this).data('eliminar-encuesta');

          $('#id_encuesta').attr('value', $(this).data('eliminar-encuesta'));
          $('#eliminarEncuesta').modal('show');
      });
    </script>

    <script>
      $('[data-publicar-encuesta]').on('click', function(){
          $('#id_encuesta_publicar').attr('value', $(this).data('publicar-encuesta'));
          $('#publicarEncuesta').modal('show');
      });
    </script>

    


@endsection
@endsection



