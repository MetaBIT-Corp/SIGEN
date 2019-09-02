@extends("area.base")

@section("nombre_vista")
Crear área
@endsection


@section("titulo_card")
Crear Área
@endsection


@section("formulario")
<form action="/materia/{{ $id_materia }}/areas" method="post">
   @csrf
    <div class="form-group">
        <label for="select_tipo_item">Tipo de item</label>
        <select class="form-control" id="select_tipo_item" name="tipo_item">
          @if(count($tipos_item))
              <option>------ Seleccione ------</option>
               @foreach($tipos_item as $tipo_item)
                   <option value="{{ $tipo_item->id }}" @if( old('tipo_item') == $tipo_item->id ) selected @endif>{{ $tipo_item->nombre_tipo_item }}</option>
               @endforeach
           @else
             <option>------ No hay opciones disponibles! ------</option>
           @endif
        </select>
      </div>
      <div class="form-group">
        <label for="titulo_input">Titulo del área</label>
        <input type="text" class="form-control" id="titulo_input" name="titulo" placeholder="Ingrese el título..." value="{{ old('titulo') }}">
      </div>
      <div class="row">
         <div class="form-group">
             <button type="submit" class="btn btn-primary">Guardar</button>                 
         </div>
         <div class="form-group offset-1">
             <button type="button" class="btn btn-secondary" onclick="location.href='{{ route('areas.index',[$id_materia]) }}'">Cancelar</button>                           
         </div>
      </div>
</form>
@endsection