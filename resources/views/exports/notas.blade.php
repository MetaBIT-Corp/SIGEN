<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
</head>
<body>
	<table class="table">
		<thead>
			<tr>
				<td colspan="3" style="text-align: center; font-size: 14px;"><b>{{ $materia->nombre_mar }}</b></td>
			</tr>
			<tr>
				<td colspan="3" style="text-align: center; font-size: 14px;"><b>Notas de <i>{{ $evaluacion->nombre_evaluacion }}.</i></b></td>
			</tr>
			<tr>
				<th style="width: 15px;"><b>CARNET</b></th>
				<th style="width: 40px;"><b>NOMBRE</b></th>
				<th style="width: 10px; text-align: center;"><b>NOTA</b></th>
			</tr>
		</thead>
		<tbody>
			@foreach($estudiantes as $estudiante)
		        <tr>
		            <td>{{ $estudiante->carnet }}</td>
		            <td>{{ $estudiante->nombre }}</td>
		            <td style="text-align: center;">{{ $estudiante->nota }}</td>
		        </tr>
		    @endforeach
		</tbody>
	</table>
</body>
</html>