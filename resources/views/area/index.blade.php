@extends("../layouts.plantilla")
@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>
<script src="{{ asset('js/area/listar_area.js') }}" type="text/javascript">
</script>
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
<form class="form-group" id="form-find">
    @csrf
    <div class="form-group mb-4 mt-2 col-6 mx-auto">
        <input hidden="" name="id_mat" type="number" value="{{ $materia->id_cat_mat }}"/>
        <input class="form-control" id="find" name="find" placeholder="Buscar Area" type="text"/>
    </div>
</form>
<div class="mb-3" id="accordion">
    @include('area.response')
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
            <form id="form-edit" method="POST">
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
                    <button class="btn btn-secondary" data-dismiss="modal" id="salir" type="button">
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
                <h5 class="modal-title" id="exampleModalLabel">
                    Eliminar Area
                </h5>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>
            <form id="form-elim" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-12 col-form-label" for="inputPassword">
                            ¿Esta seguro que desea eliminar el area seleccionada?
                        </label>
                        <input hidden="" id="id_area_eli" name="id_area" type="number"/>
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
