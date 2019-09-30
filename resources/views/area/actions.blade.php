<div>
	<a class="btn btn-success btn-sm mr-2 ml-2" @if($tipo_item=='Emparejamiento')href="{{ URL::signedRoute('getPreguntas', ['id_area' => $id_area ,'id_gpo'=>true]) }}" title="Listado Grupo Emparejamiento"@else href="{{ URL::signedRoute('getPreguntas', ['id_area' => $id_area ,'id_gpo'=>false]) }}" title="Listado preguntas"@endif>
        <span class="icon-list">
        </span>
    </a>
    <a class="btn-editar btn btn-sm" data-target="#modal" data-toggle="modal" id="btn_editar" data-name="{{ $id_area }}" title="Editar Area" href="javascript:void()">
        <span class="icon-edit">
        </span>
    </a>
    <a class="btn-eliminar btn ml-2 btn-sm" data-target="#modal1" data-toggle="modal" id="btn_eliminar" data-name="{{ $id_area }}" title="Eliminar Area" href="javascript:void()">
        <span class="icon-delete">
        </span>
    </a>
</div>