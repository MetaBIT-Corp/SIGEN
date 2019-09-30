@extends("../layouts.plantilla")

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="/materias">Materia</a></li>
    <li class="breadcrumb-item"><a href="/materia/{{ $id_materia }}/areas">Listado de áreas</a></li>
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
