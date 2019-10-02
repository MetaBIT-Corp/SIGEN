@extends("../layouts.plantilla")

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

<?php $contador=1; ?> <!-- Contador para índice de Opciones -->

<h3 class="mt-2 mb-5"><b>Pregunta</b>: <?php echo $pregunta->pregunta ?></h3>

<table class="table table-hover" style="text-align: center;">
	<thead>
		<tr><th colspan="2" style="text-align: right;color: rgb(100,180,10);">Pregunta de Verdadero/Falso</th></tr>
		<tr><th colspan="2" style="text-align: left; font-size: 1.3em;">Opciones de Pregunta</th></tr>
		<tr class="table-primary">
			<th scope="col">Opción</th>
			<th scope="col">Correcta</th>
		</tr>
	</thead>
	<tbody>

		<form form action="{{ route('actualizar-opcion',$pregunta->id)}}" method="POST">

			<div class="form-group" style="display:none;">
				<label class="col-form-label" for="tipo_opcion">Tipo Opcion</label>
				<input type="text" class="form-control" name="tipo_opcion" placeholder="ID de Pregunta" id="tipo_opcion" value="{{$tipo_opcion}}">
				<label class="col-form-label" for="pregunta_id">Pregunta_ID:</label>
				<input type="text" class="form-control" name="pregunta_id" placeholder="ID de Pregunta" id="pregunta_id" value="{{$pregunta->id}}">
			</div>

			<tr>
				<td>
					{{$opciones[0]->opcion}}
				</td>
				<td>
					<?php if ($opciones[0]->correcta==1): ?>
						<input type="radio" name="correcta" id="" value="0" checked="">
					<?php else: ?>
						<input type="radio" name="correcta" id="" value="0">
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td>
					{{$opciones[1]->opcion}}
				</td>
				<td>
					<?php if ($opciones[1]->correcta==1): ?>
						<input type="radio" name="correcta" id="" value="1" checked="">
					<?php else: ?>
						<input type="radio" name="correcta" id="" value="1">
					<?php endif; ?>
				</td>
			</tr>

			<tr style="text-align: right;" id="trBtn">
				<td></td>
				<td>
					{{ csrf_field() }}
					<button type="submit" class="btn btn-info">Guardar Opciones</button>
				</td>
			</tr>

		</form>

	</tbody>
</table>

@endsection