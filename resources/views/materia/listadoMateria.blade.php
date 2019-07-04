@extends("../layouts.plantilla")
@section("head")
@endsection

@section("body")
@section("main")
<table class="table text-center">
  <thead class="thead-dark">
    <tr>	
      <th scope="col">#</th>
      <th scope="col">Codigo Materia</th>
      <th scope="col">Nombre Materia</th>
      <th scope="col">Electiva</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($materias as $materia)
    <tr>
      <th scope="row">{{ $loop->iteration }}</th>
      <td>{{ $materia->codigo_mat }}</td>
      <td>{{ $materia->nombre_mar }}</td>
      @if($materia->es_electiva==0)
      <td>NO</td>
      @else
      <td>SI</td>
      @endif
    </tr>
    @endforeach
    
  </tbody>
</table>

@endsection
@endsection


@section("footer")
@endsection
