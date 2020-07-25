@extends("../layouts.plantilla")

@section("css")
  <style media="screen">
    img{
      max-width: 450px;
      height: auto;
    }
  </style>
@endsection

@section("body")

  @section("ol_breadcrumb")
      <li class="breadcrumb-item"><a href="#">Encuestas Disponibles</a></li>
  @endsection

  @section("main")

  
@if (session('exito'))
  <div class="alert alert-success">
    {!!session('exito')!!}
  </div>
@endif


@if (session('error'))
  <div class="alert alert-danger">
    {!!session('error')!!}
  </div>
@endif

@if (session('warning'))
  <div class="alert alert-warning">
    {!!session('warning')!!}
  </div>
@endif

    <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Encuestas </div>
          <div class="card-body">
            @if($encuestas)
        		<div class="list-group">
              <div class="row">
                @forelse($encuestas as $encuesta)
                  <div class="col-md-6">
              		  <span href="#" class="list-group-item flex-column align-items-start mb-3">
              		    <div class="d-flex w-100 justify-content-between">
              		      <h5 class="mb-3">{{$encuesta->titulo_encuesta}}</h5>
              		      <small></small>
              		    </div>
              		    <!--<p class="mb-2">{{$encuesta->descripcion_encuesta}}</p>-->
              		    <small>Disponible desde: {{$encuesta->fecha_inicio_encuesta}} hasta: {{$encuesta->fecha_final_encuesta}} </small>
                       <br>
                      <small>Autor: {{$encuesta->docente->nombre_docente}} </small>
                      <br><br>
                      <button type="button" class="btn btn-info mt-1 mb-1" 
                        data-acceder-encuesta="{{$encuesta->IdClave}}"
                        data-descripcion-encuesta="{{$encuesta->descripcion_encuesta}}" 
                        data-titulo-encuesta="{{$encuesta->titulo_encuesta}}" 
                        data-id-encuesta="{{$encuesta->id}}"
                        data-ruta-encuesta="{{$encuesta->ruta}}"
                        >Acceder</button>
              		  </span>
                    
                  </div>
                @empty
                  <div class="offset-4 col-md-4" align="center">
                    <img src="{{asset('img/no-data.svg')}}" alt="no-data" width="50%">
                    <p>No se encuentran encuestas <br>disponibles</p>
                  </div>
                @endforelse
            </div>
        		</div>
            @else
              <div class="alert alert-info">
                 No se encuentran encuestas disponibles
              </div>
            @endif
          </div>
          <div class="card-footer small text-muted"></div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content-wrapper -->
  </div>
  <!-- /#wrapper -->

  <!-- Modal para acceder a encuesta -->
<div class="modal fade" id="accederEncuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="accederModalCenterTitle">Acceso a Encuesta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" >
        <div class="card card-cascade wider reverse">
            <!-- Card image -->
            <div class="view view-cascade overlay" align="center">
              <img id="img_encuesta" class="card-img-top" style="" src="https://lh3.googleusercontent.com/p/AF1QipO1Q0Sh_IlRVhVkwlrGgUFQphOcjCsLb4ACa4Yc=s1600-w400" alt="Card image cap" >
              <a href="#!">
                <div class="mask rgba-white-slight"></div>
              </a>
            </div>
            <!-- Card content -->
            <div class="card-body card-body-cascade text-center">
              <!-- Title -->
              <h4 class="card-title" ><strong  id="titulo_acceso">Encuestas</strong></h4>
            
              <!-- Text -->
              <p class="card-text text-justify" id="descripcion_acceso"></p>
              
              <form action="{{ route('acceso_encuesta')}}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" id="id_clave_acceso" value="" name="id_clave">
                <input type="hidden" id="id_encuesta_acceso" value="" name="id_encuesta_acceso">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Acceder</button>
              </form>
            </div>

          </div>
          <!-- Card -->
      </div>   
        <div class="modal-footer">
          
        </div>
     
    </div>
  </div>
</div>
  @endsection 
  @section('js')
    <script>
      $('[data-acceder-encuesta]').on('click', function(){
          var indicaciones = "<strong>Indicaciones: </strong>";
          $('#id_clave_acceso').attr('value', $(this).data('acceder-encuesta'));
          $('#id_encuesta_acceso').attr('value', $(this).data('id-encuesta'));
          $('#descripcion_acceso').html( indicaciones.concat($(this).data('descripcion-encuesta')));
          $('#titulo_acceso').html( $(this).data('titulo-encuesta'));
          $('#accederEncuesta').modal('show');

          if($(this).data('ruta-encuesta') != ""){
            $('#img_encuesta').attr('src', "/images/" + $(this).data('ruta-encuesta'));
          }
      });
    </script>

@endsection 
@endsection

