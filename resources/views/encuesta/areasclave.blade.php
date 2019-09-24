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
										<button type="button" class="btn btn-danger">&nbsp;&nbsp;&nbsp;&nbsp;Remover&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
									</td>
								@else

									@php
										$preguntas_area = Grupo_Emparejamiento::where("area_id",$area->id)->count();
									@endphp
									<td class="col-sm-3 text-center">
										<button type="button" class="btn btn-info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Asignar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
									</td>

								@endif
							</tr>
						@empty
							<tr><td><h3>No se han definido áreas para el Docente.</h3></td></tr>
						@endforelse

						<tr class="d-flex" id="trBtn">
							<td class="col-sm-1"></td>
							<td class="col-sm-5"></td>
							<td class="col-sm-3"></td>
							<td class="col-sm-3 text-center">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cerrar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
							</td>
						</tr>

					</tbody>

				</table>


			</div>

		</div>
	</div>
</div>