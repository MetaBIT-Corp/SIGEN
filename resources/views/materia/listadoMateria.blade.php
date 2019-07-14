@extends("../layouts.plantilla")
@section("head")
@endsection

@section("body")
@section("ol_breadcrumb")
    <li class="breadcrumb-item">Materia</li>
@endsection
@section("main")
@if(count($materias)>0)
<div class="accordion col-9" id="accordionExample">

<!-- Inicio de un Collapse -->
  <div class="card">
  	<!-- Titulo de Collapse -->
    <div class="card-header btn-link" type="button" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
      <h4 class="mb-0">
          Ciclo I
      </h4>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        <!-- ----------------------------Inicio de Card Views----------------------------------- -->

<table class="table col-12">
<tbody>
<tr class=" row mb-3">
@foreach($materias as $materia)
@if($loop->iteration==1||$loop->iteration==2)
<td class="col-6">
<div class="card border-dark bg-light">
    <div class="card-body">
      <h5 class="card-title"><strong>{{ $materia->codigo_mat }}</strong></h5>
      <p class="card-text">{{ $materia->nombre_mar }}<br>
      	 @if($materia->es_electiva==0)
      	  Es una Materia obligatoria.
      	 @else
      	  Es una materia electiva.
      	 @endif
      </p>
    </div>
    <div class="card-footer">
      <small class="text-muted">Acciones</small>
    </div>
</div>
</td>
@elseif(($loop->iteration+1)%2!=0)
<td class="col-6">
<div class="card border-dark bg-light">
    <div class="card-body">
      <h5 class="card-title"><strong>{{ $materia->codigo_mat }}</strong></h5>
      <p class="card-text">{{ $materia->nombre_mar }}<br>
      	 @if($materia->es_electiva==0)
      	  Es una Materia obligatoria.
      	 @else
      	  Es una materia electiva.
      	 @endif
      </p>
    </div>
    <div class="card-footer">
      <small class="text-muted">Acciones</small>
    </div>
</div>
</td>
@else
</tr>
<tr class="row mb-3">
<td class="col-6">
<div class="card border-dark bg-light">
    <div class="card-body">
      <h5 class="card-title"><strong>{{ $materia->codigo_mat }}</strong></h5>
      <p class="card-text">{{ $materia->nombre_mar }}<br>
      	 @if($materia->es_electiva==0)
      	  Es una Materia obligatoria.
      	 @else
      	  Es una materia electiva.
      	 @endif
      </p>
    </div>
    <div class="card-footer">
      <small class="text-muted">Acciones</small>
    </div>
</div>
</td>
@endif
@endforeach
</tbody> 
</table>
      </div>
    </div>
  </div>
 <!-- Fin de un Collapse -->
</div>

@else
<h2 class="h1">No hay datos.</h2>
@endif

@endsection
@endsection


@section("footer")
@endsection
