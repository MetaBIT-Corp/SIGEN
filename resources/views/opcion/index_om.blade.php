@extends("../layouts.plantilla")

@section('main')

<?php $contador=1; ?>
<?php $indiceCorrecta = 0; ?>

<h3 class="mt-2 mb-5"><b>Pregunta</b>: <?php echo $pregunta->pregunta ?></h3>

<form action="{{ route('agregar-opcion',$pregunta->id)}}" method="POST">

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

		<tbody id="tabla1">

			<?php foreach ($opciones as $opcion): ?>

				<tr>

					<th scope="row"><?php echo $contador; ?></th>
					<td  style="text-align: left;">{{$opcion->opcion}}</td>

					<?php if($opcion->correcta==1): ?>
						
						<td><input type="radio" disabled="" checked=""></td>

						<?php $indiceCorrecta = $contador; ?>
					
					<?php else: ?>

						<td><input type="radio" disabled=""></td>
					
					<?php endif ?>
					
					<td>
						
						<a href="#" class="mr-2" style="color: rgb(70,115,200);" data-id="{{$opcion->id}}" data-opcion="{{$opcion->opcion}}" data-correcta="{{$opcion->correcta}}" data-tipo="{{$tipo_opcion}}" data-toggle="modal" data-target="#editModal"><i class="fas fa-pencil-alt"></i></a>
						
						<a href="#" class="ml-2 btnDel" data-id="{{$opcion->id}}" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash-alt"></i></a>

					</td>

				</tr>

				<?php $contador++ ?>

			<?php endforeach; ?>

		</tbody>

	</table>

	<div class="form-group" style="display:;">

		<label class="col-form-label" for="indice">Indice:</label>
		<input type="text" class="form-control" name="indice" value="0" id="indice">
		<label class="col-form-label" for="contador">Contador:</label>
		<input type="text" class="form-control" name="contador" value="{{$contador-1}}" id="contador">
		<label class="col-form-label" for="pregunta_id">Pregunta_ID:</label>
		<input type="text" class="form-control" name="pregunta_id" placeholder="ID de Pregunta" id="pregunta_id" value="{{$pregunta->id}}">
		<label class="col-form-label" for="indiceco">Opción Correcta:</label>
		<input type="text" class="form-control" name="indiceco" value= "{{$indiceCorrecta}}" id="indiceco">

	</div>

	{{ csrf_field() }}

	<div class="d-flex justify-content-end m-3">
	</div>

	<div class="d-flex justify-content-end m-3">
		<button type="button" class="btn btn-info btn mt-3" id="btnAgregarFila" name="btnAgregarFila">
			<i class="fas fa-plus-circle"></i> Agregar Opción
		</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<button type="submit" class="btn btn-success btn mt-3" >
			<i class="fas fa-arrow-right"></i> Enviar Opciones
		</button>
	</div>
	<div class="d-flex justify-content-begin m-3" >
		<br><br><br><p id="infoP">*Las preguntas de Opción Múltiple no pueden tener menos de tres opciones</p>
	</div>

	@if(count($errors)>0)
		@foreach ($errors->all() as $error)
			<ul>
				<li>{{$error}}</li>
			</ul>
		@endforeach
	@endif

</form>

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
	<script src="{{asset('js/opcion/opcionMultiple.js')}}"> </script>
@endsection