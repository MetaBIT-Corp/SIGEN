@if($error)
<div class="mess alert alert-danger text-center" role="alert" >
    <strong>
        {{ $error }}
    </strong>
</div>
@elseif($success)
<div class="mess alert alert-success text-center" role="alert" id="alerta">
    <strong>
        La accion se ejecuto exitosamente.
    </strong>
</div>
@endif
<div class="card">
    <div class="card-header bg-white">
        <div class="row">
        <div class="col-1"><strong>#</strong></div>
        <div class="col-4"><strong>Nombre Area</strong></div>
        <div class="col-5"><strong>Modalidad</strong></div>
        <div class="col-2"><strong>Acciones</strong></div>
        </div>
    </div>
</div>
@forelse($areas as $area)
<!--Collapse-->
<div class="card">
    <div class="card-header btn" id="heading{{ $area->id }}">
        <div class="row text-left text-secondary">
            <div aria-controls="collapse{{ $area->id }}" aria-expanded="false" class="mt-2 col-5 h5 btn-link collapsed" data-target="#collapse{{ $area->id }}" data-toggle="collapse">
                <div id="{{ $area->id }}">
                    {{ $loop->iteration }}.  {{ $area->titulo }}
                </div>
            </div>
            <div class="mt-2 col-5 h5">
                {{ $area->tipo_item->nombre_tipo_item }}
            </div>
            <div class="col-2 h5">
                <a class="btn-editar btn" data-target="#modal" data-toggle="modal" id="btn_editar" name="{{ $area->id }}" title="Editar Area">
                    <span class="icon-edit">
                    </span>
                </a>
                <a class="btn-eliminar btn ml-2" data-target="#modal1" data-toggle="modal" id="btn_eliminar" name="{{ $area->id }}" title="Eliminar Area">
                    <span class="icon-delete">
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div aria-labelledby="heading{{ $area->id }}" class="collapse" data-parent="#accordion" id="collapse{{ $area->id }}">
        <!--AQUI IRAN LAS PREGUNTAS DENTRO DEL CARD-BODY-->
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
