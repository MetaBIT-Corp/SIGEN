@extends("../layouts.plantilla")
@section("css")

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />

@endsection


@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Encuestas</a></li>
    <li class="breadcrumb-item">Edición</li>
@endsection
@section("main")
  <div class="row">
  <div class="col-md-2"></div>

  <div class="col-md-8">
  <div class="card">
  <div class="card-header bg-default">Edición Encuesta</div>
  <div class="card-body">


    @if (session('notification'))
      <div class="alert alert-success">
        <ul>
          <h4 class="text-center">{{session('notification')}}</h4>
        </ul>
      </div>
    @endif
    
    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
          @endforeach
        </ul>
      </div>
    @endif
    
    
      <form action="" method="POST">
        {{ csrf_field() }}
          <div class="form-group">
             <label for="title">Título</label>
             <input type="text" name="title" class="form-control" value="{{ old('title',$encuesta->titulo_encuesta) }}">
          </div>
          <div class="form-group">
             <label for="description">Descripción</label>
             <textarea name="description" class="form-control">{{ old('description',$encuesta->descripcion_encuesta) }}</textarea>
          </div>
          
            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Fecha y Hora de inicio:</label>
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input id="datetimepicker1input" type="text" name="fecha_inicio" class="form-control datetimepicker-input" data-target="#datetimepicker1" placeholder="dd/mm/yyyy hh:mm A" value="{{ old('fecha_inicio',$encuesta->fecha_inicio_encuesta) }}" 
                        @if(!$se_puede_editar)
                        disabled="true" 
                        @endif
                        />
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Fecha y Hora de fin:</label>
                  <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        <input id="datetimepicker2input" type="text" name="fecha_final" class="form-control datetimepicker-input col-md-12" data-target="#datetimepicker2" placeholder="dd/mm/yyyy hh:mm A" value="{{ old('fecha_final',$encuesta->fecha_final_encuesta) }}"/>
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
              </div>
              
              
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for=""></label>
                  <div class="form-check">
                    <input type="checkbox" name="visible" class="form-check-input" 
                    @if($encuesta->visible==1) 
                    checked
                    @endif
                    >
                    <label class="form-check-label" for="exampleCheck1">Visible</label>
                    <small class="form-text text-muted text-justify">La encuesta será visible para los usuarios. No se podrá acceder mientras no se encuentre en periodo de disponibilidad</small>
                  </div>
                </div>
              </div>
            </div>
          <input type="hidden" name="se_puede_editar" value="{{$se_puede_editar}}">
          <div class="form-group">
             <button class="btn btn-primary mb-3">Editar</button>
             <a href="{{route('listado_encuesta')}}" class="btn btn-secondary mb-3"> Cancelar</a>
          </div>
         
      </form>
  </div>
</div>
</div>
</div>


  
@endsection
@endsection

@section("js")
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>  
@endsection