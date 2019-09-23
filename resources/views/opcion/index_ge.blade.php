@extends("../layouts.plantilla")

@section("ol_breadcrumb")
<li class="breadcrumb-item">
    <a href="{{ route('materias') }}">
        Materias
    </a>
</li>
<li class="breadcrumb-item">
	<a href="#">
        Áreas
    </a>
</li>
<li class="breadcrumb-item">
	<a href="#">
        Preguntas
    </a>
</li>
<li class="breadcrumb-item">
    Opciones
</li>



@endsection

@section('main')


<h3 class="mt-2 mb-5"><b>Pregunta</b>: <?php echo $pregunta->pregunta ?></h3>

<table class="table table-hover" style="text-align: left;">
	<thead>
		<tr><th colspan="2" style="text-align: right;color: rgb(100,180,10);">Pregunta de Emparejamiento</th></tr>
		<tr class="table-primary">
			<th scope="col">Respuesta de Pregunta</th>
			<th></th>
		</tr>
	</thead>
	<tbody>

		<form form action="{{ route('actualizar-opcion',$pregunta->id)}}" method="POST">

			<div class="form-group" style="display:none">
				<label class="col-form-label" for="tipo_opcion">Tipo Opcion</label>
				<input type="text" class="form-control" name="tipo_opcion" placeholder="ID de Pregunta" id="tipo_opcion" value="{{$tipo_opcion}}">
				<label class="col-form-label" for="pregunta_id">Pregunta ID:</label>
				<input type="text" class="form-control" name="pregunta_id" placeholder="ID de Pregunta" id="pregunta_id" value="{{$pregunta->id}}">
				<label class="col-form-label" for="id">Opcion ID:</label>
				<input type="text" class="form-control" name="id" value="{{$opciones[0]->id}}" id="id">
			</div>

			<tr id="trForm">
				<td style="width:90%; ">
					<input type="text" class="form-control" name="opcion" value="{{$opciones[0]->opcion}}" id="opcion" disabled="">
				</td>
				<td style="padding-top: 20px; text-align: center;" >
					<i class="fas fa-pencil-alt" style="color: rgb(20,130,160);" onclick="editar()"></i>
				</td>
			</tr>

			<tr style="text-align: right;" id="trBtn">
				<td colspan="2">
					{{ csrf_field() }}
					<button type="submit" class="btn btn-info" id="btnEnvio" disabled="">Guardar Respuesta</button>
				</td>
			</tr>

		</form>

	</tbody>
</table>

@endsection

@section('js')
	<script>
		function editar(){
			
			if (document.getElementById("opcion").disabled==true) {
				document.getElementById("opcion").disabled = false
				document.getElementById("btnEnvio").disabled = false
			} else {
				document.getElementById("opcion").disabled = true
				document.getElementById("btnEnvio").disabled = true
			}
		}
    </script>
@endsection