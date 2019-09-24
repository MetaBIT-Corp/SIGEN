@extends("../layouts.plantilla")
@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{route('materias')}}">Materia</a></li>
    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('listado_estudiante', ['id' => $id_mat_ci]) }}">Listado de estudiantes</a></li>
    <li class="breadcrumb-item">Detalle de estudiante</li>
@endsection

@section("main")
    @if($mat_ci_valido)
        @if($estudiante and $consulta_valida)
            <div id="wrapper" style="width:800px; margin: 0 auto;">
              <div id="content-wrapper">
                <div class="container-fluid">
                  <!-- DataTables Example -->
                  <div class="card mb-3">
                    <div class="card-header">
                      <i class="fas fa-table"></i>
                      Datos de estudiante  |  Listado de Estudiantes </div>
                    <div class="card-body">
                      <div class="table-responsive">
                          <table class="table table-bordered" style="width:700px; margin: 0 auto;">
                            <tr>
                                <th scope="row" style="width:250px;">Carnet</th>
                                <td>{{$estudiante->carnet}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Nombre</th>
                                <td>{{$estudiante->nombre}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Es activo</th>
                                @if($estudiante->activo)
                                    <td>Si</td>
                                @else
                                    <td>No</td>
                                @endif
                            </tr>
                            <tr>
                                <th scope="row">Año de ingreso</th>
                                <td>{{$estudiante->anio_ingreso}}</td>
                            </tr>
                        </table>
                      </div>
                    </div>
                    <div class="card-footer small text-muted">Actualizado: {{$estudiante->updated_at}}</div>
                  </div>
                </div>
                <!-- /.container-fluid -->
              </div>
              <!-- /.content-wrapper -->
            </div>

             <div id="wrapper" style="width:800px; margin: 0 auto; padding-bottom:10px;">
              <div id="content-wrapper">
                <div class="container-fluid">
                  <!-- DataTables Example -->
                  <div class="card mb-3">
                    <div class="card-header">
                      <i class="fas fa-table"></i>
                      Materias que cursa  | Ciclo {{ $ciclo->num_ciclo }} - {{ $ciclo->anio }}</div>
                    <div class="card-body">
                      <div class="table-responsive">
                           @if($materias_cursando)

                              <table style="width:800px; margin: 0 auto; margin-left:20px;">
                                <tbody>
                                <tr class="row mb-3">
                                @forelse($materias_cursando as $materia)
                                @if($loop->iteration==1||$loop->iteration==2)
                                <td class="col-5">
                                <div class="card border-dark bg-light">
                                    <div class="card-body">
                                      <h5 class="card-title"><strong>{{ $materia->codigo_mat }}</strong></h5>
                                      <p class="card-text">{{ $materia->nombre_mar }}<br>
                                      </p>
                                    </div>
                                    <div class="card-footer">
                                         @if($materia->es_electiva)
                                              Es una Materia obligatoria.
                                         @else
                                              Es una materia electiva.
                                         @endif
                                    </div>
                                </div>
                                </td>
                                <!-- Validacion para que solo dos card view por filas se muestren-->
                                @elseif(($loop->iteration+1)%2!=0)
                                <td class="col-5">
                                <div class="card border-dark bg-light">
                                    <div class="card-body">
                                      <h5 class="card-title"><strong>{{ $materia->codigo_mat }}</strong></h5>
                                      <p class="card-text">{{ $materia->nombre_mar }}<br>
                                      </p>
                                    </div>
                                    <div class="card-footer">
                                         @if($materia->es_electiva)
                                              Es una Materia obligatoria.
                                         @else
                                              Es una materia electiva.
                                         @endif
                                    </div>
                                </div>
                                </td>
                                @else
                                </tr>
                                <tr class="row mb-3">
                                <td class="col-5">
                                <div class="card border-dark bg-light">
                                    <div class="card-body">
                                      <h5 class="card-title"><strong>{{ $materia->codigo_mat }}</strong></h5>
                                      <p class="card-text">{{ $materia->nombre_mar }}<br>
                                      </p>
                                    </div>
                                    <div class="card-footer">
                                         @if($materia->es_electiva)
                                              Es una Materia obligatoria.
                                         @else
                                              Es una materia electiva.
                                         @endif
                                    </div>
                                </div>
                                </td>
                                @endif
                                @empty
                                <tr>
                                    <td><h2 class="h1">No hay materias en este ciclo.</h2></td>
                                </tr>
                                @endforelse
                                </tbody> 
                                </table>

                        @else
                            <div class="alert alert-info alert-dismissible fade show" role="alert" style="width:800px; margin: 0 auto;"> 

                                <strong>Información!</strong> Actualmente no esta cursando ninguna materia. 

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 

                                    <span aria-hidden="true">&times;</span> 

                                </button> 

                            </div> 
                        @endif
                      </div>
                    </div>
                    <div class="card-footer small text-muted"></div>
                  </div>
                </div>
                <!-- /.container-fluid -->
              </div>
              <!-- /.content-wrapper -->
            </div>

        @else
            <div class="alert alert-warning alert-dismissible fade show" role="alert"> 

                <strong>Advertencia!</strong> No se encontró ningún estudiante que curse la materia {{$materia_consulta_codido}} con este identificador. 

                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 

                    <span aria-hidden="true">&times;</span> 

                </button> 

            </div> 
        @endif
    
    @else
    
        <div class="alert alert-warning alert-dismissible fade show" role="alert"> 

            <strong>Advertencia!</strong> No se encontró ninguna materia_ciclo con este identificador. 

            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 

                <span aria-hidden="true">&times;</span> 

            </button> 

        </div> 
    
    @endif
    
@endsection
@endsection