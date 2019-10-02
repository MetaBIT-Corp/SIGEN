@extends("../layouts.plantilla")

@section('css')
	<link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.min.css')}}">
@endsection

@section("ol_breadcrumb")

	<li class="breadcrumb-item">
		<a href="{{ route('materias') }}">
			Materias
		</a>
	</li>
	<li class="breadcrumb-item">
		<a href="{{ URL::signedRoute('getAreaIndex', ['id' => $area->id_cat_mat]) }}">
			Áreas
		</a>
	</li>
	<li class="breadcrumb-item">
		<a href="{{ URL::signedRoute('getPreguntas', ['id' => $area->id, 'id_gpo'=>true]) }}">
			Preguntas
		</a>
	</li>
	<li class="breadcrumb-item">
	    Opciones
	</li>

@endsection

@section('main')

<?php $contador=1; ?>

	<h3 class="mt-2 mb-5"><b>Descripción</b>: <?php echo $grupo->descripcion_grupo_emp ?></h3> 

	<table class="table table-hover" style="text-align: center;">
	<thead>
		<tr><th colspan="4" style="text-align: right; color: rgb(100,180,10);">Preguntas de Emparejamiento</th></tr>
		<tr><th colspan="4" style="text-align: left; font-size: 1.3em;">Opciones del Grupo</th></tr>
		<tr class="table-primary">
			<th scope="col">N°</th>
			<th scope="col">Pregunta</th>
			<th scope="col">Respuesta</th>
			<th scope="col">Acciones</th>
		</tr>
	</thead>
	<tbody>

		@if(isset($preguntas))
			<?php foreach ($preguntas as $pregunta): ?>
				<tr>
					<th scope="row"><?php echo $contador; ?></th>
					<td>{{$pregunta->pregunta}}</td>
					<td>{{$opciones[$contador-1]->opcion}}</td>

					<td>

						@if($opciones_incorrectas[$contador-1]!=null)
						
							<a href="#" class="mr-2 btn-editar btn" id="btn_editar" data-id-pregunta="{{$pregunta->id}}" data-id-opcion="{{$opciones[$contador-1]->id}}" data-pregunta="{{$pregunta->pregunta}}" data-opcion="{{$opciones[$contador-1]->opcion}}" data-opcion-incorrecta="{{$opciones_incorrectas[$contador-1]->opcion}}" data-opcion-incorrecta-id="{{$opciones_incorrectas[$contador-1]->id}}" data-toggle="modal" data-target="#editModal"><i class="fas fa-pencil-alt"></i></a>

						@else
							<a href="#" class="mr-2 btn-editar btn" id="btn_editar" data-id-pregunta="{{$pregunta->id}}" data-id-opcion="{{$opciones[$contador-1]->id}}" data-pregunta="{{$pregunta->pregunta}}" data-opcion="{{$opciones[$contador-1]->opcion}}" data-opcion-incorrecta="" data-toggle="modal" data-target="#editModal"><i class="fas fa-pencil-alt"></i></a>
						@endif
						
						<a href="#" class="ml-2 btn-eliminar btn" id="btn_eliminar" data-id-pregunta="{{$pregunta->id}}" data-id-opcion="{{$opciones[$contador-1]->id}}" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash-alt"></i></a>

					</td>
				</tr>
				<?php $contador++ ?>
			<?php endforeach; ?>
		@else
			<tr>
				<td colspan="4" class="p-5">
					<h4>Grupo de Emparejamiento vacío.</h4>
				</td>
			</tr>
		@endif
	</tbody>
</table>

<div class="d-flex justify-content-end m-3">
	<button type="button" class="btn btn-info btn mt-3 mx-3" id="btn-agregar" data-toggle="modal" data-target="#createModal">
		<i class="fas fa-plus-circle"></i> Agregar Pregunta
	</button>
</div>

<!-- Modal para la creacion de nueva Pregunta y Opcion del Grupo. -->

<div class="modal" id="createModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Crear Pregunta de Grupo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{ route('crear-pregunta-grupo',$grupo->id)}}" method="POST">
				<div class="modal-body">
					<div class="form-group" style="display:none;">
						<label class="col-form-label" for="idGrupo">Grupo ID:</label>
						<input type="text" class="form-control" name="idGrupo" placeholder="ID de Pregunta" id="idGrupo" value="{{$grupo->id}}">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="pregunta">Pregunta:</label>
						<input type="text" class="form-control" name="pregunta" placeholder="Inserte el texto de la Pregunta" id="pregunta">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="opcion">Respuesta Correcta:</label>
						<input type="text" class="form-control" name="opcion" placeholder="Inserte el texto de la Respuesta" id="opcion">
					</div>

					<div class="form-group" id="incorrecta">
						<label class="col-form-label" for="opcionincorrecta">Respuesta Incorrecta:</label>
						<input type="text" class="form-control" name="opcionincorrecta" placeholder="Inserte el texto de la Respuesta" id="opcionincorrecta">
						<button id="cancelar-incorrecta" class="btn btn-link float-right p-0">Cancelar</button>
					</div>

					<div class="form-group text-right">
						<button id="btn-incorrecta" class="btn btn-outline-dark">Agregar Respuesta Incorrecta</button>
					</div>
				</div>

				<div class="alert alert-danger m-3" id="alerta">
					<ul id="ul-alert">
					</ul>
				</div>

				<div class="modal-footer">
					{{ csrf_field() }}
					<button type="submit" id="btn-crear" class="btn btn-primary">Agregar Pregunta</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>				
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Fin de modal para creación de Pregunta y Opcion del Grupo. -->

<!-- Modal para la edición de Pregunta y Opcion del Grupo. -->

<div class="modal" id="editModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Pregunta de Grupo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{ route('editar-pregunta-grupo',$grupo->id)}}" method="POST">
				<div class="modal-body">
					<div class="form-group" style="display:none;">
						<label class="col-form-label" for="idPregunta">Pregunta ID:</label>
						<input type="text" class="form-control" name="idPregunta" placeholder="ID de Pregunta" id="idPregunta">
						<label class="col-form-label" for="idOpcion">Opcion ID:</label>
						<input type="text" class="form-control" name="idOpcion" placeholder="ID de Pregunta" id="idOpcion">
						<label class="col-form-label" for="idOpcionIncorrecta">Opcion ID:</label>
						<input type="text" class="form-control" name="idOpcionIncorrecta" placeholder="ID de Pregunta" id="idOpcionIncorrecta">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="pregunta">Pregunta:</label>
						<input type="text" class="form-control" name="pregunta" placeholder="Inserte el texto de la Pregunta" id="pregunta">
					</div>
					<div class="form-group">
						<label class="col-form-label" for="opcion">Respuesta:</label>
						<input type="text" class="form-control" name="opcion" placeholder="Inserte el texto de la Respuesta" id="opcion">
					</div>
					<div class="form-group" id="incorrecta-edit">
						<label class="col-form-label" for="opcionincorrectaedit">Respuesta Incorrecta:</label>
						<input type="text" class="form-control" name="opcionincorrectaedit" placeholder="Inserte el texto de la Respuesta" id="opcionincorrectaedit">
						<button id="eliminar-incorrecta-edit" class="btn btn-link float-right p-0">Eliminar</button>
					</div>

					<div class="form-group text-right">
						<button id="btn-incorrecta-edit" class="btn btn-outline-dark">Agregar Respuesta Incorrecta</button>
					</div>
				</div>

				<div class="alert alert-danger mx-2" id="alerta-edit">
					<ul id="ul-alert-edit">
					</ul>
				</div>

				<div class="modal-footer">
					{{ csrf_field() }}
					<button type="submit" id="btn-guardar" class="btn btn-primary">Guardar Cambios</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Fin de modal para edición de Pregunta y Opción del Grupo. -->

<!-- Modal para la eliminacioón de Pregunta y Opcion del Grupo -->

<div class="modal" id="deleteModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Eliminar Pregunta</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<legend>¿Está seguro que quiere eliminar la pregunta?</legend>
			</div>
			<div class="modal-footer">
				<form action="{{ route('eliminar-pregunta-grupo',$grupo->id)}}" method="POST">
					<div class="form-group" style="display:none;">
						<label class="col-form-label" for="idPregunta">Pregunta ID:</label>
						<input type="text" class="form-control" name="idPregunta" placeholder="ID de Opción" id="idPregunta">
						<label class="col-form-label" for="idOpcion">Opcion ID:</label>
						<input type="text" class="form-control" name="idOpcion" placeholder="ID de Opción" id="idOpcion">
					</div>
					{{ csrf_field() }}
					<button type="submit" class="btn btn-danger">Si, eliminar</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Fin de modal para eliminación de Pregunta y Opción del Grupo -->

@endsection

@section('js')
	<script src="{{asset('js/opcion/opcionGrupo.js')}}"></script>
@endsection