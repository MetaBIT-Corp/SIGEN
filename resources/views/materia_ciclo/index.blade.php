@extends("../layouts.plantilla")
@section("head")
@section("css")
	 <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
     <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
     <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
@endsection
@endsection
@section("ol_breadcrumb")
<li class="breadcrumb-item"><a href="/ciclo">Listado de Ciclos</a></li>
<li class="breadcrumb-item">Materias Ciclo</li>
<div class="col-7 mt-2">
   
</div>
<div class="col-2">
    <div class="btn-group" role="group" aria-label="Basic example">

        <!--Icono para descargar plantilla-->
        <a class="btn btn-secondary text-light"  title="Descargar Plantilla Excel" href="{{ URL::signedRoute('dmaterias_ciclo', ['id' => $ciclo->id_ciclo]) }}">
            <h6 class="mb-0"><span class="icon-download">
            </span></h6>
        </a>

       <!--Icono para importar materias ciclo -->
        <a class="btn btn-secondary" href="" title="Importar Materias Ciclo" id="importExcel">
            <h6 class="mb-0"><span class="icon-importExcel">
            </span></h6>
        </a>

        <!--Formulario para subida de archivos de excel-->
        <form method="POST" id="form-excel" enctype="multipart/form-data">
            @csrf
           <input type="file" name="archivo" accept=".xlsx" id="fileExcel" data-ciclo='{{ $ciclo->id_ciclo }}' hidden="" />
       </form>
    </div>
</div>
@endsection
@section("main")

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

<!--DATA TABLE-->
<div class="container mt-4 mb-3">
    <div class="table-responsive mt-4">
    <table class="table table-bordered mt-4" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
               <th>Codigo Materia</th>
               <th>Nombre Materia</th>
               <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($materias as $materia)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$materia->codigo_mat}}</td>
                    <td>{{$materia->nombre_mar}}</td>
                    <td class="text-center">
                        <a class="btn btn-option btn-sm" title="Listado de Docentes" href="{{URL::signedRoute('docentes_materia_ciclo',['id_mat_ci'=>$materia->id_mat_ci])}}">
                            <span class="icon-file-text"></span>
                        </a>
                    @if($ciclo->estado == 1)
                        <a class="btn-eliminar btn ml-2 btn-sm" data-target="#modal1" data-toggle="modal" id="btn_eliminar" title="Eliminar Materia Ciclo" data-id="{{$ciclo->id_ciclo}}">
                            <span class="icon-delete">
                            </span>
                        </a>
                    @else
                    
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Codigo Materia</th>
                <th>Nombre Materia</th>
                <th class="text-center">Acciones</th>
             </tr>
        </tfoot>
    </table>
    </div>
</div>


<!-- Modal2-->
<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal1" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="h4 text-danger icon-information-solid mr-2 mt-1"></span>
                <h5 class="modal-title" id="exampleModalLabel">
                   Eliminar Ciclo
                </h5>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>
            <form method="POST" id="form-delete" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-12 col-form-label" for="inputPassword">
                            <strong>¿Esta seguro que desea eliminar el ciclo seleccionado? </strong>
                        </label>
                        <input hidden="" id="id_ciclo" name="id_ciclo" type="number"/>
                        <p class="ml-3 mr-3 mb-0 text-justify">Si este ya posee materias inscritas no se podra eliminar el ciclo.</p>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <input class="btn btn-danger" id="eliminar" type="submit" value="Eliminar"/>
                    <button class="btn btn-secondary" data-dismiss="modal" id="salir_eli" type="button">
                        Salir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('js')
  <!-- Bootstrap core JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js' )}}"></script>

    <!-- Core plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/jquery-easing/jquery.easing.min.js' )}}"></script>

    <!-- Page level plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/datatables/jquery.dataTables.js' )}}"></script>
    <script type="text/javascript" src="{{asset('vendor/datatables/dataTables.bootstrap4.js' )}}"></script>
      <script type="text/javascript" src="{{asset('js/demo/datatables-demo.js')}}"></script>
      <script>
        $('#dataTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "columnDefs": [
                { "width": "18%", "targets": 1 }
            ]
        });

        $('#btn_add').on('click',function(){
            $('#modal').modal('show');
        });
        $('.btn-eliminar').on('click',function(){
            $('#form-delete').attr('action','/ciclo/'+$(this).data('id'));
        });



      </script>
      <script>
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
        var ciclo = $(this).data('ciclo');
        var data = new FormData($("#form-excel")[0]);
        //Mostrando Spinner
        $("#spinner").removeAttr("hidden");
        $.ajax({
            url: '/upload-excel/' + ciclo,
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