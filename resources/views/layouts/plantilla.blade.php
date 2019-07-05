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

         @yield("css")
        
    </head>

    <body id="page-top" style="background: white;">
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
                    <a href="" class="navbar-brand"><i class="fas fa-user"></i> 
                        @yield("a_perfil")</a>
                    <a class="navbar-brand" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
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
                                <li class="nav-item first-one">
                                    <a class="nav-link active" href="/">
                                        <span data-feather="home"></span>
                                        SIGEN
                                        <span class="arrow-sidebar" data-feather="chevron-right"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="col-md-10 position-relative" style="padding: 0">
                        @yield("main_footer")
                        <main role="main" class="ml-md-0 pt-2 px-5">
                            
                                <ol class="breadcrumb">
                                    @yield("ol_breadcrumb")
                                </ol>

                                @yield("main")
                                @if(!auth()->check() && Request::route()->getName() != 'login')
                                    @yield("encuestas")
                                    <div class = "alert alert-info">
                                        <h1 class="text-center">No hay encuestas de propósito general</h1>
                                    </div>
                                @endif
                            
                            
                        </main>
                        <footer class="footer position-absolute w-100">

                            @yield("footer")
                            <div class="container">
                                <div class="copyright">
                                    <span>Derechos Reservados © SIGPAD/SIGEN 2019</span>
                                </div>
                            </div>                            
                        </footer>
                        <a class="scroll-to-top rounded position-fixed" href="#page-top" style="bottom: 1rem; right: 1rem;">
                            <i class="fas fa-angle-up"></i>
                        </a>
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
    <script>
      feather.replace()
    </script>
  </body>
</html>