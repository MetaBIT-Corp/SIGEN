@extends("../layouts.plantilla")
@section("head")
@endsection
@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route('estudiantes_index')}}">Estudiantes</a></li>
    <li class="breadcrumb-item">Actualizar Estudiante</li>
@endsection
@section("main")
  <div class="row">
  <div class="col-md-2"></div>

  <div class="col-md-8">
  <div class="card">
  <div class="card-header bg-default">Actualizar estudiante</div>
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
          <input type="hidden" name="id_est" value="{{$estudiante->id_est}}">
          <input type="hidden" name="user_id" value="{{$estudiante->user_id}}">
          <div class="form-group">
              <label for="nombre"><b title="Campo Obligatorio">*</b>&nbsp;Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $estudiante->nombre) }}" required>
            </div>

            <div class="form-group">
               <div class="row">
                  <div class="col-md-6">
                    <label for="carnet"><b title="Campo Obligatorio">*</b>&nbsp;Carnet</label>
                    <input type="text" name="carnet" id="carnet" class="form-control" value="{{ old('carnet', $estudiante->carnet) }}" required>
                  </div>
                  <div class="col-md-6">
                    <label for="anio_ingreso"><b title="Campo Obligatorio">*</b>&nbsp;Año de ingreso</label>
                    <?php
                      $cont = date('Y');
                      $menor = $cont-100;
                    ?>
                    <select id="anio_ingreso" name="anio_ingreso" class="form-control">
                      <?php while ($cont >= $menor) { ?>
                        <option 
                        @if($estudiante->anio_ingreso == $cont)
                        selected
                        @endif
                        value="<?php echo($cont); ?>"><?php echo($cont);?></option>
                      <?php $cont = ($cont-1); } ?>
                    </select>
                  </div>
               </div>
            </div>
            
            

            <div class="form-group">
               <label for="email"><b title="Campo Obligatorio">*</b>&nbsp;Email</label>
               <input type="email" name="email" id="email" class="form-control" value="{{old('email', $email)}}" required> 
            </div>
            
            <div class="form-group">
              <div class="form-check">
                <input id="activo" type="checkbox" name="activo" class="form-check-input" required
                @if($estudiante->activo)
                checked
                >
                @endif
                <label for="activo">
                  <small for="activo" class="form-text text-muted"><strong>Activo.</strong> Seleccione para indicar que es un usuario activo
                  </small>
                </label>
              </div>
            </div>    
          
          <div class="form-group">
             <button class="btn btn-primary">Guardar</button>
             <a href="{{route('estudiantes_index')}}" class="btn btn-secondary"> Cancelar</a>
             
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


