@extends("../layouts.plantilla")
@section("head")

@endsection


@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Encuesta</a></li>
    <li class="breadcrumb-item">Nueva Encuesta</li>
@endsection
@section("main")

  <div class="card">
  <div class="card-header bg-default">Nueva Encuesta</div>
  <div class="card-body">
    <!--
    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
          @endforeach
        </ul>
      </div>
    @endif
    -->
      <form action="" method="POST">
        {{ csrf_field() }}
          <div class="form-group">
             <label for="title">Título</label>
             <input type="text" name="title" class="form-control" value="{{ old('title') }}">
          </div>
          <div class="form-group">
             <label for="description">Descripción</label>
             <textarea name="description" class="form-control">{{ old('description') }}</textarea>
          </div>
          
            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                 <label for="fecha_inicio">Fecha de Inicio:</label>
                 <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}">
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                 <label for="fecha_final">Fecha de Finalización:</label>
                 <input type="date" name="fecha_final" class="form-control" value="{{ old('fecha_final') }}">
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                 <label for="paginacion">Preguntas por página:</label>
                 <select name="paginacion" class="form-control">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="15">15</option>
                  <option value="20">20</option>
                 </select>
                </div>
              </div>
            </div>
          
          <div class="form-group">
             <button class="btn btn-primary">Crear Encuesta</button>
          </div>
         
      </form>
  </div>
</div>

  
@endsection
@endsection


@section("footer")
@endsection
