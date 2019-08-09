@extends("../layouts.plantilla")
@section("css")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
@endsection
@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Evaluación</a></li>
    <li class="breadcrumb-item"><a href="listado_estudiante">Listado de turnos</a></li>
    <li class="breadcrumb-item">Crear turno</li>
@endsection

@section("main")
    @if(session('notification-message') and session('notification-type'))
     <div class="alert alert-{{ session('notification-type') }} offset-1 col-md-10">
        <ul>
            <li>
                 {{ session('notification-message') }}
            </li>
        </ul>
     </div>
    @endif
    
    @if(count($errors))
         <div class="alert alert-danger">
             <ul>
                  @foreach($errors->all() as $error)
                            
                            <li>{{ $error }}</li>

                   @endforeach
               </ul>
           </div>
       @endif
       
 <div class="offset-1 col-md-10"> 
  <div class="card">
      <div class="card-header">
        <h3>Crear turno</h3>
      </div>
      <div class="card-body">
          <div class="offset-2 col-md-8">
            <form action="/evaluacion/{{ $id }}/turnos" method="post">
              @csrf
              <div class="form-group">
                <label for="exampleInputEmail1">Fecha + Hora de inicio:</label>
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                    <input id="datetimepicker1input" type="text" name="fecha_inicio_turno" class="form-control datetimepicker-input" data-target="#datetimepicker1" placeholder="mm/dd/yyyy hh:mm tt" value="{{ old('fecha_inicio_turno') }}" data-id_evaluacion="{{ $id }}"/>
                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepicker1').datetimepicker({
                                    locale: 'fr'
                                });
                            });
                        </script>
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Fecha + Hora de fin:</label>
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                    <input id="datetimepicker2input" type="text" name="fecha_final_turno" class="form-control datetimepicker-input" data-target="#datetimepicker2" placeholder="mm/dd/yyyy hh:mm tt" value="{{ old('fecha_final_turno') }}" readonly/>
                                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepicker2').datetimepicker({
                                    locale: 'fr'
                                });
                            });
                        </script>
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Contraseña:</label>
                <input type="password" name="contraseña" class="form-control" style="margin-left:15px" id="exampleInputPassword1" placeholder="Contraseña" value="{{ old('contraseña') }}">
              </div>
              <div class="form-check">
                <input type="checkbox" name="visibilidad" class="form-check-input">
                <label class="form-check-label" for="exampleCheck1">Visible</label>
                <small class="form-text text-muted">Al marcarlo el turno será visible para los estudiantes.</small>
              </div><br>
              <div class="row">
                 <div class="form-group">
                     <button type="submit" class="btn btn-primary">Guardar</button>                 
                 </div>
                 <div class="form-group offset-1">
                     <button type="button" class="btn btn-secondary">Cancelar</button>                           
                 </div>
              </div>
            </form><br>
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
    <script type="text/javascript">
        
        var $jq = jQuery.noConflict();
        $jq(document).ready(function() {
            
            $jq('#datetimepicker1input').blur(function(){
                
                var id_evaluacion = $(this).data('id_evaluacion');
                var fecha_hora_inicio = $(this).val();
                
                $jq.ajax({
                    url: '/api/evaluacion/'+id_evaluacion+'/duracion/',
                    type: 'GET',
                    success: function(data){ 
                        console.log(data);
                        var date = moment(fecha_hora_inicio).add(data,'hours').format('L hh:mm A');
                        $jq('#datetimepicker2input').val(date);
                    },
                    error: function(xhr, status, error) {
                        console.log('status:'+status+', error:'+error);
                    }
                });
                
            });
            
        });
        
    </script>
@endsection