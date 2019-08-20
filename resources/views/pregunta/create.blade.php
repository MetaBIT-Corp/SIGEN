@extends("../layouts.plantilla")
@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>
@endsection
@section("body")

@section("ol_breadcrumb")
<li class="breadcrumb-item">
    <a href="{{ route('get_area',[$area->materia->id_cat_mat]) }}">
        Areas
    </a>
</li>
<li class="breadcrumb-item">
    {{ $area->titulo }}
</li>
<li class="breadcrumb-item">
    Crear Pregunta
</li>
@endsection

@section("main")

@if(count($errors))
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)             
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="offset-1 col-md-10">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <strong>
            {{ session('success') }}
        </strong>
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">
                ×
            </span>
        </button>
    </div>
    @endif
    <div class="card">
        <div class="card-header h4">
            <strong>
                Agregar Pregunta
            </strong>
        </div>
        <div class="card-body">
            <div class="offset-2 col-md-8">
                <form action="{{ route('pregunta.store',[$area->id]) }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <!--<input class="form-control" hidden="" name="area_id" type="number" value="{{ $area->id }}">-->
                            <label class="col-sm-4 col-form-label" for="pregunta">
                                Pregunta
                            </label>
                            <div class="col-8">
                                <input class="form-control" id="pregunta" name="pregunta" placeholder="Pregunta" type="text" value="{{ old('pregunta') }}">
                                </input>
                            </div>
                        </input>
                    </div>
                    @if($area->tipo_item->id==3)
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="inputPassword">
                            Grupo Emparejamiento:
                        </label>
                        <div class="col-sm-6">
                            <select class="custom-select mt-3" name="gpo_emp">
                                <option value="0">
                                    Seleccione Grupo
                                </option>
                                @forelse($area->grupos_emparejamiento as $gpo)
                                <option value="{{ $gpo->id }}" @if(old('gpo_emp')==$gpo->id) selected @endif>
                                    {{ $gpo->descripcion_grupo_emp }}
                                </option>
                                @empty
                                <option value="0">
                                    No hay grupos en esta area
                                </option>
                                @endforelse
                            </select>
                        </div>
                        <!--
                        <a class="btn mt-3" data-target="#modal" data-toggle="modal" id="btn_add">
                            <span class="icon-add text-primary" title="Nuevo grupo emparejamiento">
                            </span>
                        </a>-->
                    </div>
                    @endif
                    <div class="row">
                        <div class="form-group offset-3">
                            <button class="btn btn-primary mt-3" type="submit">
                                Guardar
                            </button>
                        </div>
                        <div class="form-group offset-1">
                            <button class="btn btn-secondary mt-3" onclick="location.href='{{ route('get_area',[$area->materia->id_cat_mat]) }}'" type="button">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal 
<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Agregar Grupo Emparejamiento
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
                    <div class="alert alert-danger" hidden="" id="validacion" role="alert">
                        Campo requerido para continuar.
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="input_descripcion">
                            Descripcion
                        </label>
                        <div class="col-sm-9">
                            <input hidden="" id="id_area" name="id_area" type="number"/>
                            <textarea class="form-control" id="input_descripcion" name="descripcion" placeholder="Descripcion de Grupo de Emparejamiento" required="true">
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" id="salir" type="button">
                        Salir
                    </button>
                    <input class="btn btn-primary" id="modificar" type="button" value="Modificar"/>
                </div>
            </form>
        </div>
    </div>
</div>
-->
@endsection
