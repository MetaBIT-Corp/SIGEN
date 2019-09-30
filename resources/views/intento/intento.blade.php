@extends("../layouts.plantilla")

@section("head")
    @php
        {{
            $valores=$paginacion->items();
        }}
    @endphp
@endsection

@section("body")

    
    @section("ol_breadcrumb")
        <a href="#">
            Evaluacion \ Parcial I
        </a>
    @endsection

    @section("main")

        <!--Card que contiene a cada pregunta-->
        <!--Se agrego la etiqueta form para persistencia-->

        <div id="carta" class="">
            <div class="carta-info">

                <div class="carta-div-float">
                    <p class="p-carta">Inicio de Intento:</p>
                    <input type="text" class="form-control-plaintext in-carta" id="fecha-inicio-intento" name="fecha-inicio-intento" value="{{$intento->fecha_inicio_intento}}" disabled>
                </div>

                <div class="carta-div-float">
                    <p class="p-carta">Final de Intento:</p>
                    <input type="text" class="form-control-plaintext in-carta" id="fecha-final-intento" name="fecha-final-intento" disabled>
                </div>

                <div class="carta-div-float">
                    <p class="p-carta">Duración de Intento:</p>
                    <input type="text" class="form-control-plaintext in-carta" id="duracion-intento" name="duracion-intento" value="{{$evaluacion->duracion}}" disabled>
                </div>

                <div class="carta-div-float">
                    <p class="p-carta">Tiempo Restante:</p>
                    <span class="in-carta" id="contador"></span>
                </div>
            
            </div>

            <?php $aux=1; ?>

            <div id="paginador-pregunta">

                <p class="p-carta">Preguntas:</p><br>

                @for($i=0;$i<$paginacion->total();$i++)
                    @if($i % $paginacion->perPage() == 0)
                        <a href="/intento/prueba/{{$clave_de_intento->turno_id}}?page={{$aux}}#pregunta{{$i+1}}" class="a-pregunta">
                            <div class="a-div">
                                {{$i+1}}
                                <?php $aux++; ?>
                            </div>
                        </a>
                    @else
                        <a href="/intento/prueba/{{$clave_de_intento->turno_id}}?page={{$aux-1}}#pregunta{{$i+1}}" class="a-pregunta">
                            <div class="a-div">
                                {{$i+1}}
                                <?php $aux-1 ?>
                            </div>
                        </a>
                    @endif
                @endfor
            </div>
            <div class="carta-info">

                <div class="carta-div-float">
                    <input type="button" onclick="capturar_data(2)" class="page-link bg-danger text-white btn-md" value="Terminar"/>
                </div>
            </div>

        </div>

        <form id='quiz_form'>
            <input type="hidden" id="intento_id" value="{{ $intento->id }}">
            <div class='card'>
                <div class='card-body'>

                    <!--Aqui iran las preguntas-->

                    @for($i=0; $i < count($valores); $i++)

                    

                        @if($valores[$i]['tipo_item']!=3)

                            <div class="card bg-light mt-2 mb-2">     <!-- Cada Carta -->

                                <span class="no-viewable" id="pregunta{{(($paginacion->perPage())*($paginacion->currentPage())-($paginacion->perPage()-$i))+1}}"></span>
                                
                                <div class="card-header" name="{{ $valores[$i]['pregunta']->id }}"> <!-- Header de cada Carta -->
                                    <strong><h5>{{(($paginacion->perPage())*($paginacion->currentPage())-($paginacion->perPage()-$i))+1}}.&nbsp{{ $valores[$i]['pregunta']->pregunta }}</h5></strong> 
                                </div>

                                <!--Respuesta Corta-->
                                @if($valores[$i]['tipo_item']==4)                                    
                                    
                                    <input aria-describedby="emailHelp" name="pregunta_{{ $valores[$i]['pregunta']->id }}" class="form-control" id="exampleInputEmail1" placeholder="Ingrese respuesta" type="text" @if($valores[$i]['pregunta']->texto != "") value="{{ $valores[$i]['pregunta']->texto }}" @endif>
                                    </input>

                                @else

                                    <div class="card-body">     <!-- Body para Respuestas de Opción Múltiple-->
                                        <!--Preguntas de opcion multiple-->
                                        @for($j=0;$j< count($valores[$i]['opciones']); $j++)
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" id="{{ $valores[$i]['opciones'][$j]->id}}" name="pregunta_{{ $valores[$i]['pregunta']->id }}" type="radio" value="opcion_{{ $valores[$i]['opciones'][$j]->id }}" @if($valores[$i]['opciones'][$j]->seleccionada) checked @endif>
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
                            <div class="card bg-light mt-2">    <!-- Cartas para cada Grupo Emparejamiento -->
                                <div class="card-header">
                                    {{(($paginacion->perPage())*($paginacion->currentPage())-($paginacion->perPage()-$i))+1}}. {{ $valores[$i]['descripcion_gpo'] }}
                                </div>
                                <div class="card-body">        <!-- Body para cada Grupo de Emparejamiento -->

                                    <span class="no-viewable" id="pregunta{{(($paginacion->perPage())*($paginacion->currentPage())-($paginacion->perPage()-$i))+1}}"></span>
                                    
                                    <table class="table">
                                        <tbody>
                                            @for($r=0; $r< count($valores[$i]['preguntas']); $r++)
                                                <tr>
                                                    <td>
                                                        {{ $valores[$i]['preguntas'][$r]->pregunta }}
                                                    </td>
                                                    <td>
                                                        <select class="custom-select col-12" id="{{ $valores[$i]['preguntas'][$r]->id }}" name="pregunta_{{ $valores[$i]['preguntas'][$r]->id }}">
                                                            <option value="opcion_0" @if($valores[$i]['preguntas'][$r]->seleccionada == "opcion_0") selected @endif>
                                                                Seleccione
                                                            </option>
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
                                                            
                                                            @for($m=0; $m< count($valores[$i]['preguntas']); $m++)
                                                                <option value="opcion_{{ $valores[$i]['preguntas'][$nums[$m]]->opciones[0]->id }}" @if($valores[$i]['preguntas'][$r]->seleccionada == "opcion_".$valores[$i]['preguntas'][$nums[$m]]->opciones[0]->id) selected @endif>
                                                                    {{ $valores[$i]['preguntas'][$nums[$m]]->opciones[0]->opcion }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        @endif
                    @endfor
                </div>
                <div class="card-footer">
                    <!--Botones de control para paginacion-->
                    <div class="offset-9">{{ $paginacion->links('vendor.pagination.simple-bootstrap-4') }}</div>
                </div>
            </div>
        </form>

    @endsection

@endsection

@section("js")

    <script type="text/javascript" src="{{asset('js/intento/main.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/intento/timeControl.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/intento/moment.min.js')}}"></script>

@endsection