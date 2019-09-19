@php
	namespace App\Http\Controllers;
	use App\Clave_Area;
	use App\Grupo_Emparejamiento;
@endphp

<!-- Modal para la visualización de las Áreas predefinidas en la Materia-->

<div class="modal" id="areasModal">
	<div class="modal-dialog" role="document" style="max-width: 60% !important;">
		
		<div class="modal-content">				
			
			<div class="modal-header border-bottom-0">

				<h5 class="modal-title">Asignar Áreas a Turno</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>

			<div class="modal-body p-0">

				<div class="form-group" style="display:none; border: solid gold;">

					<label class="col-form-label" for="turno_id">Turno ID:</label>
					<input type="text" class="form-control" name="turno_id" placeholder="ID de Pregunta" id="turno_id">
					<label class="col-form-label" for="clave_id">Clave ID:</label>
					<input type="text" class="form-control" name="clave_id" placeholder="ID de Pregunta" id="clave_id">
					<label class="col-form-label" for="peso_turno">Peso Asignado al Turno:</label>
					<input type="text" class="form-control" name="peso_turno" placeholder="ID de Pregunta" id="peso_turno">
				</div>

				<table class="table table-hover border-top-0">

					<thead class="text-left ">
						<tr class="d-flex border-top-0">
							<th class="col-1 border-bottom-0">N°</th>
							<th class="col-5 border-bottom-0">Área</th>
							<th class="col-3 border-bottom-0">Tipo</th>
							<th class="col-3 border-bottom-0">Acción</th>
						</tr>
					</thead>

					<tbody>

						@forelse ($areas as $area)
							<tr class="d-flex">
								<th class="col-sm-1 text-center">
									{{ $loop->iteration }}
								</th>

								<td class="col-sm-5">
									{{ $area->titulo }}
								</td>
									
								@switch( $area->tipo_item_id )
									@case(1)
										<td class="col-sm-3">
											Opción Múltiple
										</td>
									@break
									@case(2)
										<td class="col-sm-3">
											Verdadero/Falso
										</td>
									@break
									@case(3)
										<td class="col-sm-3">
											Emparejamiento
										</td>
									@break
									@case(4)
										<td class="col-sm-3">
											Respuesta Corta
										</td>
									@break
								@endswitch

								@if(in_array($area->id, $id_areas))

									@php
										$id_clave_area = Clave_Area::where("area_id",$area->id)->where("clave_id",$clave->id)->first();
									@endphp

									<td class="col-sm-3 text-center">
										<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarModal" data-id-area="{{$id_clave_area->id}}" onclick="$('#eliminarModal').modal();">Remover</button>
									</td>
								@else

									@php
										$preguntas_area = Grupo_Emparejamiento::where("area_id",$area->id)->count();
									@endphp
									<td class="col-sm-3 text-center">
										<button type="button" class="btn btn-info" data-id-turno="{{$turno->id}}" data-id-clave="{{$claves[0]->id}}" data-id-area="{{$area->id}}" data-titulo="{{$area->titulo}}" data-preguntas-area="{{$preguntas_area}}" data-peso-turno="{{$peso_turno}}" data-toggle="modal" data-target="#asignarModal" data-dismiss="modal" onclick="$('#asignarModal').modal();">&nbsp;Asignar&nbsp;</button>
									</td>
								@endif
							</tr>
						@empty
							<tr><td><h3>No se han definido áreas para la materia.</h3></td></tr>
						@endforelse

						<tr class="d-flex" id="trBtn">
							<td class="col-sm-1"></td>
							<td class="col-sm-5"></td>
							<td class="col-sm-3"></td>
							<td class="col-sm-3 text-center">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">&nbsp;&nbsp;Cerrar&nbsp;&nbsp;</button>
							</td>
						</tr>

					</tbody>

				</table>

			</div>

		</div>

	</div>
</div>

<!-- Fin de Modal para Visualización de Áreas -->

<!-- Modal para la asignación de Áreas predefinidas al Turno-->

<div class="modal" id="asignarModal">
	<div class="modal-dialog" role="document" style="max-width: 50% !important;">
			
		<div class="modal-content">
				
			<div class="modal-header">

				<h5 class="modal-title"><b>Configuración de Área</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>

			<form action="{{ route('asignar-area-clave',$turno->id)}}" method="POST">

			<div class="modal-body">

				<div class="form-group">

					<div class="form-group" style="display: none; border: solid gold;">
						<label class="col-form-label" for="turno_id">Turno ID:</label>
						<input type="text" class="form-control" name="turno_id" placeholder="ID de Pregunta" id="turno_id">
						<label class="col-form-label" for="clave_id">Clave ID:</label>
						<input type="text" class="form-control" name="clave_id" placeholder="ID de Pregunta" id="clave_id">
						<label class="col-form-label" for="area_id">Area ID:</label>
						<input type="text" class="form-control" name="area_id" placeholder="ID de Pregunta" id="area_id">
						<label class="col-form-label" for="preguntas_area">Preguntas por Área:</label>
						<input type="text" class="form-control" name="preguntas_area" placeholder="Preguntas por Area" id="preguntas_area">
						<label class="col-form-label" for="titulo">T&iacute;tulo del &Aacute;rea:</label>
						<input type="text" class="form-control" name="titulo" id="titulo" readonly="">
						<label class="col-form-label" for="cantidad_preguntas">Cantidad de Preguntas disponibles en el &Aacute;rea:</label>
						<input type="text" class="form-control" name="cantidad_preguntas" id="cantidad_preguntas" readonly="">
						<label class="col-form-label" for="peso_turno">Peso Asignado al Turno:</label>
						<input type="text" class="form-control" name="peso_turno" placeholder="Peso de Turno" id="peso_turno" readonly="">
						<label class="col-form-label" for="peso_restante">Peso Restante de Turno:</label>
						<input type="text" class="form-control" name="peso_restante" placeholder="Peso restante" id="peso_restante" readonly="">
					</div>

					<div class="form-group">
						<b>Titulo del Área:</b> <span id="titulo_s" name="titulo_s"></span><br>
						<b>Cantidad de Preguntas del Área:</b> <span id="cantidad_preguntas_s" name="cantidad_preguntas_s"></span><br>
						<b>Peso Total Actual del Turno:</b> <span id="peso_turno_s" name="peso_turno_s"></span><br>
					</div>

					<hr>

					<p>Modo de asignación de Preguntas al Área:</p>
					<div class="form-check">
						<label class="form-check-label">
							<input type="radio" class="form-check-input" name="aleatorio" id="aleatorio-no" value="0" checked="" onchange="aleatorioNo()">
							Manual
						</label>
					</div>
					<div class="form-check">
						<label class="form-check-label">
							<input type="radio" class="form-check-input" name="aleatorio" id="aleatorio-si" value="1" onchange="aleatorioSi()">
							Aleatorio
						</label>
					</div><br>

					<div class="form-group" id="divAleatorias">
						<label class="col-form-label" for="cantidad">Cantidad de Preguntas Aleatorias:</label>
						<span style="float: right;" id="cantidad_preguntas_s2"></span>
						<input type="text" class="form-control" name="cantidad" id="cantidad" value="0">
					</div>

					<div class="form-group">
						<label class="col-form-label" for="peso">Peso del Área:</label>
						<span style="float: right;" id="peso_restante_s"></span>
						<input type="text" class="form-control" name="peso" placeholder="Inserte Peso" id="peso">
					</div>

				</div>

			</div>

			<div class="modal-footer">

				{{ csrf_field() }}

				<button type="submit" class="btn btn-primary">Asignar Área</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				
			</div>

			</form>

		</div>
	</div>
</div>

<!-- Fin de Modal para la asignación de Áreas predefinidas al Turno-->

<!-- Modal para la remover las Áreas asignadas al Turno-->

<div class="modal" id="eliminarModal">
	<div class="modal-dialog" role="document" style="max-width: 50% !important;">
			
		<div class="modal-content">
				
			<div class="modal-header">

				<h5 class="modal-title">Remover Área de Turno</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>

			<div class="modal-body">

				<div class="form-group">
						
					<form action="{{ route('eliminar_clave_area')}}" method="POST">

						<div class="form-group" style="display:none;">
							<label class="col-form-label" for="id_clave_area">ClaveArea ID:</label>
							<input type="text" class="form-control" name="id_clave_area" placeholder="ID de Pregunta" id="id_clave_area">
						</div>

						<h5>¿Desea Remover el Área del Turno?</h5>

						<div class="modal-footer">

							{{ csrf_field() }}
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button type="submit" class="btn btn-danger">Eliminar</button>
								
						</div>
					</form>

				</div>
					
			</div>

		</div>
	</div>
</div>  