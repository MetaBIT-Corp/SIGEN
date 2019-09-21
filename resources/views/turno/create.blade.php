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
                        <input id="datetimepicker1input" type="text" name="fecha_inicio_turno" class="form-control datetimepicker-input col-md-10" data-target="#datetimepicker1" placeholder="dd/mm/yyyy hh:mm A" value="{{ old('fecha_inicio_turno') }}" data-evaluacion_id="{{ $id }}"/>
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
            <div class="col-md-10">
                <div class="form-group">
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        <input id="datetimepicker2input" type="text" name="fecha_final_turno" class="form-control datetimepicker-input col-md-8" data-target="#datetimepicker2" placeholder="dd/mm/yyyy hh:mm A" value="{{ old('fecha_final_turno') }}"/>
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
  </div><br>
  <div class="row">
     <div class="form-group">
         <button type="submit" class="btn btn-primary">Guardar</button>                 
     </div>
     <div class="form-group offset-1">
         <a class="btn btn-secondary" href="{{ URL::signedRoute('listado_turnos', ['id' => $id]) }}">Cancelar</a>                           
     </div>
  </div>
</form>
@endsection
     
@section("extra_js")
    <script type="text/javascript" src="{{ asset('js/turno/main.js') }}"></script>
@endsection
      