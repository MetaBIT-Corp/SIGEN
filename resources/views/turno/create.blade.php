@extends("turno.base")

@section("nombre_vista") Crear turno @endsection
   
@section("titulo_card") Crear turno @endsection

@section("formulario")
<form action="/evaluacion/{{ $id }}/turnos" method="post">
  @csrf
  <div class="form-group">
    <label for="exampleInputEmail1">Fecha + Hora de inicio:</label>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input id="datetimepicker1input" type="text" name="fecha_inicio_turno" class="form-control datetimepicker-input" data-target="#datetimepicker1" placeholder="dd/mm/yyyy hh:mm tt" value="{{ old('fecha_inicio_turno') }}" data-evaluacion_id="{{ $id }}"/>
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
                        <input id="datetimepicker2input" type="text" name="fecha_final_turno" class="form-control datetimepicker-input" data-target="#datetimepicker2" placeholder="dd/mm/yyyy hh:mm tt" value="{{ old('fecha_final_turno') }}" readonly/>
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
    <input type="password" name="contraseña" class="form-control" style="margin-left:15px" id="exampleInputPassword1" placeholder="Contraseña" value="{{ old('contraseña') }}">
  </div>
  <div class="form-check">
    <input type="checkbox" name="visibilidad" class="form-check-input">
    <label class="form-check-label" for="exampleCheck1">Visible</label>
    <small class="form-text text-muted">Al marcarlo el turno será visible para los estudiantes.</small>
  </div><br>
  <div class="row">
     <div class="form-group">
         <button type="submit" class="btn btn-primary">Guardar</button>                 
     </div>
     <div class="form-group offset-1">
         <button type="button" class="btn btn-secondary">Cancelar</button>                           
     </div>
  </div>
</form>
@endsection
      