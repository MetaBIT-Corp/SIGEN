<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
</head>
<body>
	<input type="hidden" value="{{ $i=0 }}">
	<div>
		@if($esExcel)
		<table>
			<tr>
				<td colspan="2" style="width: 80px;font-size: 16px;"><b>{{ $encuesta->titulo_encuesta }}</b></td>
			</tr>
			@foreach($preguntas as $pregunta)
		        <tr>
		            <td colspan="2"><i><b>{{ ++$i }}. {{ $pregunta->pregunta }}:</b></i></td>
		        </tr>
		        <tr>
		        	<td style="width: 40px; text-align: center;"><b>Opción</b></td>
		        	<td style="width: 40px; text-align: center;"><b>Cantidad</b></td>
		        </tr>
		        @foreach($pregunta->opciones as $opcion)
					<tr>
						<td style="text-align: center;">{{ $opcion->opcion }}</td>
						<td style="text-align: center;">{{ $opcion->cantidad }}</td>
					</tr>
		        @endforeach
		    @endforeach
	    </table>
	    @else
	    	<table>
				<tr>
					<td colspan="2" style="text-align: center; font-size: 16px;"><b>{{ $encuesta->titulo_encuesta }}</b></td>
				</tr>
				@foreach($preguntas as $pregunta)
			        <tr>
			            <td colspan="2"><i><b>{{ ++$i }}. {{ $pregunta->pregunta }}:</b></i></td>
			        </tr>
			        <tr>
			        	<td style="text-align: center;"><b>Opción</b></td>
			        	<td style="text-align: center;"><b>Cantidad</b></td>
			        </tr>
			        @foreach($pregunta->opciones as $opcion)
						<tr>
							<td style="text-align: center;">{{ $opcion->opcion }}</td>
							<td style="text-align: center;">{{ $opcion->cantidad }}</td>
						</tr>
			        @endforeach
			    @endforeach
		    </table>s
	    @endif
	 </div>
</body>
</html>