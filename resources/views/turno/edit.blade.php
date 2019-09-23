@extends("turno.base")

@section("nombre_vista") Editar turno @endsection
   
@section("titulo_card") Editar turno @endsection

@section("formulario")
<form action="/evaluacion/{{ $turno->evaluacion_id }}/turnos/{{ $turno->id }}" method="post">
  @csrf
  @method('PATCH')
  <div class="form-group">
    
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                  <label for="exampleInputEmail1">Fecha + Hora de inicio:</label>
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input id="datetimepicker1input" type="text" name="fecha_inicio_turno" class="form-control datetimepicker-input" data-target="#datetimepicker1" placeholder="dd/mm/yyyy hh:mm tt" value="{{ old('fecha_inicio_turno',$turno->fecha_inicio_turno) }}" data-evaluacion_id="{{ $turno->evaluacion_id }}" @if($turno->iniciado) readonly @endif/>
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="form-group">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                  <label for="exampleInputEmail1">Fecha + Hora de fin:</label>
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        <input id="datetimepicker2input" type="text" name="fecha_final_turno" class="form-control datetimepicker-input" data-target="#datetimepicker2" placeholder="dd/mm/yyyy hh:mm tt" value="{{ old('fecha_final_turno',$turno->fecha_final_turno) }}"/>
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="form-group col-md-8">
    <label for="exampleInputPassword1">Contraseña:</label>
    <input type="password" name="contraseña" class="form-control" style="" id="exampleInputPassword1" placeholder="Contraseña" value="{{ old('contraseña',"") }}">
    <small class="form-text text-muted" style="">La contraseña será la misma sino se digita una nueva.</small>
  </div><br>
  <div class="row offset-1">
     <div class="form-group">
         <button type="submit" class="btn btn-primary">Guardar</button>                 
     </div>
     <div class="form-group offset-1">
        <a class="btn btn-secondary" href="{{ URL::signedRoute('listado_turnos', ['id' => $turno->evaluacion_id]) }}">Cancelar</a> 
     </div>
  </div>
  <input type="hidden" name="iniciado" value="{{ $turno->iniciado }}">
</form>
@endsection

@section('clave-area')
  @include('turno.asignarAreasClave')
@endsection
     
@section("extra_js")
  <script src="/js/clave/cargarPreguntas.js"> </script>
  <script src="/js/clave/operacionesClaveArea.js"> </script>
  <script type="text/javascript" src="{{ asset('js/turno/areaclave.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/turno/areaclavevalidacion.js') }}"></script>
   
   @if($turno->iniciado) 
       <script type="text/javascript" src="{{ asset('js/turno/edit/main.js') }}"></script>
   @else 
       <script type="text/javascript" src="{{ asset('js/turno/main.js') }}"></script>
   @endif
    
@endsection    