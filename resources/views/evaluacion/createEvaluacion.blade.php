@extends("../layouts.plantilla")
@section("head")
@endsection


@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route('materias')}}">Materia</a></li>
    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('listado_evaluacion', ['id' => $id]) }}">Evaluaciones</a> </li>
    <li class="breadcrumb-item">Nueva Evaluacion</li>
@endsection
@section("main")
  <div class="row">
  <div class="col-md-2"></div>

  <div class="col-md-8">
  <div class="card">
  <div class="card-header bg-default">Nueva Evaluación</div>
  <div class="card-body">
    <!-- Notificacion  -->
            @if (session('notification'))
                  <div class="alert alert-success">
                        {{session('notification')}}
                  </div>
            @endif
            <!-- Notificacion -->

            <!-- Validaciones -->
            @if (count($errors) > 0)
                  <div class="alert alert-danger">
                        <ul>
                              @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                              @endforeach
                        </ul>
                  </div>
            @endif
            <!-- Validaciones -->
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

              <div class="col-md-3">
                <div class="form-group">
                 <label for="duration">Duración (minutos):</label>
                 <input type="number" min="1" max="" value="50" name="duration" class="form-control" value="{{ old('duration') }}">
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                 <label for="intentos">Cantidad de intentos:</label>
                 <input type="number" min="1" max="" value="1" name="intentos" class="form-control" value="{{ old('intentos') }}">
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                 <label for="paginacion">Preguntas por página:</label>
                 <select name="paginacion" class="form-control">
                  @for ($i =1; $i <= 9; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                  @for ($i =10; $i <= 25; $i+=5)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                 </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for=""></label>
                  <div class="form-check">
                    <input type="checkbox" name="revision" class="form-check-input" >
                    <label class="form-check-label" for="exampleCheck1">Revisión</label>
                    <small class="form-text text-muted">La solución de la evaluación será visible para los estudiantes al finalizar el intento.</small>
                  </div>
                </div>
            </div>
          </div>
          
          <div class="form-group">
             <button class="btn btn-primary">Guardar</button>
             <a href="{{route('listado_evaluacion',$id)}}" class="btn btn-secondary"> Cancelar</a>
             
          </div>

      </form>
  </div>
</div>
 </div>
</div>
@endsection

@endsection


@section("footer")
@endsection


