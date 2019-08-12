@extends("../layouts.plantilla")
@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>
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
<div class="col-9 mt-2">
    <a href="{{ route('materias') }}">
        Materia
    </a>
    \
    <a href="#">
        {{ $materia->nombre_mar }}
    </a>
    \
        Areas
</div>
<div class="col-3">
    <a href="/materia/{{ $materia->id_cat_mat }}/areas/create" class="btn">
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
                <div aria-controls="collapse{{ $area->id }}" aria-expanded="false" class="col-5 h5 btn-link collapsed" data-target="#collapse{{ $area->id }}" data-toggle="collapse">
                    {{ $loop->iteration }}. {{ $area->titulo }}
                </div>
                <div class="col-5 h5">
                    <strong>
                        Modalidad:
                    </strong>
                    {{ $area->tipo_item->nombre_tipo_item }}
                </div>
                <div class="col-2 h5">
                    <a class="btn-editar btn" id="btn_editar" title="Editar" type="submit">
                        <span class="icon-edit">
                        </span>
                    </a>
                    &nbsp;&nbsp;
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
@endsection
