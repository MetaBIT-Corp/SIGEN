<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Envío de Email</title>
</head>
<body>
	<div >
	  <h1>Nueva Evaluación! {{$titulo}}</h1>
	  <p>{{$descripcion}}.</p>
	  <hr>
	  <p>Turnos. {{$periodo}}</p>
	  <a href="http://sigen.herokuapp.com/login">Acceder</a>
	</div>
</body>
</html>