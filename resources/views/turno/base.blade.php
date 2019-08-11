@extends("../layouts.plantilla")
@section("css")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
@endsection
@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="/evaluacion/{{ $id }}/">Evaluación</a></li>
    <li class="breadcrumb-item"><a href="/evaluacion/{{ $id }}/turnos/">Listado de turnos</a></li>
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
       
 <div class="offset-1 col-md-10"> 
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
@endsection

@endsection


@section("js")
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/turno/main.js') }}"></script>
@endsection