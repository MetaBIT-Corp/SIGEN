@php
	namespace App\Http\Controllers;
	use App\Clave_Area;
	use App\Grupo_Emparejamiento;
@endphp

<div class="modal" id="areasEncuestaModal">
	<div class="modal-dialog" role="document" style="max-width: 60% !important;">
		
		<div class="modal-content">

			<div class="modal-header border-bottom-0">

				<h3 class="modal-title"><b>Asignar Áreas a Encuesta</b></h3>
				
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>

			<div class="modal-body p-0">

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
										<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarModal" data-id-area="{{$id_clave_area->id}}">
										&nbsp;&nbsp;&nbsp;&nbsp;Remover&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</button>
									</td>
								@else

									@php
										$preguntas_area = Grupo_Emparejamiento::where("area_id",$area->id)->count();
									@endphp

									@if($preguntas_area>0)
										<td class="col-sm-3 text-center">
											<button type="button" class="btn btn-info btn-asignacion" data-id-encuesta="{{$encuesta->id}}" data-id-clave="{{$clave->id}}" data-id-area="{{$area->id}}" data-titulo="{{$area->titulo}}" data-preguntas-area="{{$preguntas_area}}" data-url="{{ route('asignar-area-encuesta',$encuesta->id) }}" data-token-a="{{ csrf_token() }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Asignar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
										</td>
									@else
										<td class="col-sm-3 text-center">
											<button type="button" class="btn btn-secondary" disabled="">&nbsp;No Asignable&nbsp;</button>
										</td>
									@endif

								@endif
							</tr>
						@empty
							<tr><td><h3>No se han definido áreas para el Docente.</h3></td></tr>
						@endforelse

						<tr class="d-flex" id="trBtn">
							<td class="col-sm-9" colspan="3">
								<p>* Áreas sin Preguntas no son asignables.</p>
							</td>
							<td class="col-sm-3 text-center">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cerrar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</button>
							</td>
						</tr>

					</tbody>

				</table>


			</div>

		</div>
	</div>
</div>

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