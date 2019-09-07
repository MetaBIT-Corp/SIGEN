<div>
	<a class="btn btn-success mr-2 ml-2" @if($tipo_item=='Emparejamiento')href="{{ URL::signedRoute('pregunta.index', ['id_area' => $id_area ,'id_gpo'=>true]) }}" title="Listado Grupo Emparejamiento"@else href="{{ URL::signedRoute('pregunta.index', ['id_area' => $id_area ,'id_gpo'=>false]) }}" title="Listado preguntas"@endif>
        <span class="icon-list">
        </span>
    </a>
    <a class="btn-editar btn" data-target="#modal" data-toggle="modal" id="btn_editar" data-name="{{ $id_area }}" title="Editar Area" href="javascript:void()">
        <span class="icon-edit">
        </span>
    </a>
    <a class="btn-eliminar btn ml-2" data-target="#modal1" data-toggle="modal" id="btn_eliminar" data-name="{{ $id_area }}" title="Eliminar Area" href="javascript:void()">
        <span class="icon-delete">
        </span>
    </a>
</div>