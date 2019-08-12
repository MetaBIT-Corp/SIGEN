@extends("../layouts.plantilla")
@section("head")
@endsection

@section("css")
<link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
@endsection

@section("body")
@section("ol_breadcrumb")
    <li class="breadcrumb-item">Materia</li>
@endsection
@section("main")
@if(auth()->user()->role==2)
<div class="card">
  <div class="card-header">
    <h4>Ciclo {{ $ciclo[0]->num_ciclo }}  Año: {{ $ciclo[0]->anio }}</h4>
  </div>
  <div class="card-body">
<table class="col-12">
<tbody>
<tr class=" row mb-3">
@forelse($materias as $materia)
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
<!-- Validacion para que solo dos card view por filas se muestren-->
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
@empty
<tr>
	<td><h2 class="h1">No hay materias en este ciclo.</h2></td>
</tr>
@endforelse
</tbody> 
</table>
  </div>
</div>
@else
@if(count($ciclos)>0)
<div class="accordion col-10" id="accordionExample">
@foreach($materias as $ciclo=>$materias)
@if($ciclos[$loop->iteration-1]->estado==1)
<!-- Inicio de un Collapse -->
  <div class="card">
  	<!-- Titulo de Collapse -->
    <div class="card-header btn-link" type="button" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
      <h4 class="mb-0">
          Ciclo {{ $ciclos[$loop->iteration-1]->num_ciclo }} Año: {{ $ciclos[$loop->iteration-1]->anio }}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Estado: Activo
      </h4>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        <!-- ----------------------------Inicio de Card Views----------------------------------- -->
@else
<!-- Inicio de un Collapse -->
  <div class="card">
  	<!-- Titulo de Collapse -->
    <div class="card-header btn-link collapsed" type="button" id="heading{{ $ciclo }}" data-toggle="collapse" data-target="#collapse{{ $ciclo }}" aria-expanded="false" aria-controls="collapse{{ $ciclo }}">
      <h4 class="mb-0">
          Ciclo {{ $ciclos[$loop->iteration-1]->num_ciclo }} Año: {{ $ciclos[$loop->iteration-1]->anio }}
      </h4>
    </div>

    <div id="collapse{{ $ciclo }}" class="collapse" aria-labelledby="heading{{ $ciclo }}" data-parent="#accordionExample">
      <div class="card-body">
        <!-- ----------------------------Inicio de Card Views----------------------------------- -->
@endif
<table class="col-12">
<tbody>
<tr class=" row mb-3">
@forelse($materias as $materia)
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
      <a class="icon-list btn" href="{{ route('listado_estudiante',$materia->id_mat_ci) }}"></a>
      <a href="{{ route('docentes_materia_ciclo',$materia->id_mat_ci) }}">ver</a>
    </div>
</div>
</td>
<!-- Validacion para que solo dos card view por filas se muestren-->
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
      <a class="icon-list btn" href="{{ route('listado_estudiante',$materia->id_mat_ci) }}"></a>
      <a href="{{ route('docentes_materia_ciclo',$materia->id_mat_ci) }}">ver</a>
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
      <a class="icon-list btn" href="{{ route('listado_estudiante',$materia->id_mat_ci) }}"></a>
      <a href="{{ route('docentes_materia_ciclo',$materia->id_mat_ci) }}">ver</a>
    </div>
</div>
</td>
@endif
@empty
<tr>
	<td><h2 class="h1">No hay materias en este ciclo.</h2></td>
</tr>
@endforelse
</tbody> 
</table>
      </div>
    </div>
  </div>
 <!-- Fin de un Collapse -->
 @endforeach
</div>

@else
<h2 class="h1">No hay ciclos registrados.</h2>
@endif
@endif
@endsection
@endsection


@section("footer")
@endsection