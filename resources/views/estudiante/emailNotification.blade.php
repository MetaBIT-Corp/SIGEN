<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Envío de Email</title>
</head>
<body>
	<div >
	  <h3>{{ $titulo }}</h3>
	  <p>A continuación, se anexan sus credenciales para el acceso al sistema:</p><br>
	  <p><b>Email: </b> {{ $email }}</p>
      <p><b>Contraseña: </b> {{ $password }}</p>
	  <a href="http://sigen.herokuapp.com/login">Acceder</a>
	</div>
</body>
</html>