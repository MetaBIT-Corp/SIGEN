@extends("../layouts.plantilla")
@section("head")
@endsection

@section("css")
	 <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
   <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
   <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">

@endsection

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{route('materias')}}">Materia</a></li>
    <li class="breadcrumb-item">Estudiante</li>



    <div class="offset-8 btn-group" role="group" aria-label="Basic example">
        <!--Boton para agregar pregunta-->
        <a class="btn btn-secondary" href="#" title="Inscribir estudiante">
            <h6 class="mb-0">
                <span class="icon-add-solid"></span>
            </h6>
        </a>

        <!--Icono para descargar plantilla-->
        <a class="btn btn-secondary text-light"  title="Descargar plantilla para inscripciones" href="{{ URL::signedRoute('download_inscripciones', ['materia_ciclo_id' => $id_mat_ci]) }}">
            <h6 class="mb-0"><span class="icon-download">
            </span></h6>
        </a>

       <!--Icono para importar materias ciclo -->
        <a class="btn btn-secondary" href="" title="Importar Inscripciones" id="importExcel">
            <h6 class="mb-0"><span class="icon-importExcel">
            </span></h6>
        </a>

        
    </div>
    <!--Formulario para subida de archivos de excel-->
    <form method="POST" id="form-excel" enctype="multipart/form-data">
        @csrf
       <input type="file" name="archivo" accept=".xlsx" id="fileExcel" hidden="" />
       <input type="hidden" name="materia_ciclo_id" value="{{$id_mat_ci}}"/>
   </form>

@endsection
@section("main")

<div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">
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
          Listado de Estudiantes | {{$materia->nombre_mar}}</div>
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
                  <td>
                    <a title="Detalle estudiante" href="
                    {{ URL::signedRoute('detalle_estudiante', ['id' => $estudiante->id_est, 'id_mat' =>$id_mat_ci]) }}
                   " class="btn btn-sm btn-option">
                      <span class="icon-student"></span>
                    </a>
                    <button title="Desinscribir estudiante" class="btn btn-sm btn-danger">
                      <span class="icon-minus-circle"></span>
                    </button>
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
          
        </div>
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
    <script type="text/javascript">

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
                  url: '/estudiantes/upload-excel/',
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