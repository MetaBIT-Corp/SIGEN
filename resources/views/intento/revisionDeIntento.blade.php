@extends("../layouts.plantilla")
@section("head")
@php
    {{
    	if($paginacion!= null){
    	$valores=$paginacion->items();
    }
        
    }}
@endphp

@endsection

@section("body")
@section("ol_breadcrumb")
<a href="#" >
    Evaluacion \ Parcial I
</a>
@endsection
@section("main")

<div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">
      <!-- DataTables Example -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fas fa-table"></i>
          Revisión</div>
        <div class="card-body">
 		  @if($estudiante && $intento && $respuestas && $paginacion && $evaluacion)
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Carnet</th>
                  <th >Estudiante</th>
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
         

          <!--Card que contiene a cada pregunta-->
		<!--Se agrego la etiqueta form para persistencia-->
		<form id="quiz_form">
		    <div class="card">
		    <div class="card-body">
		        <!--Aqui iran las preguntas-->
		        @for($i=0;$i<count($valores);$i++)

		        @if($valores[$i]['tipo_item']!=3)
		        <div class="card bg-light mt-2 mb-2">
		            <div class="card-header" name='{{ $valores[$i]['pregunta']->id }}'>
		                <strong><h4>{{ $i+1}}.&nbsp&nbsp{{ $valores[$i]['pregunta']->pregunta }}</h4></strong> 
		            </div>
		        <!--Respuesta Corta-->
		        @if($valores[$i]['tipo_item']==4)
					
		            <input disabled="true" aria-describedby="emailHelp" name="pregunta_{{ $valores[$i]['pregunta']->id }}" class="form-control" id="exampleInputEmail1" placeholder="Ingrese respuesta" type="text" 
		            @if($valores[$i]['pregunta']->texto != "") 
		            	value="{{ $valores[$i]['pregunta']->texto }}" 
		            @endif
					@foreach($respuestas as $respuesta)
						@if($respuesta->id_pregunta == $valores[$i]['pregunta']->id)
							@if($respuesta->id_opcion==$valores[$i]['opciones'][$j]->id)
								@if($valores[$i]['opciones'][$j]->opcion == $respuesta->texto_respuesta)
									style="background-color: #9FF189;"
								@else
									style="background-color: #F37F7F;"
								@endif
							@endif
						@endif
					@endforeach
		            >
		            </input>
		            </div>
		            

		        @else
		       
		        	
			        <div class="card-body">
			            <!--Preguntas de opcion multiple-->
			            @for($j=0;$j<count($valores[$i]['opciones']);$j++)
			                <div class="custom-control custom-radio" 
							@foreach($respuestas as $respuesta)
								@if($respuesta->id_pregunta == $valores[$i]['pregunta']->id)
									@if($respuesta->id_opcion==$valores[$i]['opciones'][$j]->id)
										@if($valores[$i]['opciones'][$j]->correcta)
											style="background-color: #9FF189;"
										@else
											style="background-color: #F37F7F;"
										@endif
									@endif
								@endif
							@endforeach
			                >
			                    <input  disabled="true" class="custom-control-input" id="{{ $valores[$i]['opciones'][$j]->id}}" name="pregunta_{{ $valores[$i]['pregunta']->id }}" type="radio" value="opcion_{{ $valores[$i]['opciones'][$j]->id }}" 
			                    @if($valores[$i]['opciones'][$j]->seleccionada) checked 
			                    @endif
			                    @foreach($respuestas as $respuesta)
									@if($respuesta->id_pregunta == $valores[$i]['pregunta']->id)
										@if($respuesta->id_opcion==$valores[$i]['opciones'][$j]->id)
										checked
										@endif
									@endif
								@endforeach
			                    >
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

		        <!--Preguntas de Emparejamiento-->
		        @if($valores[$i]['tipo_item']==3)
		        <div class="card bg-light mt-2">
		            <div class="card-header">
		                {{ $valores[$i]['descripcion_gpo'] }}
		            </div>
		            <div class="card-body">
		                <table class="table">
		                    <tbody>
		                        @for($r=0;$r<count($valores[$i]['preguntas']);$r++)
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
		                            @foreach($respuestas as $respuesta)
										@if($respuesta->id_pregunta == $valores[$i]['preguntas'][$r]->id)
											@for($p=0;$p<count($valores[$i]['preguntas']);$p++)
											@if($respuesta->id_opcion== $valores[$i]['preguntas'][$nums[$p]]->opciones[0]->id 
												&& $respuesta->id_pregunta  == $valores[$i]['preguntas'][$r]->id)
												@if($valores[$i]['preguntas'][$nums[$p]]->opciones[0]->correcta)
													style="background-color: #9FF189;"
												@else
													style="background-color: #F37F7F;"
												@endif
											@endif
											@endfor
										@endif
									@endforeach 

		                            	>
		                                <option value="opcion_0" @if($valores[$i]['preguntas'][$r]->seleccionada == "opcion_0") selected @endif>
		                                    Seleccione
		                                </option>
		                                
		                                @for($m=0;$m<count($valores[$i]['preguntas']);$m++)

		                                <option  se value="opcion_{{ $valores[$i]['preguntas'][$nums[$m]]->opciones[0]->id }}" @if($valores[$i]['preguntas'][$r]->seleccionada == "opcion_".$valores[$i]['preguntas'][$nums[$m]]->opciones[0]->id) selected @endif
		                                	@foreach($respuestas as $respuesta)
										@if($respuesta->id_pregunta == $valores[$i]['preguntas'][$r]->id)
											
											@if($respuesta->id_opcion== $valores[$i]['preguntas'][$nums[$m]]->opciones[0]->id )
												@if($valores[$i]['preguntas'][$nums[$m]]->opciones[0]->correcta)
													selected="true"
												@endif
											@endif
											
										@endif
									@endforeach
		                                	>
		                                    {{ $valores[$i]['preguntas'][$nums[$m]]->opciones[0]->opcion }}
		                                </option>

		                                @endfor
		                            </select>
		                        </tr>
		                        @endfor
		                        <tr>
		                    </tbody>
		                </table>
		            </div>
		        </div>
		        @endif
		        @endfor

		    </div>
		    <div class="card-footer">
		        <!--Botones de control para paginacion-->
		        <div class="offset-10">
		        	
		            <a class="page-link bg-danger text-white" href="{{ URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->id_carga]) }}">Finalizar Revisión</a>
		            
		        </div>
		    </div>
		</div>
		</form>
        </div>
         @endif
        <div class="card-footer small text-muted">
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->

<select disabled="true" id="total" class="custom-select col-12" >
        <option value="" >
            Seleccione 1
        </option>
        <option value="" selected="true">
            Seleccione 2
        </option>
        <option value="">
            Seleccione 3
        </option>
</select>
<script>
	c = document.getElementById('total');
 	c.style.color = green;
</script>

@endsection
@endsection

@section("js")

	<script>
		function deshabilitaRetroceso(){
		    window.location.hash="no-back-button";
		    window.location.hash="Again-No-back-button" //chrome
		    window.onhashchange=function(){window.location.hash="no-back-button";}
		}
	</script>
@endsection
