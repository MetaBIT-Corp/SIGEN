<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Envío de Email</title>
</head>
<body>
	<div >
	  <h3>{{ $titulo }}</h3>
	  <p>A continuacion, se anexan sus credenciales:</p>
	  <hr>
	  <p>email: {{ $email }}</p>
      <p>contrasena: {{ $password }}</p>
	  <a href="http://sigen.herokuapp.com/login">Acceder</a>
	</div>
</body>
</html>