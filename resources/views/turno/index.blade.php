@extends("../layouts.plantilla")

@section("css")
    <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
@endsection

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="/evaluacion/{{ $evaluacion_id }}/">Evaluación</a></li>
    <li class="breadcrumb-item">Listado de turnos</li>
@endsection

@section("main")
    <div id="wrapper">
      <div id="content-wrapper">
        <div class="container-fluid">
          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Listado de Turnos | <b>{{ $nombre_evaluacion }}</b></div>
            <div class="card-body">
            <div class="row">
                <a id="btn_add" class="btn btn-sm" href="/evaluacion/{{ $evaluacion_id }}/turnos/create" title="Agregar">
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
                                       <a id="btn_editar" class="btn btn-sm" title="Editar" href="/evaluacion/{{ $evaluacion_id }}/turnos/{{ $turno->id }}/edit">
                                           <span class="icon-edit"></span>
                                       </a>
                                       <a id="btn_eliminar" class="btn btn-sm" title="Eliminar">
                                           <span class="icon-delete"></span>
                                       </a> 
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
            <div class="card-footer small text-muted"><b>*No acciones:</b> El turno ya no esta disponible para hacer cambios, esto se debe a que el turno ya fue llevado a cabo.</div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-wrapper -->
    </div>
@endsection

@endsection