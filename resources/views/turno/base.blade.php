@extends("../layouts.plantilla")
@section("css")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />

    <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/sb-admin.min.css')}}">
@endsection
@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="/evaluacion/{{ $id }}/">Evaluación</a></li>
    <li class="breadcrumb-item"><a href="{{ URL::signedRoute('listado_turnos', ['id' => $id]) }}">Listado de turnos</a></li>
    <li class="breadcrumb-item">@yield("nombre_vista")</li>
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

<!--Mostrará mensaje de éxito en caso que la petición en clave-area se haya realizado correctamente-->
@if (session('exito'))
  <div class="alert alert-success">
    <ul>
      <h4 class="text-center">{{session('exito')}}</h4>
    </ul>
  </div>
@endif

<!--Mostrará mensaje de eror en caso que la petición en clave-area no se haya realizado correctamente-->
@if (session('error'))
  <div class="alert alert-danger">
    <ul>
      <h4 class="text-center">{{session('error')}}</h4>
    </ul>
  </div>
@endif
       
 <div class="col-md-12"> 
  <div class="card">
      <div class="card-header">
        <h4><b>@yield("titulo_card")</b></h4>
      </div>
      <div class="card-body">
          <div class="offset-2 col-md-8">
           @yield("formulario")
       </div>
      </div>
  </div>    
</div><br>

<div class="col-md-12">
  @yield('clave-area')
</div>    
@endsection

@endsection


@section("js")
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>

    <script type="text/javascript" src="{{asset('js/sb-admin.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/sb-admin.min.js')}}"></script>
    <!-- Bootstrap core JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js' )}}"></script>

    <!-- Core plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/jquery-easing/jquery.easing.min.js' )}}"></script>

    <!-- Page level plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/datatables/jquery.dataTables.js' )}}"></script>
    <script type="text/javascript" src="{{asset('vendor/datatables/dataTables.bootstrap4.js' )}}"></script>
    <script type="text/javascript" src="{{asset('js/demo/datatables-demo.js')}}"></script> 
    @yield("extra_js")
    
@endsection