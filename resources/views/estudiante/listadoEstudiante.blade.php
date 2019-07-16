@extends("../layouts.plantilla")
@section("head")
@endsection

@section("css")
	 <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
	 <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.css')}}">
	 <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.min.css')}}">
@endsection

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Materia</a></li>
    <li class="breadcrumb-item">Estudiante</li>
@endsection
@section("main")

<div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">
      <!-- DataTables Example -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fas fa-table"></i>
          Listado de Estudiantes | Materia</div>
        <div class="card-body">
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
                  <td><a title="Detalle estudiante" href="{{ route('detalle_estudiante',$estudiante->id_est) }}"><img src="{{asset('img/student.ico')}}" width="35" height="35" /></a></td>
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
        <div class="card-footer small text-muted">Actualizado: fecha</div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

@endsection
@endsection


@section("footer")
@endsection

@section("js")
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