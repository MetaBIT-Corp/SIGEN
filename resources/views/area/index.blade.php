@extends("../layouts.plantilla")
@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>
<script type="text/javascript">
    $(document).ready(function(){
         $.ajax({
        })
        .done()
        .fail();

    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
       
        $(".btn-editar").click(function(){
            var id_area=$(this).attr("name");
            $("#id_area").val(""+id_area);
            var titulo=$("#"+id_area).text();
            titulo=titulo.split(".");
            var valor=$("#input_titulo").val($.trim(titulo[1]));
        });

        $("#form-edit").click(function(){
            $.ajax({
                url:"{{ route('area_update') }}",
                type:"POST",
                data:$("#form-edit").serialize(),
                dataType:"json"
            })
            .done(function(datos){
                console.log(datos);
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
        {{ $materia->nombre_mar }}
    </a>
    \
        Areas
</div>
<div class="col-3">
    <a class="btn" href="#">
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
                    <a class="btn" id="btn_eliminar" title="Eliminar">
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
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="inputPassword">
                            Titulo de Area
                        </label>
                        <div class="col-sm-8">
                            <input id="id_area" type="number" name="id_area" hidden />
                            <input class="form-control" id="input_titulo" name="titulo" placeholder="Titulo" required="" type="text"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">
                        Salir
                    </button>
                    <input class="btn btn-primary" id="modificar" type="submit" value="Modificar"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
