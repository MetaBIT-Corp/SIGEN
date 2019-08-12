@extends("../layouts.plantilla")
@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>
<script type="text/javascript">
    $(document).ready(function(){
        
        //Funcion que asigna el nuevo valor y el id de la pregunta a sus respectivos inputs
        $(".btn-editar").click(function(){
            var id_area=$(this).attr("name");
            $("#id_area").val(""+id_area);
            var titulo=$("#"+id_area).text();
            titulo=titulo.split(".");
            var valor=$("#input_titulo").val($.trim(titulo[1]));
        });

        $(".btn-eliminar").click(function(){
            var id_area=$(this).attr("name");
            $("#id_area_eli").val(""+id_area);
            var titulo=$("#"+id_area).text();
            titulo=titulo.split(".");
            var valor=$("#input_titulo").val($.trim(titulo[1]));
        });

        //Peticion asincrona donde se envian los datos
        $("#modificar").click(function(){
            if($("#input_titulo").val().length>0){
                $.ajax({
                    url:"{{ route('area_update') }}",
                    type:"POST",
                    data:$("#form-edit").serialize(),
                    dataType:"html"
                })
                .done(function(datos){
                    $("#salir").click();
                    $("#accordion").html(datos);
                    $(".btn-editar").click(function(){
                        var id_area=$(this).attr("name");
                        $("#id_area").val(""+id_area);
                        var titulo=$("#"+id_area).text();
                        titulo=titulo.split(".");
                        var valor=$("#input_titulo").val($.trim(titulo[1]));
                    });
                })
                .fail(function(xhr,status,e){
                    console.log(e);
                });
            }else{
                $("#validacion").removeAttr("hidden");
            }
        });

            //Peticion para eliminar
        $("#eliminar").click(function(){
                $.ajax({
                    url:"{{ route('area_delete') }}",
                    type:"POST",
                    data:$("#form-elim").serialize(),
                    dataType:"html"
                })
                .done(function(datos){
                    $("#salir_eli").click();
                    $("#accordion").html(datos);
                    $(".btn-eliminar").click(function(){
                        var id_area=$(this).attr("name");
                        $("#id_area_eli").val(""+id_area);
                        var titulo=$("#"+id_area).text();
                        titulo=titulo.split(".");
                        var valor=$("#input_titulo").val($.trim(titulo[1]));
                    });
                })
                .fail(function(xhr,status,e){
                    console.log(e);
                });

        });
});
</script>
@endsection

@section("body")
@section("ol_breadcrumb")
<div class="col-9 mt-2">
    <a href="{{ route('materias') }}">
        Materias
    </a>
    \
    <a href="#">
        {{ $areas[0]->materia->nombre_mar }}
    </a>
    \
        Areas
</div>
<div class="col-3">
    <a class="btn" href="/materia/{{ $areas[0]->materia->id_cat_mat }}/areas/create">
        <span class="icon-add text-primary" href="#">
        </span>
    </a>
    <strong id="b_add">
        Agregar Area
    </strong>
</div>
@endsection
@section("main")
<div id="accordion">
    @forelse($areas as $area)
    <!--Collapse-->
    <div class="card">
        <div class="card-header btn" id="heading{{ $area->id }}">
            <div class="row text-left text-secondary">
                <div aria-controls="collapse{{ $area->id }}" aria-expanded="false" class="mt-2 col-5 h5 btn-link collapsed" data-target="#collapse{{ $area->id }}" data-toggle="collapse">
                    <div id="{{ $area->id }}">
                        {{ $loop->iteration }}. {{ $area->titulo }}
                    </div>
                </div>
                <div class="mt-2 col-5 h5">
                    <strong>
                        Modalidad:
                    </strong>
                    {{ $area->tipo_item->nombre_tipo_item }}
                </div>
                <div class="col-2 h5">
                    <a class="btn-editar btn" data-target="#modal" data-toggle="modal" id="btn_editar" name="{{ $area->id }}" title="Editar">
                        <span class="icon-edit">
                        </span>
                    </a>
                    <a class="btn-eliminar btn" data-target="#modal1" data-toggle="modal" id="btn_eliminar" name="{{ $area->id }}" title="Eliminar">
                        <span class="icon-delete">
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div aria-labelledby="heading{{ $area->id }}" class="collapse" data-parent="#accordion" id="collapse{{ $area->id }}">
            <div class="card-body">
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        <h2 class="h1 text-center">
            No hay areas.
        </h2>
    </div>
    @endforelse
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
