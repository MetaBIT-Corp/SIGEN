@extends("../layouts.plantilla")
@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>

<!--Css para Datatable-->
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
@endsection

@section("body")
@section("ol_breadcrumb")
    <div class="col-9 mt-2">
        <a class="mr-2" href="{{ route('materias') }}">
            Materias
        </a>
        /
        <a class="ml-2 mr-2" href="#">
            {{ $materia->nombre_mar }}
        </a>
        /
        Áreas
    </div>
    <div class="col-3">
        <a class="btn" href="{{ URL::signedRoute('crear_area', ['id_materia' => $materia->id_cat_mat]) }}">
            <span class="icon-add text-primary">
            </span>
        </a>
        <strong id="b_add">
            Agregar Area
        </strong>
    </div>
    @endsection
@section("main")
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
    <div class="container mt-3 mb-3">
        <table class="table table-striped table-bordered" id="areas" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">
                        #
                    </th>
                    <th>
                        Nombre Area
                    </th>
                    <th>
                        Modalidad
                    </th>
                    <th class="text-center">
                        Acciones
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Editar Area
                    </h5>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">
                            ×
                        </span>
                    </button>
                </div>
                <form id="form-edit" method="PUT">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-danger" hidden="" id="validacion" role="alert">
                            Campo requerido para continuar.
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="inputPassword">
                                Titulo de Area
                            </label>
                            <div class="col-sm-8">
                                <input hidden="" id="id_area" name="id_area" type="number"/>
                                <input class="form-control" id="input_titulo" name="titulo" placeholder="Titulo" required="" type="text"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" id="salir" type="button" onclick="$('#validacion').attr('hidden',true);">
                            Salir
                        </button>
                        <input class="btn btn-primary" id="modificar" type="button" value="Modificar"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal2-->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal1" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="h4 text-danger icon-information-solid mr-2 mt-1"></span>
                    <h5 class="modal-title" id="exampleModalLabel">
                       Eliminar Area
                    </h5>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">
                            ×
                        </span>
                    </button>
                </div>
                <form id="form-elim" method="DELETE">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-12 col-form-label" for="inputPassword">
                                <strong>¿Esta seguro que desea eliminar el area seleccionada?</strong>
                            </label>
                            <input hidden="" id="id_area_eli" name="id_area" type="number"/>
                            <p class="ml-3 mr-3 mb-0 text-justify">Sí elimina el area se eliminaran todas las preguntas y sus opciones correspondientes. </p>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" id="salir_eli" type="button">
                            Salir
                        </button>
                        <input class="btn btn-danger" id="eliminar" type="button" value="Eliminar"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<!--Scripts para datatables con Laravel-->
@section("js")
<script type="text/javascript" src="{{ asset('js/area/area.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js">
</script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js">
</script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js">
</script>
@endsection