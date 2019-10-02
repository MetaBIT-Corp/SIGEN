@extends("../layouts.plantilla")

@section("css")
    <link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>
    <!--Css para Datatable-->
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section("ol_breadcrumb")
    <li class="breadcrumb-item">
        <a href="{{ route('materias') }}">
            Materias
        </a>
    </li>
    <li class="breadcrumb-item">
    	<a @if($area->materia!=null)href="{{ URL::signedRoute('getAreaIndex',['materia_id'=>$area->materia->id_cat_mat]) }}"  @else href="{{ route('areas_encuestas') }}" @endif id="id-area" data-area="{{ $area->id }}">
            Áreas
        </a>
    </li>
    <li class="breadcrumb-item">
    	<a>
            {{ $area->titulo }}
        </a>
    </li>
    <li class="breadcrumb-item">
    	@if(Request::get('id_gpo')==1)
        	Listado de Emparejamiento
            <label id="gpo-preg" data-control="0" data-token="{{ csrf_token() }}"></label>
        @else
        	Listado de preguntas
            <label id="gpo-preg" data-control="1" data-token="{{ csrf_token() }}"></label>
        @endif
    </li>
    @if(Request::get('id_gpo')==0)
        <div class="col-5 text-right">
            <a class="btn" href="javascript:void(0)" data-target="#modal" data-toggle="modal" id="add_pregunta">
                <span class="icon-add text-primary">
                </span>
            </a>
            <strong>
                Agregar Pregunta
            </strong>
        </div>
    @else
        <div class="col-5 text-right">
            <a class="btn" href="#" data-toggle="modal" data-target="#createModal">
                <span class="icon-add text-primary">
                </span>
            </a>
            <strong>
                Agregar Grupo
            </strong>
        </div>
    @endif

@endsection

@section('main')
    <div id="message-success" class="alert alert-success alert-dismissible fade show text-center" role="alert" hidden>
      <strong id="text-success">Exito</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div id="message-error" class="alert alert-danger alert-dismissible fade show text-center" role="alert" hidden>
      <strong id="text-error">Error</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <!--DATA TABLE-->
    <div class="container mt-3 mb-3">
        <table class="table table-striped table-bordered" id="areas" style="width:100%">
            <thead>
                <tr>
                	<th>
                        #
                    </th>
                    <th>
                    @if(Request::get('id_gpo')==1)
    				Descripcion grupo emparejamiento
    				@else
    				Pregunta
    				@endif
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
        </table>
    </div>
<!-- Modal -->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal">
                        Ejemplo
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
                            <label class="col-sm-4 col-form-label" for="inputPassword">
                                Pregunta
                            </label>
                            <div class="col-sm-8">
                                <input type="hidden" id="pregunta_id" name="pregunta_id"/>
                                <input class="form-control" id="pregunta" name="pregunta" placeholder="Titulo" required="" type="text"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" id="salir" type="button"
                        onclick="$('#validacion').attr('hidden',true);">
                            Salir
                        </button>
                        <input class="btn btn-primary" id="modificar" type="button" value="Modificar"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal2-->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal1" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="h4 text-danger icon-information-solid mr-2 mt-1"></span>
                    <h5 class="modal-title" id="exampleModalLabel">
                      Eliminar Area
                    </h5>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">
                            ×
                        </span>
                    </button>
                </div>
                <form id="form-elim" method="DELETE">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-12 col-form-label" for="inputPassword">
                                <strong>¿Esta seguro que desea eliminar la pregunta?</strong>
                            </label>
                            <input hidden="" id="id_preg_eli" name="id" type="number"/>
                            <p class="ml-3 mr-3 mb-0 text-justify">Se eliminaran las opciones asociadas con la pregunta, al ejecutar esta accion. </p>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" id="salir_eli" type="button">
                            Salir
                        </button>
                        <input class="btn btn-danger" id="eliminar" type="button" value="Eliminar"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para la creación de grupo emparejamiento -->

    <div class="modal" id="createModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Grupo de Emparejamiento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('crear-grupo-emparejamiento',$area->id)}}" method="POST">
                    <div class="modal-body">
                        <div class="form-group" style="display:;">
                            <label class="col-form-label" for="areaid">Area ID:</label>
                            <input type="text" class="form-control" name="areaid" placeholder="ID de Pregunta" id="areaid" value="{{$area->id}}">
                        </div>

                        <div class="form-group">
                            <label class="col-form-label" for="descripcion">Descripción del Grupo:</label>
                            <input type="text" class="form-control" name="descripcion" placeholder="Inserte la Descripción" id="descripcion">
                        </div>

                    </div>

                    <div class="alert alert-danger m-3" id="alerta">
                        <ul id="ul-alert">
                        </ul>
                    </div>

                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <button type="submit" id="btn-agregar" class="btn btn-primary">Agregar Grupo de Emparejamiento</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>                
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<!--Scripts para datatables con Laravel-->
@section("js")
    <script type="text/javascript" src="{{ asset('js/pregunta/pregunta.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('js/pregunta/grupo.js')}}"></script>
@endsection