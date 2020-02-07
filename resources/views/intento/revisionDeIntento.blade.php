@extends("../layouts.plantilla")

@php
	use App\Opcion;
@endphp

@section("head")
	@php
	{{
		if($paginacion!= null){
			$valores=$paginacion->items();
		}
	}}
	@endphp
@endsection

@section("css")
   <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
@endsection

@section("body")

	@if(auth()->user()->IsStudent)	
		@section("ol_breadcrumb")
			<a href="#" >
				 Evaluacion \ {{$evaluacion->nombre_evaluacion }}
			</a>
		@endsection
	@else
		@section("ol_breadcrumb")
			<li class="breadcrumb-item">
				<a href=" {{ URL::signedRoute('listado_evaluacion', ['id'=>$evaluacion->carga_academica->id_carg_aca]) }}">
					Evaluaciones
				</a>
			</li>
			<li class="breadcrumb-item">
				<a href="{{ URL::signedRoute('estudiantes_en_evaluacion', ['evaluacion_id' => $evaluacion->id]) }}">
					Estudiantes
				</a>
			</li>
			<li class="breadcrumb-item">
				Revisión de Estudiante
			</li>
		@endsection
	@endif

	@section("main")
		<div id="wrapper">
			<div id="content-wrapper">
				<div class="container-fluid">

					<!-- Inicio DataTable -->
					<div class="card mb-3">
						
						<div class="card-header">
							<i class="fas fa-table"></i>
							Revisión
						</div>

						<div class="card-body">
							@if($estudiante && $intento && $respuestas && $paginacion && $evaluacion)
								
								<!-- Inicio Info Intento -->
								<div class="table-responsive">
									<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
										<thead>
											<tr>
												<th>Carnet</th>
												<th>Estudiante</th>
												<th>Numero de Intento</th>
												<th>Nota obtenida</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{{$estudiante->carnet}}</td>
												<td >{{$estudiante->nombre}}</td>
												<td>{{$intento->numero_intento}}</td>
												<td>{{$intento->nota_intento}}</td>    
											</tr>
										</tbody>
									</table>
								</div>
								<!-- Fin Info Intento -->
								@if($se_permite_revision && $evaluacion->revision==1 || $intento->revision_estudiante==1)
								<!-- Inicio Revisión Intento -->
								<form id="quiz_form">
									<div class="card">

										<div class="card-body">

											<!-- Inicio Items del Intento -->
											@for($i=0; $i < count($valores); $i++)
												
												@if($valores[$i]['tipo_item']!=3)
													<div class="card bg-light mt-2 mb-2">
														
														<div class="card-header" name="{{ $valores[$i]['pregunta']->id }}">
															<div class="row px-3 align-items-center">
																<div class="col-md-11">
																	<!-- Texto de Pregunta -->
																	<h4>
																		{{ $i+1}}.&nbsp&nbsp{{ $valores[$i]['pregunta']->pregunta }}
																	</h4>
																</div>
																
																@if(auth()->user()->IsTeacher)
																	
																	<div class="col-md-1">
																		<a class="btn btn-sm btn-info text-white" title="Editar Opciones"

																			style="cursor: pointer;" 

																			data-id-pregunta="{{$valores[$i]['pregunta']->id}}"
																			data-pregunta="{{$valores[$i]['pregunta']->pregunta}}"
																			data-tipo-pregunta="{{$valores[$i]['tipo_item']}}"

																			data-cantidad-opciones ="{{$valores[$i]['opciones']->count()}}"

																			@foreach($valores[$i]['opciones'] as $opcion)
																				data-id-opcion{{$loop->iteration}}="{{$opcion->id}}"
																				data-opcion{{$loop->iteration}}="{{$opcion->opcion}}"
																				data-correcta-opcion{{$loop->iteration}}="{{$opcion->correcta}}"
																			@endforeach

																			data-toggle="modal"
																			data-target="#edit-modal"
																		>
																			<span class="icon-edit"></span>
																		</a>
																	</div>

																@endif

															</div>
														</div>

														@if($valores[$i]['tipo_item']==4) <!-- Opciones de Respuesta Corta -->
															
															<input disabled="true" aria-describedby="emailHelp" name="pregunta_{{ $valores[$i]['pregunta']->id }}" class="form-control" id="exampleInputEmail1" placeholder="Ingrese respuesta" type="text" 

															@if($valores[$i]['pregunta']->texto != "")
																value="{{ $valores[$i]['pregunta']->texto }}"
															@endif

															@foreach($respuestas as $respuesta)
																@if($respuesta->id_pregunta == $valores[$i]['pregunta']->id)

																	<?php $contador_rc = 0; ?>

																	@foreach(($valores[$i]['opciones']) as $opcion)
																		@if(str_replace('%20',' ',strtolower($respuesta->texto_respuesta)) == strtolower($opcion->opcion))
																			<?php $contador_rc++ ; ?>
																		@else
																			<?php $contador_rc = $contador_rc ; ?>
																		@endif
																	@endforeach
																		@if($contador_rc>0)
																			style="background-color: #d4edda;"
																		@else
																			style="background-color: #f8d7da;"
																		@endif

																	
																	
																@endif
															@endforeach
															></input>

														@else <!-- Opciones de Opción Múltiple -->

															<div class="card-body">
																@for($j=0;$j < count($valores[$i]['opciones']);$j++)
																	<div class="custom-control custom-radio"

																	@foreach($respuestas as $respuesta)
																		@if($respuesta->id_pregunta == $valores[$i]['pregunta']->id)
																			@if($respuesta->id_opcion==$valores[$i]['opciones'][$j]->id)
																				@if($valores[$i]['opciones'][$j]->correcta)
																					style="background-color: #d4edda;"
																				@else
																					style="background-color: #f8d7da;"
																				@endif
																			@endif
																		@endif
																	@endforeach>



																		<input  disabled="true" class="custom-control-input" id="{{ $valores[$i]['opciones'][$j]->id}}" name="pregunta_{{ $valores[$i]['pregunta']->id }}" type="radio" value="opcion_{{ $valores[$i]['opciones'][$j]->id }}"

																		@if($valores[$i]['opciones'][$j]->seleccionada) checked
																		@endif
																		@foreach($respuestas as $respuesta)
																			@if($respuesta->id_pregunta == $valores[$i]['pregunta']->id)
																				@if($respuesta->id_opcion==$valores[$i]['opciones'][$j]->id)
																					checked
																				@endif
																			@endif
																		@endforeach>


																			<label class="custom-control-label" for="{{ $valores[$i]['opciones'][$j]->id}}">
																				<h5>{{ $valores[$i]['opciones'][$j]->opcion }}</h5>
																			</label>
																		</input>
																	</div>
																@endfor
															</div>
														@endif

													</div>
												@endif

												<!-- Preguntas de Emparejamiento-->
												@if($valores[$i]['tipo_item']==3)
													<div class="card bg-light mt-2">
														<div class="card-header">
															{{ $valores[$i]['descripcion_gpo'] }}
														</div>
														<div class="card-body">
															<table class="table">
																<tbody>
																	@for($r=0;$r< count($valores[$i]['preguntas']);$r++)
																		<tr>
																			<td>
																				{{ $valores[$i]['preguntas'][$r]->pregunta }}
																			</td>
																			
																			<td>
																				@php
																				{{
																					/*Funcionalidad que mezcla las opciones de un select,
																					para que no se muestren de manera ordenada segun respuesta
																					de cada pregunta
																					FUNCIONALIDAD CON DUDA
																					*/
																					
																					$nums=range(0,count($valores[$i]['preguntas'])-1);
																					shuffle($nums);
																				}}
																				@endphp

																				
																				<select disabled="true" class="custom-select col-12" id="{{ $valores[$i]['preguntas'][$r]->id }}" name="pregunta_{{ $valores[$i]['preguntas'][$r]->id }}"
																				//recorremos las respuestas

																				@foreach($respuestas as $respuesta)
																					@if($respuesta->id_pregunta == $valores[$i]['preguntas'][$r]->id)
																						@for($e=0;$e< count($valores[$i]['preguntas']);$e++)
																							@for($j=0; $j< count($valores[$i]['preguntas'][$e]->opciones); $j++)
																								@if($valores[$i]['preguntas'][$e]->opciones[$j]->id == $respuesta->id_opcion )
																									@if($valores[$i]['preguntas'][$e]->opciones[$j]->correcta && $valores[$i]['preguntas'][$e]->opciones[$j]->pregunta_id == $valores[$i]['preguntas'][$r]->id)
																										style="background-color: #d4edda;"
																									@else
																										style="background-color: #f8d7da;"
																									@endif
																								@endif
																							@endfor
																						@endfor
																					@endif
																				@endforeach>
																					<option value="opcion_0" @if($valores[$i]['preguntas'][$r]->seleccionada == "opcion_0") selected @endif>
																						Seleccione
																					</option>

																					@for($m=0; $m< count($valores[$i]['preguntas']); $m++)
																						<!--For para recorer opciones-->
																						@for($n=0; $n < count($valores[$i]['preguntas'][$m]->opciones); $n++)
																							<option value="opcion_{{ $valores[$i]['preguntas'][$m]->opciones[$n]->id }}" @if($valores[$i]['preguntas'][$r]->seleccionada == "opcion_".$valores[$i]['preguntas'][$m]->opciones[$n]->id) selected @endif>
																								{{ $valores[$i]['preguntas'][$m]->opciones[$n]->opcion }}
																							</option>
																						@endfor
																					@endfor
																				</select>
																			</td>
																			<td>

																			<a class="btn btn-sm btn-info text-white" title="Editar Opciones"

																			style="cursor: pointer;" 

																			data-id-pregunta="{{ $valores[$i]['preguntas'][$r]->id }}"
																			data-pregunta="{{ $valores[$i]['preguntas'][$r]->pregunta}}"
																			data-tipo-pregunta="3"


																			<?php
																				$opciones_gpo = Opcion::where('pregunta_id', $valores[$i]['preguntas'][$r]->id)->get();
																			?>

																			data-cantidad-opciones="{{$opciones_gpo->count()}}"

																			@foreach($opciones_gpo as $opcion)
																				data-id-opcion{{$loop->iteration}}="{{$opcion->id}}"
																				data-opcion{{$loop->iteration}}="{{$opcion->opcion}}"
																				data-correcta-opcion{{$loop->iteration}}="{{$opcion->correcta}}"
																			@endforeach


																			data-toggle="modal"
																			data-target="#edit-modal"
																			>
																				<span class="icon-edit"></span>
																			</a>

																				

																			</td>
																		</tr>
																	@endfor
																</tbody>
															</table>
														</div>
													</div>
												@endif
												<!-- Fin Preguntas de Emparejamiento-->

											@endfor
											<!-- Fin Items del Intento -->
										</div>

										<div class="card-footer">

											<div class="row">

												<div class="col-md-7"></div>

												<!--Botones de control para paginacion-->
												<div class="col-md-5 text-right">
													@if(auth()->user()->IsTeacher)
														<a class="btn btn-warning" href="{{ route('recalificar_evaluacion',$intento->id)}}">
															Recálculo de Nota
														</a>
													@endif
													<a class="btn btn-danger text-white" href="{{ URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->id_carga]) }}">
														Finalizar Revisión
													</a>
												</div>
												<!-- Fin Botones de control para paginacion-->

											</div>
										</div>

									</div>
								</form>
								<!-- Fin Revisión Intento -->
								@else
								<a class="btn btn-danger text-white" href="{{ URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->id_carga]) }}">
									Finalizar Revisión
								</a>
								@endif
							@endif
						</div>

					</div>
					<!-- Fin DataTable -->

				</div>
			</div>
		</div>

	@include('intento.edicion_opcion')


	@endsection

	@section("js")
		<script>
			function deshabilitaRetroceso(){
				window.location.hash="no-back-button";
				window.location.hash="Again-No-back-button" //chrome
				window.onhashchange=function(){window.location.hash="no-back-button";}
			}
		</script>
		<script src="{{asset('js/intento/edicion_opcion.js')}}"></script>
	@endsection
@endsection