@extends("../layouts.plantilla")

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
		<?php foreach ($preguntas as $pregunta): ?>
			<tr>
				<th scope="row"><?php echo $contador; ?></th>
				<td>{{$pregunta->pregunta}}</td>
				<td>{{$opciones[$contador-1]->opcion}}</td>

				<td>
					
					<a href="#" class="mr-2" style="color: rgb(70,115,200);" data-id-pregunta="{{$pregunta->id}}" data-id-opcion="{{$opciones[$contador-1]->id}}" data-pregunta="{{$pregunta->pregunta}}" data-opcion="{{$opciones[$contador-1]->opcion}}" data-toggle="modal" data-target="#editModal"><i class="fas fa-pencil-alt"></i></a>
					
					<a href="#" class="ml-2" style="color: rgb(200,10,50);" data-id-pregunta="{{$pregunta->id}}" data-id-opcion="{{$opciones[$contador-1]->id}}" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash-alt"></i></a>

				</td>
			</tr>
			<?php $contador++ ?>
		<?php endforeach; ?>
	</tbody>
</table>

<div class="d-flex justify-content-end m-3">
	<button type="button" class="btn btn-info btn mt-3" data-toggle="modal" data-target="#createModal">
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
			<div class="modal-body">

				<form action="{{ route('crear-pregunta-grupo',$grupo->id)}}" method="POST">

					<div class="form-group" style="display:;">
						<label class="col-form-label" for="idGrupo">Grupo ID:</label>
						<input type="text" class="form-control" name="idGrupo" placeholder="ID de Pregunta" id="idGrupo" value="{{$grupo->id}}">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="pregunta">Pregunta:</label>
						<input type="text" class="form-control" name="pregunta" placeholder="Inserte el texto de la Pregunta" id="pregunta">
					</div>
					<div class="form-group">
						<label class="col-form-label" for="opcion">Respuesta:</label>
						<input type="text" class="form-control" name="opcion" placeholder="Inserte el texto de la Respuesta" id="opcion">
					</div>

					{{ csrf_field() }}

					<button type="submit" class="btn btn-primary">Guardar Cambios</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

				</form>
			</div>
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
			<div class="modal-body">

				<form action="{{ route('editar-pregunta-grupo',$grupo->id)}}" method="POST">

					<div class="form-group" style="display:none;">
						<label class="col-form-label" for="idPregunta">Pregunta ID:</label>
						<input type="text" class="form-control" name="idPregunta" placeholder="ID de Pregunta" id="idPregunta">
						<label class="col-form-label" for="idOpcion">Opcion ID:</label>
						<input type="text" class="form-control" name="idOpcion" placeholder="ID de Pregunta" id="idOpcion">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="pregunta">Pregunta:</label>
						<input type="text" class="form-control" name="pregunta" placeholder="Inserte el texto de la Pregunta" id="pregunta">
					</div>
					<div class="form-group">
						<label class="col-form-label" for="opcion">Respuesta:</label>
						<input type="text" class="form-control" name="opcion" placeholder="Inserte el texto de la Respuesta" id="opcion">
					</div>

					{{ csrf_field() }}

					<button type="submit" class="btn btn-primary">Guardar Cambios</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

				</form>
			</div>
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
					<div class="form-group" style="display:;">
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

	<script>

        $('#editModal').on('show.bs.modal', function(event){

            var link = $(event.relatedTarget)

            var idPregunta = link.data('id-pregunta')
            var idOpcion = link.data('id-opcion')
            var opcion = link.data('opcion')
            var pregunta = link.data('pregunta')
            
            var modal = $(this)

            modal.find('.modal-body #idPregunta').val(idPregunta)
            modal.find('.modal-body #idOpcion').val(idOpcion)
            modal.find('.modal-body #pregunta').val(pregunta)
            modal.find('.modal-body #opcion').val(opcion)

        })

        $('#deleteModal').on('show.bs.modal', function(event){

            var link = $(event.relatedTarget)

            var idPregunta = link.data('id-pregunta')
            var idOpcion = link.data('id-opcion')
            
            var modal = $(this)

            modal.find('.modal-footer #idPregunta').val(idPregunta)
            modal.find('.modal-footer #idOpcion').val(idOpcion)

        })

    </script>
@endsection