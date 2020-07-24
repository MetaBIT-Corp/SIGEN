@extends("../layouts.plantilla")
@section("head")
@endsection


@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route('docentes_index')}}">Docentes</a></li>
    <li class="breadcrumb-item">Nuevo Docente</li>
@endsection
@section("main")
  <div class="row">

    <div class="offset-2 col-md-8">
    <div class="card">
    <div class="card-header bg-default">Nuevo docente</div>
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
              <label for="nombre_docente"><b title="Campo Obligatorio">*</b>&nbsp;Nombre</label>
                <input type="text" name="nombre_docente" id="nombre_docente" class="form-control" value="{{ old('nombre_docente') }}" required>
            </div>

            <div class="form-group">
               <div class="row">
                  <div class="col-md-6">
                    <label for="carnet_dcn"><b title="Campo Obligatorio">*</b>&nbsp;Carnet</label>
                    <input type="text" name="carnet_dcn" id="carnet_dcn" class="form-control" value="{{ old('carnet_dcn') }}" required>
                  </div>
                  <div class="col-md-6">
                    <label for="anio_titulo"><b title="Campo Obligatorio">*</b>&nbsp;Año de titulación</label>
                    <?php
                      $cont = date('Y');
                      $menor = $cont-100;
                    ?>
                    <select id="anio_titulo" name="anio_titulo" class="form-control">
                      <?php while ($cont >= $menor) { ?>

                        <option value="<?php echo($cont); ?>"><?php echo($cont);?></option>
                      <?php $cont = ($cont-1); } ?>
                    </select>
                  </div>
               </div>
            </div>
            
            

            <div class="form-group">
               <label for="email"><b title="Campo Obligatorio">*</b>&nbsp;Email</label>
               <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}" required> 
            </div>

            <div class="form-group">
               <label for="descripcion_docente"><b title="Campo Obligatorio">*</b>&nbsp;Descripción</label>
               <textarea name="descripcion_docente" id="descripcion_docente" class="form-control">{{ old('descripcion_docente') }}</textarea>
            </div>
            
            <div class="form-group">
              <div class="form-check">
                <input id="activo" type="checkbox" name="activo" class="form-check-input" checked>
                <label for="activo">
                  <small for="activo" class="form-text text-muted"><strong>Activo.</strong> Seleccione para indicar que es un usuario activo
                  </small>
                </label>
              </div>
            </div>    
        
            <div class="form-group">
               <button class="btn btn-primary">Guardar</button>
               <a href="{{ route('docentes_index')}}" class="btn btn-secondary"> Cancelar</a>
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