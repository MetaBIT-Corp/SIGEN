<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    <head>
        @yield("head")
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SIGEN</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Icons -->
        <link rel="icon" type="image/png" href="{{asset('img/logo.png')}}" />
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js" integrity="sha384-0pzryjIRos8mFBWMzSSZApWtPl/5++eIfzYmTgBBmXYdhvxPc+XcFEk+zJwDgWbP" crossorigin="anonymous"></script>

        <!-- Styles -->
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/estilo.css')}}">

        <!-- Scripts-->
        <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous">
        </script>

         @yield("css")
        
    </head>

    <body id="page-top" style="background: white;" onload="deshabilitaRetroceso()">
        @yield("body")

        <nav class="navbar navbar-dark bg-dark sticky-top flex-md-nowrap p-0">
            @yield("navbar")

            <div class="my-2 my-lg-2">
                <a class="logo-text" href="/">
                    <img src="{{asset('img/logo.png')}}" width="40" height="40" class="d-inline-block align-top logo" alt="">
                    SIGEN
                </a>
            </div>
            
            @if(auth()->check())
                <div class="my-2 my-lg-2">

                    <a href="" class="navbar-brand mr-4">
                        <strong>Ciclo</strong>&nbsp;{{$ciclo_activo->num_ciclo}}&nbsp;|&nbsp;<strong>Año</strong>&nbsp;{{$ciclo_activo->anio}}
                    </a>

                    <a href="" class="navbar-brand mr-2"><i class="fas fa-user"></i>&nbsp;&nbsp;
                        @yield("a_perfil"){{auth()->user()->name}} | 
                        @switch(auth()->user()->role)
                            @case(0)
                                Administrador
                            @break
                            @case(1)
                                Docente
                            @break
                            @case(2)
                                Estudiante
                            @break
                        @endswitch
                    </a>&nbsp;&nbsp;

                    <a class="navbar-brand" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>&nbsp;
                    {{ __('Salir') }}</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                </div>
            @else
                <a href="/login" class="navbar-brand"><i class="fas fa-sign-in-alt"></i> Ingresar</a>
            @endif
            
        </nav>

        <div id="content-wrapper">

            <div class="container-fluid">
                <div class="row sidebar-row">
                    <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
                        @yield("sidebar")

                        <div class="sidebar-sticky">
                            <ul class="nav flex-column">
                                @yield("ul_sidebar")
                                 @if(auth()->check())
                                <li class="nav-item first-one">
                                    <a class="nav-link active" href="{{route('materias')}}">
                                        <span data-feather="home"></span>
                                        SIGEN
                                        <span class="arrow-sidebar" data-feather="chevron-right"></span>
                                    </a>
                                </li>
                                @endif
                                
                                 @if(auth()->check())
                                 <li class="nav-item first-one">
                                    <a class="nav-link " href="{{route('encuestas')}}">
                                        <span data-feather="home"></span>
                                        Encuestas Disponibles
                                        <span class="arrow-sidebar" data-feather="chevron-right"></span>
                                    </a>
                                </li>
                                 @if(auth()->user()->IsTeacher)
                                <li class="nav-item first-one">
                                    <a class="nav-link " href="
                                    {{ URL::signedRoute('listado_encuesta') }}">
                                        <span data-feather=""></span>
                                        Listado Encuesta
                                        <span class="arrow-sidebar" data-feather="chevron-right"></span>
                                    </a>
                                </li>
                                <li class="nav-item first-one">
                                    <a class="nav-link " href="{{route('areas_encuestas')}}">
                                        <span data-feather="home"></span>
                                        Area - Encuestas
                                        <span class="arrow-sidebar" data-feather="chevron-right"></span>
                                    </a>
                                </li>
                                @endif
                                @endif
                            </ul>
                        </div>
                    </nav>
                    <div class="col-md-10 position-relative" style="padding: 0">
                        @yield("main_footer")
                        <main role="main" class="ml-md-0 pt-2 px-5 mb-5 pb-3">

                                <ol class="breadcrumb">
                                    @yield("ol_breadcrumb")
                                </ol>

                                @yield("main")
                                <!-- @if(!auth()->check() && Request::route()->getName() != 'login')
                                    @yield("encuestas")
                                    <div class="alert alert-info">
                                        <h1 class="text-center">No hay encuestas de propósito general</h1>
                                    </div>
                                @endif -->
                            
                            
                        </main>
                        
                        <footer class="footer position-absolute w-100">

                            @yield("footer")
                            <div class="container">
                                <div class="copyright">
                                    <span>Derechos Reservados © SIGPAD/SIGEN 2019</span>
                                </div>
                            </div>                            
                        </footer>
                    </div>
                </div>
            </div>
        </div>
        
        

    @yield("js")

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
          <!--feather.replace()-->
  </body>
</html>