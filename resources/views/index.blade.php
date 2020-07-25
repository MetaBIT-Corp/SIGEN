@extends("../layouts.plantilla")
@section("css")
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"
  />
@endsection
@section("body")
@section("main")
<img src="{{asset('img/wave.svg')}}" alt="wave" width="100%" class="animate__animated">
<div class="row">
  <div class="col-lg-7 my-auto ">
    <div class="header-content mx-auto animate__animated animate__bounceInLeft">
      <h1 class="mb-5">Sistema de Información para la Gestión de Encuestas y Evaluaciones (SIGEN)</h1>
      <a href="/login" class="btn btn-option btn-lg" style="text-decoration: none; color: #FFFFFF">Iniciar Sesión!</a>
    </div>
  </div>
  <div class="col-lg-5 my-auto">
    <div class="device-container">
      <div class="device-mockup iphone6_plus portrait white">
        <div class="device">
          <div class="screen animate__animated animate__bounceInRight">
            <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
            <img src="{{asset('img/sigen.svg')}}" class="img-fluid" alt="img" width="90%">
          </div>
          <div class="button">
            <!-- You can hook the "home button" to some JavaScript events or just remove it -->
          </div>
        </div>
      </div>
    </div>
  </div> 
</div>
@endsection
@endsection