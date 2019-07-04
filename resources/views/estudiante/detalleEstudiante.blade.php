@extends("../layouts.plantilla")
@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Materia</a></li>
    <li class="breadcrumb-item"><a href="listado_estudiante">Listado de estudiantes</a></li>
    <li class="breadcrumb-item">Detalle de estudiante</li>
@endsection

@section("main")
    
    @if($estudiante)
        <h2 style="padding-bottom:30px;">Detalle de Estudiante</h2>
         
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
                <div class="card-footer small text-muted">Actualizado: 04-07-2019 08:50:14</div>
              </div>
            </div>
            <!-- /.container-fluid -->
          </div>
          <!-- /.content-wrapper -->
        </div>
        
    @else
        <div class="alert alert-warning alert-dismissible fade show" role="alert"> 

            <strong>Advertencia!</strong> No se encontró ningún estudiante con este identificador. 

            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 

                <span aria-hidden="true">&times;</span> 

            </button> 

        </div> 
    @endif
    
@endsection
@endsection