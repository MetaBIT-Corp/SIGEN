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

						@if($incorrectas[$contador-1]!=null)

						<a href="#" class="mr-2 btn-editar btn" id="btn_editar" data-id-pregunta="{{$pregunta->id}}" data-pregunta="{{$pregunta->pregunta}}" data-id-correcta="{{$opciones[$contador-1]->id}}" data-correcta="{{$opciones[$contador-1]->opcion}}" data-total-incorrectas="{{count($incorrectas[$contador-1])}}"  
								@foreach ($incorrectas[$contador-1] as $incorrecta)
									data-id-incorrecta-{{$loop->iteration}}="{{$incorrecta->id}}"
									data-incorrecta-{{$loop->iteration}}="{{$incorrecta->opcion}}"
								@endforeach
							data-toggle="modal" data-target="#edit-modal"><i class="fas fa-pencil-alt"></i></a>

						@else

							<a href="#" class="mr-2 btn-editar btn" id="btn_editar" data-id-pregunta="{{$pregunta->id}}" data-pregunta="{{$pregunta->pregunta}}" data-id-correcta="{{$opciones[$contador-1]->id}}" data-correcta="{{$opciones[$contador-1]->opcion}}" data-total-incorrectas="{{count($incorrectas[$contador-1])}}" data-toggle="modal" data-target="#edit-modal"><i class="fas fa-pencil-alt"></i></a>

						@endif
						
						<a href="#" class="ml-2 btn-eliminar btn" id="btn_eliminar" data-id-pregunta="{{$pregunta->id}}" data-toggle="modal" data-target="#delete-modal"><i class="fas fa-trash-alt"></i></a>

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
	<button type="button" class="btn btn-info btn mt-3 mx-3" id="btn-agregar" data-toggle="modal" data-target="#create-modal">
		Agregar Pregunta&nbsp;&nbsp;&nbsp;<i class="fas fa-plus-circle"></i>
	</button>
</div>

<!-- Modal para la creacion de nueva Pregunta y Opcion del Grupo. -->

<div class="modal" id="create-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Crear Pregunta de Grupo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{ route('crear-pregunta-grupo',$grupo->id)}}" method="POST">
				<div class="modal-body" style=" max-height: calc(100vh - 200px); overflow-y: auto;">
					<div class="form-group" style="display:none;">
						<label class="col-form-label" for="idGrupo">Grupo ID:</label>
						<input type="text" class="form-control" name="idGrupo" placeholder="ID de Pregunta" id="idGrupo" value="{{$grupo->id}}">
						<input type="number" id="incorrectas-contador" name="incorrectas_contador" class="form-control-plaintext" value="0">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="pregunta">Pregunta:</label>
						<input type="text" class="form-control" name="pregunta" placeholder="Inserte el texto de la Pregunta" id="pregunta">
					</div>

					<div class="form-group">
						<label class="col-form-label text-success" for="opcion">Respuesta Correcta:</label>
						<input type="text" class="form-control" name="opcion" placeholder="Inserte el texto de la Respuesta" id="opcion">
					</div>

					<div id="incorrectas-div">
					</div>

					<div class="form-group text-right mt-3">
						<button id="btn-incorrecta" type="button" class="btn btn-outline-dark">Agregar Respuesta Incorrecta</button>
					</div>
				</div>

				<div class="alert alert-danger m-3" id="div-alerta">
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

<div class="modal" id="edit-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Pregunta de Grupo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{ route('editar-pregunta-grupo',$grupo->id)}}" method="POST">
				<div class="modal-body" style=" max-height: calc(100vh - 200px); overflow-y: auto;">
					<div class="form-group d-none">
						<label class="col-form-label" for="id-grupo-edit">ID Grupo:</label>
						<input type="text" id="id-grupo-edit" name="id_grupo_edit" class="form-control-plaintext" value="{{$grupo->id}}">
						<label class="col-form-label" for="id-pregunta">ID Pregunta:</label>
						<input type="text" id="id-pregunta" name="id_pregunta" class="form-control-plaintext" value="0">
						<label class="col-form-label" for="id-correcta">ID Correcta:</label>
						<input type="text" id="id-correcta" name="id_correcta" class="form-control-plaintext" value="0">
						<label class="col-form-label" for="incorrectas-contador-edit">Contador:</label>
						<input type="number" id="incorrectas-contador-edit" name="incorrectas_contador_edit" class="form-control-plaintext" value="0">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="pregunta-edit">Pregunta:</label>
						<input type="text" id="pregunta-edit" class="form-control" name="pregunta_edit" placeholder="Inserte el texto de la Pregunta">
					</div>

					<div class="form-group">
						<label class="col-form-label text-success" for="correcta-edit">Respuesta Correcta:</label>
						<input type="text" class="form-control" name="correcta_edit" placeholder="Inserte el texto de la Respuesta" id="correcta-edit">
					</div>

					<div id="incorrectas-div-edit">
					</div>

					<div class="form-group text-right mt-3">
						<button id="btn-incorrecta-edit" type="button" class="btn btn-outline-dark">Agregar Respuesta Incorrecta</button>
					</div>
				</div>

				<div class="alert alert-danger m-3" id="div-alerta-edit">
					<ul id="ul-alert-edit">
					</ul>
				</div>

				<div class="modal-footer">
					{{ csrf_field() }}
					<button type="submit" id="btn-guardar" class="btn btn-primary">Actualizar Pregunta</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>				
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Fin de modal para edición de Pregunta y Opción del Grupo. -->

<!-- Modal para la eliminacioón de Pregunta y Opcion del Grupo -->

<div class="modal" id="delete-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Eliminar Pregunta de Grupo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{ route('eliminar-pregunta-grupo',$grupo->id)}}" method="POST">
				<div class="modal-body">
					<div class="form-group d-none">
						<label class="col-form-label" for="id-pregunta-delete">ID Pregunta:</label>
						<input type="text" class="form-control" name="id_pregunta_delete" id="id-pregunta-delete">
					</div>
					<legend>¿Desea eliminar la Pregunta?</legend>
					<p class="lead">Esto eliminará permanentemente todas las Opciones asociadas con la Pregunta.</p>
				</div>
				<div class="modal-footer">
					{{ csrf_field() }}
					<button type="submit" class="btn btn-danger">Si, eliminar</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Fin de modal para eliminación de Pregunta y Opción del Grupo -->

@endsection

@section('js')
	<script src="{{asset('js/opcion/opcionGrupo.js')}}"></script>
@endsection