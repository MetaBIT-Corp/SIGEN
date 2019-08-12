@extends("turno.base")

@section("nombre_vista") Editar turno @endsection
   
@section("titulo_card") Editar turno @endsection

@section("formulario")
<form action="/evaluacion/{{ $turno->evaluacion_id }}/turnos/{{ $turno->id }}" method="post">
  @csrf
  @method('PATCH')
  <div class="form-group">
    <label for="exampleInputEmail1">Fecha + Hora de inicio:</label>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input id="datetimepicker1input" type="text" name="fecha_inicio_turno" class="form-control datetimepicker-input" data-target="#datetimepicker1" placeholder="dd/mm/yyyy hh:mm tt" value="{{ old('fecha_inicio_turno',$turno->fecha_inicio_turno) }}" data-evaluacion_id="{{ $turno->evaluacion_id }}"/>
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
    <label for="exampleInputEmail1">Fecha + Hora de fin:</label>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        <input id="datetimepicker2input" type="text" name="fecha_final_turno" class="form-control datetimepicker-input" data-target="#datetimepicker2" placeholder="dd/mm/yyyy hh:mm tt" value="{{ old('fecha_final_turno',$turno->fecha_final_turno) }}" readonly/>
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Contraseña:</label>
    <input type="password" name="contraseña" class="form-control margen_form" style="margin-left:15px" id="exampleInputPassword1" placeholder="Contraseña" value="{{ old('contraseña',"") }}">
    <small class="form-text text-muted" style="margin-left:15px">La contraseña será la misma sino se digita una nueva.</small>
  </div>
  <div class="form-check">
    <input type="checkbox" name="visibilidad" class="form-check-input" @if($turno->visibilidad) checked @endif>
    <label class="form-check-label" for="exampleCheck1">Visible</label>
    <small class="form-text text-muted">Al marcarlo el turno será visible para los estudiantes.</small>
  </div><br>
  <div class="row">
     <div class="form-group">
         <button type="submit" class="btn btn-primary">Guardar</button>                 
     </div>
     <div class="form-group offset-1">
         <button type="button" class="btn btn-secondary" onclick="location.href='/evaluacion/{{ $turno->evaluacion_id }}/turnos'">Cancelar</button>                           
     </div>
  </div>
</form>
@endsection
      