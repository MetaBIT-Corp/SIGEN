@extends("../layouts.plantilla")



@section("body")

  @section("ol_breadcrumb")
      <li class="breadcrumb-item"><a href="#">Encuestas Disponibles</a></li>
  @endsection

  @section("main")
    <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Encuestas </div>
          <div class="card-body">
        		<div class="list-group">
              <div class="row">
                @forelse($encuestas as $encuesta)
                  <div class="col-md-6">
              		  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start mb-3">
              		    <div class="d-flex w-100 justify-content-between">
              		      <h5 class="mb-1">{{$encuesta->titulo_encuesta}}</h5>
              		      <small></small>
              		    </div>
              		    <p class="mb-1">{{$encuesta->titulo_encuesta}}</p>
              		    <small>Disponible hasta: {{$encuesta->fecha_final_encuesta}} </small>
              		  </a>
                  </div>
                @empty
                @endforelse
            </div>
        		</div>
          </div>
          <div class="card-footer small text-muted"></div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content-wrapper -->
  </div>
  <!-- /#wrapper -->



  @endsection  
@endsection

