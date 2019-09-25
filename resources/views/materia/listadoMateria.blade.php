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
<table class="col-md-12">
<tbody>
<tr class=" row mb-3">
@forelse($materias as $materia)
@if($loop->iteration==1||$loop->iteration==2)
<td class="col-md-6">
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

      <a class="btn btn-option btn-sm" title="Estudiantes Inscritos" href="{{ URL::signedRoute('listado_estudiante', ['id' => $materia->id_mat_ci]) }}">
          <span class="icon-users"></span>
      </a>
      
      <a class="btn btn-option btn-sm" title="Docentes" href="{{ URL::signedRoute('docentes_materia_ciclo', ['id_mat_ci' => $materia->id_mat_ci]) }}">
        <span class="icon-admin"></span>
      </a>

      <a class="btn btn-option btn-sm" title="Evaluaciones" href="
      {{ URL::signedRoute('listado_evaluacion', ['id' => $materia->id_carg_aca]) }}">
        <span class="icon-file-text"></span>
      </a>
      @if(auth()->user()->IsTeacher)
      <a class="btn btn-option btn-sm" title="Areas" href="{{ URL::signedRoute('areas.index', ['id' => $materia->id_cat_mat]) }}">
        <span class="icon-options"></span>
      </a>
      @endif

    </div>
</div>
</td>
<!-- Validacion para que solo dos card view por filas se muestren-->
@elseif(($loop->iteration+1)%2!=0)
<td class="col-md-6">
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
      <a class="btn btn-option btn-sm" title="Estudiantes Inscritos" href="{{ URL::signedRoute('listado_estudiante', ['id' => $materia->id_mat_ci]) }}">
          <span class="icon-users"></span>
      </a>

      <a class="btn btn-option btn-sm" title="Docentes" href="{{ URL::signedRoute('docentes_materia_ciclo', ['id_mat_ci' => $materia->id_mat_ci]) }}">
        <span class="icon-admin"></span>
      </a>

      <a class="btn btn-option btn-sm" title="Evaluaciones" href="
      {{ URL::signedRoute('listado_evaluacion', ['id' => $materia->id_carg_aca]) }}">
        <span class="icon-file-text"></span>
      </a>
      @if(auth()->user()->IsTeacher)
      <a class="btn btn-option btn-sm" title="Areas" href="{{ URL::signedRoute('areas.index', ['id' => $materia->id_cat_mat]) }}">
        <span class="icon-options"></span>
      </a>
      @endif

    </div>
</div>
</td>
@else
</tr>
<tr class="row mb-3">
<td class="col-md-6">
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

      <a class="btn btn-option btn-sm" title="Estudiantes Inscritos" href="{{ URL::signedRoute('listado_estudiante', ['id' => $materia->id_mat_ci]) }}">
          <span class="icon-users"></span>
      </a>

      <a class="btn btn-option btn-sm" title="Docentes" href="{{ URL::signedRoute('docentes_materia_ciclo', ['id_mat_ci' => $materia->id_mat_ci]) }}">
        <span class="icon-admin"></span>
      </a>

      <a class="btn btn-option btn-sm" title="Evaluaciones" href="
      {{ URL::signedRoute('listado_evaluacion', ['id' => $materia->id_carg_aca]) }}">
        <span class="icon-file-text"></span>
      </a>
      @if(auth()->user()->IsTeacher)
      <a class="btn btn-option btn-sm" title="Areas" href="{{ URL::signedRoute('areas.index', ['id' => $materia->id_cat_mat]) }}">
        <span class="icon-options"></span>
      </a>
      @endif
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
<div class="accordion col-md-10" id="accordionExample">
@foreach($materias as $ciclo=>$materias)
@if($ciclos[$loop->iteration-1]->estado==1)
<!-- Inicio de un Collapse -->
  <div class="card">
  	<!-- Titulo de Collapse -->
    <div class="card-header btn text-primary text-left" type="button" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
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
    <div class="card-header btn text-primary text-left collapsed" type="button" id="heading{{ $ciclo }}" data-toggle="collapse" data-target="#collapse{{ $ciclo }}" aria-expanded="false" aria-controls="collapse{{ $ciclo }}">
      <h4 class="mb-0">
          Ciclo {{ $ciclos[$loop->iteration-1]->num_ciclo }} Año: {{ $ciclos[$loop->iteration-1]->anio }}
      </h4>
    </div>

    <div id="collapse{{ $ciclo }}" class="collapse" aria-labelledby="heading{{ $ciclo }}" data-parent="#accordionExample">
      <div class="card-body">
        <!-- ----------------------------Inicio de Card Views----------------------------------- -->
@endif
<table class="col-md-12">
<tbody>
<tr class=" row mb-3">
@forelse($materias as $materia)
@if($loop->iteration==1||$loop->iteration==2)
<td class="col-md-6">
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

      <a class="btn btn-option btn-sm" title="Estudiantes Inscritos" href="{{ URL::signedRoute('listado_estudiante', ['id' => $materia->id_mat_ci]) }}">
          <span class="icon-users"></span>
      </a>

      <a class="btn btn-option btn-sm" title="Docentes" href="{{ URL::signedRoute('docentes_materia_ciclo', ['id_mat_ci' => $materia->id_mat_ci]) }}">
        <span class="icon-admin"></span>
      </a>

      @if(auth()->user()->role == 0)
        <a class="btn btn-option btn-sm" title="Evaluaciones" href="
      {{ URL::signedRoute('listado_evaluacion', ['id' => $materia->id_mat_ci]) }}">
          <span class="icon-file-text"></span>
        </a>
      @elseif(auth()->user()->role == 1)
        <a class="btn btn-option btn-sm" title="Evaluaciones" href="
      {{ URL::signedRoute('listado_evaluacion', ['id' => $materia->id_carg_aca]) }}">
          <span class="icon-file-text"></span>
        </a>
      @endif

      @if(auth()->user()->IsTeacher)
      <a class="btn btn-option btn-sm" title="Areas" href="{{ URL::signedRoute('areas.index', ['id' => $materia->id_cat_mat]) }}">
        <span class="icon-options"></span>
      </a>
      @endif

    </div>
</div>
</td>
<!-- Validacion para que solo dos card view por filas se muestren-->
@elseif(($loop->iteration+1)%2!=0)
<td class="col-md-6">
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
      <a class="btn btn-option btn-sm" title="Estudiantes Inscritos" href="{{ URL::signedRoute('listado_estudiante', ['id' => $materia->id_mat_ci]) }}">
          <span class="icon-users"></span>
      </a>

      <a class="btn btn-option btn-sm" title="Docentes" href="{{ URL::signedRoute('docentes_materia_ciclo', ['id_mat_ci' => $materia->id_mat_ci]) }}">
        <span class="icon-admin"></span>
      </a>

      @if(auth()->user()->role == 0)
        <a class="btn btn-option btn-sm" title="Evaluaciones" href="
      {{ URL::signedRoute('listado_evaluacion', ['id' => $materia->id_mat_ci]) }}">
          <span class="icon-file-text"></span>
        </a>
      @elseif(auth()->user()->role == 1)
        <a class="btn btn-option btn-sm" title="Evaluaciones" href="
      {{ URL::signedRoute('listado_evaluacion', ['id' => $materia->id_carg_aca]) }}">
          <span class="icon-file-text"></span>
        </a>
      @endif

      @if(auth()->user()->IsTeacher)
      <a class="btn btn-option btn-sm" title="Areas" href="{{ URL::signedRoute('areas.index', ['id' => $materia->id_cat_mat]) }}">
        <span class="icon-options"></span>
      </a>
      @endif

    </div>
</div>
</td>
@else
</tr>
<tr class="row mb-3">
<td class="col-md-6">
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
      <a class="btn btn-option btn-sm" title="Estudiantes Inscritos" href="{{ route('listado_estudiante',$materia->id_mat_ci) }}">
        <span class="icon-users"></span>
      </a>

      <a class="btn btn-option btn-sm" title="Docentes" href="{{ URL::signedRoute('listado_estudiante', ['id' => $materia->id_mat_ci]) }}">
        <span class="icon-admin"></span>
      </a>
      
      @if(auth()->user()->role == 0)
        <a class="btn btn-option btn-sm" title="Evaluaciones" href="
      {{ URL::signedRoute('listado_evaluacion', ['id' => $materia->id_mat_ci]) }}">
          <span class="icon-file-text"></span>
        </a>
      @elseif(auth()->user()->role == 1)
        <a class="btn btn-option btn-sm" title="Evaluaciones" href="
      {{ URL::signedRoute('listado_evaluacion', ['id' => $materia->id_carg_aca]) }}">
          <span class="icon-file-text"></span>
        </a>
      @endif

      @if(auth()->user()->IsTeacher)
      <a class="btn btn-option btn-sm" title="Areas" href="{{ URL::signedRoute('areas.index', ['id' => $materia->id_cat_mat]) }}">
        <span class="icon-options"></span>
      </a>
      @endif
    </div>
</div>
</td>
@endif
@empty
<tr>
	<td class="alert alert-info"><h2 class="h4 text-center">No hay materias en este ciclo.</h2></td>
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