<div>
    @if(Request::get('id_gpo')==1)
    <a class="btn btn-success btn btn-sm mr-2 ml-2" title="Listado preguntas" href="{{ URL::signedRoute('list-preguntas',[$id,0]) }}">
        <span class="icon-list">
        </span>
    </a>
    <a class="btn-editar btn btn-sm" style="cursor: pointer;" id="btn_editar" title="Editar Grupo Emparejamiento" data-descripcion="{{$descripcion_grupo_emp}}" data-id-grupo="{{$id}}" data-toggle="modal" data-target="#editmodal">
        <span class="icon-edit">
        </span>
    </a>
    <a class="btn-eliminar btn btn-sm ml-2" style="cursor: pointer;" id="btn_eliminar" title="Eliminar Grupo Emparejamiento" data-id-grupo-delete="{{$id}}" data-toggle="modal" data-target="#deletemodal">
        <span class="icon-delete">
        </span>
    </a>

    @else

    <a class="btn bg-primary btn-sm text-white mr-2 ml-2" title="Agregar Opciones" href="{{ URL::signedRoute('index-opcion',[$id]) }}">
        <span class="icon-options">
        </span>
    </a>
    <a class="btn-editar btn btn-sm" id="btn_editar" title="Editar Pregunta" href="javascript:void(0)" data-id="{{ $id }}" data-gpo="{{ $grupo_emparejamiento_id }}" data-target="#modal" data-toggle="modal">
        <span class="icon-edit">
        </span>
    </a>
    <a class="btn-eliminar btn btn-sm ml-2" id="btn_eliminar" data-id="{{ $id }}" data-gpo="{{ $grupo_emparejamiento_id }}" data-target="#modal1" data-toggle="modal" title="Eliminar Area" href="">
        <span class="icon-delete">
        </span>
    </a>
    @endif
    
</div>