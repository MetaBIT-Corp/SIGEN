@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(auth()->user()->is_admin)
                        <h1>Eres administrador</h1>
                    @elseif(auth()->user()->is_teacher)
                        <h1>Eres docente</h1>
                    @elseif(auth()->user()->is_student)
                        <h1>Eres estudiante</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
