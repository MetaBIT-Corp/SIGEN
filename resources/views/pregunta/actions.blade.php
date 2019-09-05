<div>
    @if(Request::get('id_gpo')==1)
    <a class="btn btn-success mr-2 ml-2" title="Listado preguntas" href="{{ URL::signedRoute('list-preguntas',[$id]) }}">
        <span class="icon-list">
        </span>
    </a>
    <a class="btn-editar btn" id="btn_editar"title="Editar Grupo Emparejamiento" href="">
        <span class="icon-edit">
        </span>
    </a>
    <a class="btn-eliminar btn ml-2" id="btn_eliminar" data-name="" title="Eliminar Area" href="">
        <span class="icon-delete">
        </span>
    </a>

    @else

    <a class="btn btn-warning mr-2 ml-2" title="Agregar Opciones" href="{{ URL::signedRoute('index-opcion',[$id]) }}">
        <span class="icon-add-solid">
        </span>
    </a>
    <a class="btn btn-success mr-2 ml-2" title="Informacion Opciones" href="">
        <span class="icon-information-solid">
        </span>
    </a>
    <a class="btn-editar btn" id="btn_editar" title="Editar Pregunta" href="{{ URL::signedRoute('pregunta.edit',[$grupo_emparejamiento_id,$id]) }}">
        <span class="icon-edit">
        </span>
    </a>
    <a class="btn-eliminar btn ml-2" id="btn_eliminar" data-id="{{ $id }}" data-gpo="{{ $grupo_emparejamiento_id }}" data-target="#modal1" data-toggle="modal" title="Eliminar Area" href="">
        <span class="icon-delete">
        </span>
    </a>
    @endif
    
</div>