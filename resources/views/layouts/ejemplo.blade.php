@extends("layouts.plantilla")

@section("a_perfil")
<?php echo $nombre?>
@endsection

@section("ul_sidebar")
	<li class="nav-item first-one">
		<a class="nav-link active" href="#">
			<span data-feather="home"></span>
			Dashboard
			<span class="arrow-sidebar" data-feather="chevron-right"></span>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#">
			<span data-feather="file"></span>
			Orders
			<span class="arrow-sidebar" data-feather="chevron-right"></span>
		</a>
	</li>
@endsection

@section("ol_breadcrumb")
	<li class="breadcrumb-item"><a href="#">Home</a></li>
	<li class="breadcrumb-item"><a href="#">Library</a></li>
@endsection

@section("main")
	<p>Este es un ejemplo de Blade</p>
	<table>
	</table>
@endsection
