@extends("../layouts.plantilla")

@section("css")
  <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
  <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
  <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.min.css')}}">
@endsection

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Evaluación</a></li>
    <li class="breadcrumb-item">Claves</li>
@endsection

@section("main")

@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul>
  </div>
@endif

@if (session('exito'))
  <div class="alert alert-success">
    <ul>
      <h4 class="text-center">{{session('exito')}}</h4>
    </ul>
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger">
    <ul>
      <h4 class="text-center">{{session('error')}}</h4>
    </ul>
  </div>
@endif

<!-- /#wrapper -->
<div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">
      <!-- DataTables Example -->
      <div class="card mb-3">
        <div class="card-header">
          <div class="row">
            <div class="col-11">
              <i class="fas fa-table"></i>
              Listado de Docentes | Materia
            </div>
            <div class="col-1">
              <a href="#" class="icon-add btn" title="Agregar Área"></a>
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
                  <td id="id_cantidad" class="text-center">{{ $clave_area->numero_preguntas }}</td>
                  <td id="id_peso">{{ $clave_area->peso }}</td>
                  <td>
                    <a class="icon-delete btn btn-danger" href="#" title="Eliminar Área" data-eliminar-ca="{{ $clave_area->id }}"></a>
                      <a class="icon-edit btn btn-primary" href="#" title="Editar Área" data-editar-ca="{{ $clave_area->id }}" data-aleatorio="{{ $clave_area->aleatorio }}"></a>
                    @if(!$clave_area->aleatorio)
                      <a class="icon-information-solid btn  btn-secondary" href="#" title="Ver preguntas agregadas" data-preguntas="{{ $clave_area->id }}"></a>
                      <a class="icon-add-solid btn btn-info" href="#" title="Agregar preguntas" data-id-clave-area="{{ $clave_area->id }}"></a>
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
      <form action="" method="POST">
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
@endsection

@section('js')
  <script src="/js/clave/cargarPreguntas.js"> </script>
  <script src="/js/clave/operacionesClaveArea.js"> </script>

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