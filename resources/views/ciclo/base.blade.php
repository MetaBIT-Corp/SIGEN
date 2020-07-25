@extends("../layouts.plantilla")

@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="/ciclo">Listado de Ciclos</a></li>
    <li class="breadcrumb-item">@yield("nombre_vista")</li>
@endsection 

@section("main")        
    @if(count($errors))
        <div class="alert card alert-danger offset-1 col-md-10">
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
          @yield('warnings')
          <div class="offset-2 col-md-8">
           @yield("formulario")
          </div>
      </div>
  </div>    
</div><br>    
@endsection

@endsection