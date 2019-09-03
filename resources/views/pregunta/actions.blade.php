<div>
    @if(Request::get('id_gpo')==1)
    <a class="btn btn-success mr-2 ml-2" title="Listado preguntas" href="">
        <span class="icon-list">
        </span>
    </a>
    @else
    <a class="btn btn-warning mr-2 ml-2" title="Agregar Opciones" href="">
        <span class="icon-add-solid">
        </span>
    </a>
    <a class="btn btn-success mr-2 ml-2" title="Informacion Opciones" href="">
        <span class="icon-information-solid">
        </span>
    </a>
    @endif
    <a class="btn-editar btn" data-target="#modal" data-toggle="modal" id="btn_editar" data-name="" title="Editar Area" href="javascript:void()">
        <span class="icon-edit">
        </span>
    </a>
    <a class="btn-eliminar btn ml-2" data-target="#modal1" data-toggle="modal" id="btn_eliminar" data-name="" title="Eliminar Area" href="javascript:void()">
        <span class="icon-delete">
        </span>
    </a>
</div>