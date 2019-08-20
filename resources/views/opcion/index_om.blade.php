@extends("../layouts.plantilla")

@section('main')

<?php $contador=1; ?>

<h3 class="mt-2 mb-5"><b>Pregunta</b>: <?php echo $pregunta->pregunta ?></h3>

<table class="table table-hover" style="text-align: center;">
	<thead>
		<tr><th colspan="4" style="text-align: right; color: rgb(100,180,10);">Pregunta de Opción Múltiple</th></tr>
		<tr><th colspan="4" style="text-align: left; font-size: 1.3em;">Opciones de Pregunta</th></tr>
		<tr class="table-primary">
			<th scope="col">N°</th>
			<th scope="col">Opción</th>
			<th scope="col">Correcta</th>
			<th scope="col">Opciones</th>
		</tr>
	</thead>
	<tbody>

		<?php foreach ($opciones as $opcion): ?>
			<tr>
				<th scope="row"><?php echo $contador; ?></th>
				<td  style="text-align: left;">{{$opcion->opcion}}</td>
				<?php if($opcion->correcta==1): ?>
					<td><input type="radio" disabled="" checked=""></td>
				<?php else: ?>
					<td><input type="radio" disabled=""></td>
				<?php endif ?>
				<td>
					
					<a href="#" class="mr-2" style="color: rgb(70,115,200);" data-id="{{$opcion->id}}" data-opcion="{{$opcion->opcion}}" data-correcta="{{$opcion->correcta}}" data-tipo="{{$tipo_opcion}}" data-toggle="modal" data-target="#editModal"><i class="fas fa-pencil-alt"></i></a>
					
					<a href="" class="ml-2" style="color: rgb(200,10,50);" data-id="{{$opcion->id}}" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash-alt"></i></a>

				</td>
			</tr>
			<?php $contador++ ?>
		<?php endforeach; ?>

	</tbody>
</table>

<div class="d-flex justify-content-end m-3">
	<button type="button" class="btn btn-info btn mt-3" data-toggle="modal" data-target="#createModal">
		<i class="fas fa-plus-circle"></i> Agregar Opción
	</button>
</div>

<!-- Modal para la creación de nuevas opciones en la pregunta -->

<div class="modal" id="createModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Agregar nueva Opción a Pregunta</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form action="{{ route('agregar-opcion',$pregunta->id)}}" method="POST">

					<div class="form-group" style="display:none;">
						<label class="col-form-label" for="pregunta_id">Pregunta_ID:</label>
						<input type="text" class="form-control" name="pregunta_id" placeholder="ID de Pregunta" id="pregunta_id" value="{{$pregunta->id}}">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="opcion">Opción:</label>
						<input type="text" class="form-control" name="opcion" placeholder="Inserte el texto de la Opción" id="opcion">
					</div>
					<div class="form-group">
						<div class="custom-control custom-radio">
							<input type="radio" id="correctaSi" name="correcta" class="custom-control-input" value="1">
							<label class="custom-control-label" for="correctaSi">Es correcta</label>
						</div>
						<div class="custom-control custom-radio">
							<input type="radio" id="correctaNo" name="correcta" class="custom-control-input" value="0" checked="">
							<label class="custom-control-label" for="correctaNo">No es correcta</label>
						</div>
					</div>
					{{ csrf_field() }}

					<button type="submit" class="btn btn-primary">Agregar</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

				</form>
			</div>
		</div>
	</div>
</div>

<!-- Fin de Modal para Agregar Nuevas preguntas -->


<!-- Modal para la edición de opciones en la pregunta -->

<div class="modal" id="editModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Opción de Pregunta</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form action="{{ route('actualizar-opcion',$pregunta->id)}}" method="POST">

					<div class="form-group" style="display:none;">
						<label class="col-form-label" for="opcion">Pregunta_ID:</label>
						<input type="text" class="form-control" name="id" placeholder="ID de Pregunta" id="id">
						<label class="col-form-label" for="opcion">Tipo Opcion</label>
						<input type="text" class="form-control" name="tipo_opcion" placeholder="Tipo de Opción" id="tipo_opcion">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="opcion">Opción:</label>
						<input type="text" class="form-control" name="opcion" placeholder="Inserte el texto de la Opción" id="opcion">
					</div>
					<div class="form-group">
						<div class="custom-control custom-radio">
							<input type="radio" id="correctaSiEdit" name="correctaEdit" class="custom-control-input" value="1">
							<label class="custom-control-label" for="correctaSiEdit">Es correcta</label>
						</div>
						<div class="custom-control custom-radio">
							<input type="radio" id="correctaNoEdit" name="correctaEdit" class="custom-control-input" value="0">
							<label class="custom-control-label" for="correctaNoEdit">No es correcta</label>
						</div>
					</div>
					{{ csrf_field() }}

					<button type="submit" class="btn btn-primary">Guardar Cambios</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

				</form>
			</div>
		</div>
	</div>
</div>

<!-- Fin de modal para edición de opcion -->

<!-- Modal para la eliminacioón de opciones en la pregunta -->

<div class="modal" id="deleteModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Eliminar Opción de Pregunta</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<legend>¿Está seguro que quiere eliminar la opción?</legend>
			</div>
			<div class="modal-footer">
				<form action="{{ route('eliminar-opcion',$pregunta->id)}}" method="POST">
					<div class="form-group" style="display:none;">
						<label class="col-form-label" for="id">Opcion_ID:</label>
						<input type="text" class="form-control" name="id" placeholder="ID de Opción" id="id">
					</div>
					{{ csrf_field() }}
					<button type="submit" class="btn btn-danger">Si, eliminar</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Fin de modal para eliminación de opcion -->




@endsection

@section('js')

	<script>
        $('#editModal').on('show.bs.modal', function(event){

            var link = $(event.relatedTarget)

            var opcion = link.data('opcion')
            var tipo_opcion = link.data('tipo')
            var correcta = link.data('correcta')
            var id = link.data('id')

            var modal = $(this)

            console.log(correcta)

            if (correcta==1) {
            	modal.find(".modal-body #correctaSiEdit").prop('checked',true);
            }else{
            	modal.find(".modal-body #correctaNoEdit").prop('checked',true);
            }

            modal.find('.modal-body #opcion').val(opcion)
            modal.find('.modal-body #tipo_opcion').val(tipo_opcion)
            modal.find('.modal-body #id').val(id)

        })

        $('#deleteModal').on('show.bs.modal', function(event){

            var link = $(event.relatedTarget)

            var id = link.data('id')

            var modal = $(this)

            console.log(id)

            modal.find('.modal-footer #id').val(id)

        })

    </script>
@endsection