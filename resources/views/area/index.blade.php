@extends("../layouts.plantilla")
@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet">
<script type="text/javascript">
	$(document).ready(function(){
		$(".btn-editar").click(function(){
			$.ajax({
				url:"{{ route('areas.show',[3,2]) }}",
				type:"GET",
				data:{
					"valio":1
				},
				dataType:"html"
				
			})
			.done(function(datos){
				
			})
			.fail(function(){
				console.log("ALV");
			});
		});

	});
</script>
@endsection

@section("body")
@section("ol_breadcrumb")
    <a href="#">
        Materia \ X \ Areas
    </a>
    <a class="btn btn-sm" href="#" id="btn_add" title="Agregar">
        <span class="icon-add">
        </span>
    </a>
    <b id="b_add">
        Agregar turno
    </b>
    @endsection
@section("main")
@forelse($areas as $area)
    <div id="accordion">
        <!--Collapse-->
        <div class="card">
            <div class="card-header btn collapsed" id="heading{{ $area->id }}">
                <div class="row text-left text-secondary">
                    <div aria-controls="collapse{{ $area->id }}" aria-expanded="true" class="col-5 h5 btn-link" data-target="#collapse{{ $area->id }}" data-toggle="collapse">
                        {{ $loop->iteration }}. {{ $area->titulo }}
                    </div>
                    <div class="col-5 h5">
                        <strong>
                            Modalidad:
                        </strong>
                        {{ $area->tipo_item->nombre_tipo_item }}
                    </div>
                    <div class="col-2 h5">
                        <a type="submit" id="btn_editar" title="Editar" class="btn-editar btn">
                            <span class="icon-edit">
                            </span>
                        </a>
                        &nbsp;&nbsp;&nbsp;
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
    </div>
    @empty
    <div class="alert alert-info">
        <h2 class="h1 text-center">
            No hay areas.
        </h2>
    </div>
    @endforelse
@endsection
</link>