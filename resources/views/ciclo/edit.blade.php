@extends("ciclo.base")

@section("nombre_vista")
Editar Ciclo
@endsection


@section("titulo_card")
Editar Ciclo
@endsection


@section("formulario")
<form action="{{route('ciclo.update',['ciclo'=>$ciclo->id_ciclo])}}" method="POST">
   @csrf
   @method('PUT')
  <div class="form-group mb-4">
    <label for="titulo_input"><b title="Campo Obligatorio">*</b>&nbsp;Fecha de inicio:</label>
    <input type="date" class="form-control" id="inicio_ciclo" name="inicio_ciclo" placeholder="Seleccione la fecha de inicio del ciclo" value="{{$ciclo->inicio_ciclo}}">
  </div>
  <div class="form-group mt-4">
    <label for="titulo_input"><b title="Campo Obligatorio">*</b>&nbsp;Fecha de finalizacion:</label>
    <input type="date" class="form-control" id="fin_ciclo" name="fin_ciclo" placeholder="Seleccione la fecha de finalizacion del ciclo" value="{{$ciclo->fin_ciclo}}">
  </div>
  <div class="row text-center mt-4">
     <div class="form-group offset-3 mt-4">
         <button type="submit" class="btn btn-primary">Actualizar</button>                 
     </div>
     <div class="form-group offset-1 mt-4">
         <a class="btn btn-secondary" href="{{route('ciclo.index')}}">Cancelar</a>                           
     </div>
  </div>
</form>
@endsection

