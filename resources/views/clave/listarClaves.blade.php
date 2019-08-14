@extends("../layouts.plantilla")

@section("css")
<link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
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

@foreach($claves as $clave)
<div class="accordion col-11" id="accordionExample">
  <div class="card">
      <!-- Titulo de Collapse -->
      <div class="card-header btn text-primary text-left" type="button" id="heading{{ $clave->id }}" data-toggle="collapse" data-target="#collapse{{ $clave->id }}" aria-expanded="true" aria-controls="collapse{{ $clave->id }}">

        <div class="d-flex justify-content-between align-items-center">
          <h5>Número de clave: {{ $clave->numero_clave }}</h5>
          <a class="icon-add btn float-rigth" href="#" title="Agregar Área"></a>
        </div>

      </div>

      <div id="collapse{{ $clave->id }}" class="collapse" aria-labelledby="heading{{ $clave->id }}" data-parent="#accordionExample">
        <div class="card-body">
          @if(count($clave->clave_areas) > 0 )
          <table class="col-12">
          <tbody>
          <tr class=" row mb-3">
          @foreach($clave->clave_areas as $clave_area)
          <td class="col-6 mt-3">
          <div class="card border-dark bg-light">
              <div class="card-body">
                <input type="hidden" value="{{ $clave_area->id}}" id="id_clave_area">
                <table class="table table-sm table-borderless">
                  <tr>
                      <th scope="row">Cantidad de preguntas: </th>
                      <td id="id_cantidad">{{ $clave_area->numero_preguntas }}</td>
                  </tr>
                  <tr>
                      <th scope="row">Peso: </th>
                      <td id="id_peso">{{ $clave_area->peso }}</td>
                  </tr>
                  <tr>
                      <th scope="row">Aleatorio: </th>
                      <td>{{ $clave_area->es_aleatorio }}</td>
                  </tr>
                  <tr>
                      <th scope="row">Modalidad: </th>
                      <td>{{ $clave_area->area->tipo_item->nombre_tipo_item }}</td>
                  </tr>
                </table>
              </div>
              <div class="card-footer">
                @if(!$clave_area->aleatorio)
                  <a class="icon-add btn" href="#" title="Agregar preguntas" data-id-clave-area="{{ $clave_area->id }}"></a>
                @endif
                <a class="icon-list btn" href="#" title="Preguntas agregadas" data-preguntas="{{ $clave_area->id }}"></a>
                <a class="icon-edit btn" href="#" title="Editar Área" data-editar-ca="{{ $clave_area->id }}"></a>
                <a class="icon-delete btn" href="#" title="Eliminar Área" data-eliminar-ca="{{ $clave_area->id }}"></a>
              </div>
          </div>
          </td>
          @endforeach
          </tr>
          </tbody>
          </table>
          @else
            <h3 class="text-center"><strong>Esta clave aún no tiene áreas asignadas</strong></h3>
          @endif
        </div>
      </div>
  </div>
</div>
@endforeach

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
        <input type="hidden" name="clave_area" value="" id="id_clave_area">
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
  <div class="modal-dialog" role="document">
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
            <label for="cantidad_preguntas_id">Cantidad de preguntas</label>
            <input type="number"  min="1" class="form-control" id="cantidad_preguntas_id" name="numero_preguntas">
          </div>
          <div class="form-group">
            <label for="peso_ca_id">Peso del área</label>
            <input type="number" min="0" max="100" class="form-control" id="peso_ca_id" name="peso">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal elimanr Asignación de área a clave-->
<div class="modal fade" id="eliminarClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
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
          <form action="POST">
            {{ csrf_field() }}
            <button type="button" class="btn btn-danger" data-dismiss="modal">Eliminar</button>
            }
          </form>
        </div>
    </div>
  </div>
</div>

@endsection
@endsection

@section('js')
  <script src="/js/clave/cargarPreguntas.js"> </script>
  <script src="/js/clave/listarPreguntas.js"> </script>

  <script>
    $('[data-editar-ca]').click(function(){
      var cantidad_preguntas = $(this).parent().prev().find('#id_cantidad').text();
      var peso = $(this).parent().prev().find('#id_peso').text();
      var id_clave_area = $(this).parent().prev().find('#id_clave_area').val();

      $('#cantidad_preguntas_id').attr('value', cantidad_preguntas);
      $('#peso_ca_id').attr('value', peso);
      $('#id_ca').attr('value', id_clave_area);

      $('#editarClaveArea').modal('show');
    });
  </script>

  <script>
    $('[data-eliminar-ca]').click(function(){
      $('#eliminarClaveArea').modal('show');
    });
  </script>
@endsection