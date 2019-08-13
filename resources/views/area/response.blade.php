@if($success)
<div class="alert alert-success text-center" role="alert">
    <strong>
        La accion se ejecuto exitosamente.
    </strong>
</div>
@endif
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
