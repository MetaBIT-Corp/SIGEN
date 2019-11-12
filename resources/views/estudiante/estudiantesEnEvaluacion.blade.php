@extends("../layouts.plantilla")
@section("head")
@endsection

@section("css")
   <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
   <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.css')}}">
   <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.min.css')}}">
   <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
   <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
@endsection

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route('materias') }}">Materia</a></li>
    <li class="breadcrumb-item">Evaluacion</li>
    <li class="breadcrumb-item">Estudiantes</li>
@endsection
@section("main")




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

<!-- /#wrapper -->
<div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">

      <!-- Información sobre la Evaluación -->
      <div class="card mb-3" style="max-width: 100%;">
        <div class="card-header"><strong>Información sobre la Evaluación</strong></div>
        <div class="card-body">
          <div class="row">
            <p class="col-md-12"><strong>Nombre:</strong> {{$evaluacion->nombre_evaluacion}}</p>
          </div>
          <div class="row">
            <p class="col-md-12"><strong>Descripcion:</strong> {{$evaluacion->descripcion_evaluacion}}</p>
          </div>
          <div class="row">
            <p class="col-md-4"><strong>Duración (minutos):</strong> {{$evaluacion->duracion}}</p>
            <p class="col-md-4"><strong>Intentos Posibles:</strong> {{$evaluacion->intentos}}</p>
            <p class="col-md-4">
              <strong>Revisión:</strong>
              @if($evaluacion->revision==1)
                Permitida
              @else
                No Permitida
              @endif
            </p>
          </div>
        </div>
      </div>

      <!-- DataTables Example -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fas fa-table"></i>
          <strong>Estudiantes de Evaluación</strong></div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Carnet</th>
                  <th>Nombre</th>
                  <th>Inicio</th>
                  <th>Finalización</th>
                  <th>Nota</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Carnet</th>
                  <th>Nombre</th>
                  <th>Inicio</th>
                  <th>Finalización</th>
                  <th>Nota</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </tfoot>
              <tbody>
                @foreach($estudiantes as $estudiante)
                <tr>
                  <td>{{ $estudiante->carnet }}</td>
                  <td>{{ $estudiante->nombre }}</td>
                  <td>{{ $estudiante->inicio }}</td> <!--campo atributo agregado en el controlador-->
                  <td>{{ $estudiante->final }}</td> <!--campo atributo agregado en el controlador-->
                  <td>{{ $estudiante->nota }}</td> <!--campo atributo agregado en el controlador-->
                  <td> <!--campo atributo agregado en el controlador-->
                    @if( $estudiante->estado == 0)
                      <span class="badge badge-warning ">No iniciado</span>
                    @elseif( $estudiante->estado == 1)
                      <span class="badge badge-info ">Iniciado</span>
                    @elseif( $estudiante->estado == 2)
                      <span class="badge badge-success ">Finalizado</span>
                    @else
                      <span class="badge badge-success "> - </span>
                    @endif
                  </td> 
                  <td class="text-center">

                    <!-- Validación para mostrar opción de habilitar y/0 deshabilitar -->
                    @if(!$estudiante->revision_estudiante)
                      @if($estudiante->id_intento != 0)
                        <a class="btn btn-sm btn-option mb-1" title="Habilitar Revisión" href="#"
                        data-habilitar-revision="{{ $estudiante->id_intento }}">
                        <span class="icon-eye"></span>
                        </a>
                        @endif
                    @else
                      <a class="btn btn-sm btn-option mb-1" title="Deshabilitar Revisión" href="#"
                      data-deshabilitar-revision="{{ $estudiante->id_intento }}">
                        <span class="icon-eye-slash"></span>
                      </a>
                    @endif

                    @if($estudiante->id_intento !=0 )
                      <a class="btn btn-sm btn-option mb-1" title="Revisión" href="{{ route('revision_evaluacion',$estudiante->id_intento)}}">
                        <span class="icon-detail_user"></span>
                      </a>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Actualizado: fecha</div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->
</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Modal para autorizar revisión -->
<div class="modal fade" id="habilitarRevision" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deshabilitarModalCenterTitle">Habilitar Revisión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="habilitar-revision">
          <h5><strong>¿Desea habilitar la revisión?</strong></h5>
        </div>
        <div class="modal-footer">
          <form action="{{ route('habilitar_revision')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="" id="id_intento" name="id_intento">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-option">Habilitar</button>
          </form>
        </div>
    </div>
  </div>
</div>
<!-- Modal para autorizar revisión -->

<!-- Modal para deshabilitar revisión -->
<div class="modal fade" id="deshabilitarRevision" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deshabilitarModalCenterTitle">Deshabilitar Revisión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="habilitar-revision">
          <h5><strong>¿Desea deshabilitar la revisión?</strong></h5>
        </div>
        <div class="modal-footer">
          <form action="{{ route('deshabilitar_revision')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="" id="id_intento_des" name="id_intento_des">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Deshabilitar</button>
          </form>
        </div>
    </div>
  </div>
</div>
<!-- Modal para deshabilitar revisión -->

@endsection
@endsection


@section("footer")
@endsection

@section("js")
<!-- Script para autorizar la revision -->
    <script>
      $('[data-habilitar-revision]').on('click', function(){
        $('#id_intento').attr('value', $(this).data('habilitar-revision'));
          $('#habilitarRevision').modal('show');
      });
    </script>
<!-- Script para autorizar la revision -->

  <!-- Script para deshabilitar la revision -->
    <script>
      $('[data-deshabilitar-revision]').on('click', function(){
        $('#id_intento_des').attr('value', $(this).data('deshabilitar-revision'));
          $('#deshabilitarRevision').modal('show');
      });
    </script>
  <!-- Script para deshabilitar la revision -->

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