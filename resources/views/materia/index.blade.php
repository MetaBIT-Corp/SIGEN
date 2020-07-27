@extends("../layouts.plantilla")

@section('css')
	<link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet">
@endsection

@section("body")

@section("ol_breadcrumb")

	<li class="breadcrumb-item"><a href="{{ route('materias') }}">Home</a></li>
	<li class="breadcrumb-item">Todas las Materias</li>

	<div class="offset-7 btn-group" role="group" aria-label="Basic example">

        <!--Boton para agregar Materia-->
        <a class="btn btn-secondary" title="Agregar Materia" href="#" data-toggle="modal" data-target="#materiaModal">
            <h6 class="mb-0">
                <span class="icon-add-solid"></span>
            </h6>
        </a>

        <!--Icono para descargar plantilla-->
        <a class="btn btn-secondary" href="{{ route('materia_plantilla') }}" title="Descargar Plantilla Excel">
            <h6 class="mb-0">
                <span class="icon-download"></span>
            </h6>
        </a>

        <!--Icono para importar Docentes -->
        <a class="btn btn-secondary" href="" title="Importar Docentes" id="importExcel">
            <h6 class="mb-0">
                <span class="icon-importExcel"></span>
            </h6>
        </a>
    </div>
    <!--Formulario para subida de archivos de excel-->
    <form method="POST" id="form-excel" enctype="multipart/form-data">
            @csrf
        <input type="file" name="archivo" accept=".xlsx" id="fileExcel" hidden="" />
    </form>


@endsection

@section('main')

@if(session('notification-message') and session('notification-type'))
          <div class="alert alert-{{ session('notification-type') }} text-center alert-dismissible fade show" role="alert">
            <h5>{{ session('notification-message') }}</h5>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif

          @if (session('message'))
            <div class="alert alert-success">
                <h6 class="text-center">{!! session('message') !!}</h6>
            </div>
          @endif

        <div class="text-center" id="spinner" hidden="true">
            <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;" role="status" >
            </div><br>
            <span class="">Importando ...</span>
        </div>
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

	<div class="card mb-3 overflow-hidden">
	    <div class="card-header row">
	    	<div class="col-md-9">
		    	<i class="fas fa-table"></i>
		    	Listado de Materias | Global
	    	</div>	    	
	    </div>
	    <div class="card-body">
	    	<div class="table-responsive">
	    		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
	    			<thead>
	    				<tr class="text-center">
	    					<th>Código</th>
	    					<th>Nombre</th>
	    					<th>Preguntas</th>
	    					<th>Tipo</th>                					
	    					<th>Acciones</th>
	    				</tr>
	    			</thead>
	                <tfoot>
	                    <tr class="text-center">
	    					<th>Código</th>
	    					<th>Nombre</th>
	    					<th>Preguntas</th>
	    					<th>Tipo</th>
	    					<th>Acciones</th>
	    				</tr>
	                </tfoot>
	                <tbody>
	                    @if(count($materias)>0)
	                        @foreach($materias as $materia)
	                            <tr>
	                                <td>{{ $materia->codigo_mat }}</td>
	                                <td>{{ $materia->nombre_mar }}</td>
	                                <td class="text-center">{{ $materia->maximo_cant_preguntas }}</td>
	                                @if( $materia->es_electiva!=1)
	                                    <td class="text-center"><span class="badge badge-info col-md-8">Obligatoria</span></td>
	                                @else
	                                    <td class="text-center"><span class="badge badge-success col-md-8">Electiva</span></td>
	                                @endif
	                                <td class="text-center">
	                                	@if(auth()->user()->IsAdmin)
	                                	<a href="#" class="btn btn-sm btn-option mr-1" title="Editar Materia"
	                                	data-id="{{$materia->id_cat_mat}}"
	                                	data-codigo="{{$materia->codigo_mat}}"
	                                	data-materia="{{$materia->nombre_mar}}"
	                                	data-tipo="{{$materia->es_electiva}}"
	                                	data-preguntas="{{$materia->maximo_cant_preguntas}}"
	                                	data-toggle="modal"
	                                	data-target="#materiaModal">
	                                	<span class="icon-edit"></span></a>

	                                	<a href="#" class="btn btn-sm btn-danger ml-1" title="Eliminar Materia"
	                                	data-id="{{$materia->id_cat_mat}}"
	                                	data-codigo="{{$materia->codigo_mat}}"
	                                	data-materia="{{$materia->nombre_mar}}"
	                                	data-toggle="modal"
	                                	data-target="#deleteModal">
	                                	<span class="icon-delete"></span></a>
	                                	@endif
	                                </td>
	                            </tr>
	                        @endforeach
	                    @else
	                        <tr>
	                            <td colspan="5">No se encuentran resultados disponibles</td>
	                        </tr>
	                    @endif
	                </tbody>
	            </table>
	        </div>
	    </div>
	    <div class="card-footer small text-muted">
	    	Actualizado en: {{ date('d-M-Y h:i A', strtotime($last_update)) }}
	    </div>
	</div>

	<!-- Modal para la edición de materias registradas en el sistema -->

	<div class="modal" id="materiaModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				
				<div class="modal-header">
					<h5 class="modal-title">Materia</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form action="{{ route('materia_update',$materia->id_cat_mat)}}" method="POST" class="mb-0">
					
					<div class="modal-body">

						<div class="form-group d-none">
							<label for="materia-id" class="col-form-label">Materia Id:</label>
							<input type="text" id="materia-id" name="materia_id" class="form-control" placeholder="ID de Materia">
						</div>

						<div class="form-group">
							<label for="materia" class="col-form-label">Nombre de Materia:</label>
							<input type="text" id="materia" name="materia" class="form-control" placeholder="Nombre de Materia">
						</div>
						<div class="form-group">
							<label for="materia-codigo" class="col-form-label">Código de Materia:</label>
							<input type="text" id="materia-codigo" name="materia_codigo" class="form-control" placeholder="Código de Materia">
						</div>

						<span>Tipo de Materia:</span>
						<div class="form-group ml-3 mt-2">
							<div class="custom-control custom-radio">
								<input type="radio" id="materia-obligatoria" name="materia_tipo" class="custom-control-input" value="0">
								<label class="custom-control-label" for="materia-obligatoria">Obligatoria</label>
							</div>
							<div class="custom-control custom-radio">
								<input type="radio" id="materia-electiva" name="materia_tipo" class="custom-control-input" value="1">
								<label class="custom-control-label" for="materia-electiva">Electiva</label>
							</div>
						</div>

						<div class="form-group">
							<label for="materia-preguntas" class="col-form-label">Cantidad de preguntas de Materia:</label>
							<input type="text" id="materia-preguntas" name="materia_preguntas" class="form-control" placeholder="Cantidad de preguntas de Materia">
						</div>

						<div id="errorDiv" class="alert alert-danger m-3">
							<ul id="errorUl">
								
							</ul>
						</div>
					
					</div>

					<div class="modal-footer">
						{{ csrf_field() }}
						<button type="submit" id="materiaEditBtnSubmit" class="btn btn-primary">Guardar Cambios</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>

				</form>

			</div>
		</div>
	</div>

	<!-- Fin de modal para edición de opcion -->

@endsection

@section('js')
	<script src="{{asset('js/materia/materia.js')}}"> </script>

	<script type="text/javascript">

       

        $(document).ready(function() {
            function exito(datos) {
                $("#message-success").removeAttr("hidden");
                $("#text-success").text(datos.success);
                //Para mover al inicio de la pagina el control
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                setTimeout(function() {
                    $("#message-success").attr('hidden', true);
                    location.reload();
                }, 6000);
            }

            $('#importExcel').click(function(e) {
                //Evita que se recarge la pagina, porque sino no guarda el archivo en la variable input type file.
                e.preventDefault();
                $('#fileExcel').click();
            });

            $('#fileExcel').on("change", function() {
                var data = new FormData($("#form-excel")[0]);
                //Mostrando Spinner
                $("#spinner").removeAttr("hidden");
                $.ajax({
                    url: '/materias/upload-excel/',
                    type: "POST",
                    data: data,
                    contentType: false, //Importante para enviar el archivo
                    processData: false, //Importante para enviar el archivo
                    dataType: "json"
                }).done(function(datos) {
                    $('#fileExcel').val("");
                    console.log(datos.type);
                    if (datos.type == 2) {
                        exito(datos);
                    } else {
                        $("#message-error").removeAttr("hidden");
                        $("#text-error").text(datos.error);
                        //Para mover al inicio de la pagina el control
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        setTimeout(function() {
                            $("#message-error").attr('hidden', true);
                        }, 6000);
                    }
                    //Para ocultar spinner
                    $("#spinner").attr("hidden",true);

                }).fail(function(xhr, status, e) {
                    //Para ocultar spinner
                    $("#spinner").attr("hidden",true);
                    console.log(e);
                });
            });
        });


    </script>
@endsection