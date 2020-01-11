@extends("../layouts.plantilla")
@section("head")
@endsection


@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route('materias')}}">Materia</a></li>
    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->id_carga]) }}">Evaluaciones</a> </li>
    <li class="breadcrumb-item">Edición</li>
@endsection
@section("main")
  <div class="row">
  <div class="col-md-2"></div>

  <div class="col-md-8">
  <div class="card">
  <div class="card-header bg-default">Editar Evaluación</div>
  <div class="card-body">
    <!-- Notificacion  -->
            @if (session('notification'))
                  <div class="alert alert-success">
                        {!!session('notification')!!}
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
      @if($evaluacion)
      <form action="" method="POST">
        {{ csrf_field() }}

          <div class="form-group">
             <label for="title">Título</label>
             <input type="text" name="title" class="form-control" value="{{ old('title',$evaluacion->nombre_evaluacion) }}">
          </div>
          <div class="form-group">
             <label for="description">Descripción</label>
             <textarea name="description" class="form-control">{{ old('description',$evaluacion->descripcion_evaluacion) }}</textarea>
          </div>
          
            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                 <label for="duration">Duración (minutos):</label>
                 <input type="number" min="1" max="" name="duration" class="form-control" value="{{ old('duration',$evaluacion->duracion) }}">
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                 <label for="intentos">Cantidad de intentos:</label>
                 <input type="number" min="1" max="" name="intentos" class="form-control" value="{{ old('intentos',$evaluacion->intentos) }}">
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                 <label for="paginacion">Preguntas por página:</label>
                 <select name="paginacion" class="form-control" >
                  @for ($i =1; $i <= 9; $i++)
                    @if($evaluacion->preguntas_a_mostrar==$i)
                    <option value="{{ $i }}" selected>{{ $i }}</option>
                    @else
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endif
                  @endfor
                  @for ($i =10; $i <= 25; $i+=5)
                    @if($evaluacion->preguntas_a_mostrar==$i)
                    <option value="{{ $i }}" selected>{{ $i }}</option>
                    @else
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endif
                  @endfor
                 </select>
                </div>
              </div>

              
          </div>

          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Parametros de revisión</label>
                  <div class="form-check">
                    <input 
                    @if($evaluacion->mostrar_nota)
                    checked
                    @endif
                    type="checkbox" name="mostrar_nota" class="form-check-input" >
                    <small for="mostrar_nota" class="form-text text-muted">Mostrar solamente la calificación obtenida al finalizar el intento.</small>

                    <input
                    @if($evaluacion->revision)
                    checked
                    @endif 
                    type="checkbox" name="revision" class="form-check-input" >
                    <small for="revision" class="form-text text-muted">Mostrar la calificación obtenida y la solución de la evaluación al finalizar el intento.</small>
                    
                  </div>
                </div>
            </div>
          </div>
          
          <div class="form-group">
             <button class="btn btn-primary">Editar</button>
             <a href="{{ URL::signedRoute('listado_evaluacion', ['id' => $evaluacion->id_carga]) }}" class="btn btn-secondary"> Cancelar</a>
          </div>

      </form>
      @endif
  </div>
</div>
 </div>
</div>
@endsection

@endsection


@section("footer")
@endsection


