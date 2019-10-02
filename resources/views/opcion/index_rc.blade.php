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
		<a href="{{ URL::signedRoute('getPreguntas', ['id' => $area->id]) }}">
			Preguntas
		</a>
	</li>
	<li class="breadcrumb-item">
	    Opciones
	</li>

@endsection

@section('main')

<?php $contador=1; ?>

<h3 class="mt-2 mb-5"><b>Pregunta</b>: <?php echo $pregunta->pregunta ?></h3>

<table class="table table-hover" style="text-align: center;">
	<thead>
		<tr><th colspan="3" style="text-align: right;color: rgb(100,180,10);">Pregunta de Respuesta Corta</th></tr>
		<tr><th colspan="3" style="text-align: left; font-size: 1.3em;">Opciones de Pregunta</th></tr>
		<tr class="table-primary">
			<th scope="col">N°</th>
			<th scope="col">Opción</th>
			<th scope="col">Opciones</th>
		</tr>
	</thead>
	<tbody>

		<?php foreach ($opciones as $opcion): ?>
			<tr>
				<th scope="row"><?php echo $contador; ?></th>
				<td  style="text-align: center;">{{$opcion->opcion}}</td>
				<td>
					
					<a href="#" class="mr-2 btn-editar btn" id="btn_editar" data-id="{{$opcion->id}}" data-opcion="{{$opcion->opcion}}" data-tipo="{{$tipo_opcion}}" data-toggle="modal" data-target="#editModal"><span class="icon-edit"></span></a>
					
					<a href="" class="ml-2 btn-eliminar btn" id="btn_eliminar" data-id="{{$opcion->id}}" data-toggle="modal" data-target="#deleteModal"><span class="icon-delete"></span></a>

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

					<div class="form-group">
						<label class="col-form-label" for="opcion">Opción:</label>
						<input type="text" class="form-control" name="opcion" placeholder="Inserte el texto de la Opción" id="opcion">
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
						<label class="col-form-label" for="id">Opcion ID:</label>
						<input type="text" class="form-control" name="id" placeholder="ID de Opcion" id="id">
						<label class="col-form-label" for="tipo_opcion">Tipo Opcion</label>
						<input type="text" class="form-control" name="tipo_opcion" placeholder="Tipo de Opción" id="tipo_opcion">

					</div>

					<div class="form-group">
						<label class="col-form-label" for="opcion">Opción:</label>
						<input type="text" class="form-control" required="required" name="opcion" placeholder="Inserte el texto de la Opción" id="opcion">
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