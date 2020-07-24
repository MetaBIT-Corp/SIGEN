@extends("ciclo.base")

@section("nombre_vista")
Crear Ciclo
@endsection


@section("titulo_card")
Crear Ciclo
@endsection

@section('warnings')
<div class="bd-callout bd-callout-warning offset-2 mb-4">
    <p>
        <span><small><strong>ADVERTENCIAS</strong></small></span>
    </p>
    <p>
        <li class="icon fas fa-warning mr-2" style="color:red;"></li><span style="text-size=4px">
            <small><strong>
                El nuevo ciclo automaticamente sera el nuevo ciclo activo.
            </strong></small></span>
    </p>
</div>
@endsection
@section("formulario")
<form action="{{route('ciclo.store')}}" method="post">
   @csrf
  <div class="form-group mb-4">
    <label for="titulo_input"><b title="Campo Obligatorio">*</b>&nbsp;Fecha de inicio:</label>
    <input type="date" class="form-control" id="inicio_ciclo" name="inicio_ciclo" placeholder="Seleccione la fecha de inicio del ciclo" value="{{ old('inicio_ciclo') }}">
  </div>
  <div class="form-group mt-4">
    <label for="titulo_input"><b title="Campo Obligatorio">*</b>&nbsp;Fecha de finalizacion:</label>
    <input type="date" class="form-control" id="fin_ciclo" name="fin_ciclo" placeholder="Seleccione la fecha de finalizacion del ciclo" value="{{ old('fin_ciclo') }}">
  </div>
  <div class="row text-center mt-4">
     <div class="form-group offset-3 mt-4">
         <button type="submit" class="btn btn-primary">Guardar</button>                 
     </div>
     <div class="form-group offset-1 mt-4">
         <a class="btn btn-secondary" href="{{route('ciclo.index')}}">Cancelar</a>                           
     </div>
  </div>
</form>
@endsection
