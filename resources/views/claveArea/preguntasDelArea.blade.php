@extends("../layouts.plantilla")
@section("head")
@section("css")
   <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
   <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.css')}}">
   <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.min.css')}}">
    <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
@endsection
@endsection


@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Areas asignadas a la clave</a></li>
    <li class="breadcrumb-item">Preguntas</li>
@endsection

@section("main")
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
              Clave-Area | Preguntas
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Pregunta</th>
                  <th>Fecha de creación</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Pregunta</th>
                  <th>Fecha de creación</th>
                </tr>
              </tfoot>
              <tbody>
                @foreach($preguntas as $pregunta)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $pregunta->pregunta}}</td>
                  <td >{{ $pregunta->created_at }}</td>
                </tr>
                @endforeach
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
@endsection

@endsection