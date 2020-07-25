@extends("../layouts.plantilla")
@section("head")
@section("css")
	 <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" type="text/css" rel="stylesheet"> 
     <link rel="stylesheet" href="{{asset('icomoon/style.css')}}">
     <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
@endsection
@endsection
@section("ol_breadcrumb")
<div class="col-md-8 breadcrumb-item">
    <a class="mr-2">
        Listado de Ciclos
    </a>
</div>
<div class="col-md-3">
    <a class="btn" href="{{url('ciclo/create')}}">
        <span class="icon-add text-primary">
        </span>
    </a>
    <strong>
        Agregar Ciclo
    </strong>
</div>
@endsection
@section("main")

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
    <strong id="text-success">{{ session('success') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
    <strong id="text-success">{{ session('error') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

<!--DATA TABLE-->
<div class="container mt-4 mb-3">
    <div class="table-responsive mt-4">
    <table class="table table-bordered mt-4" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
               <th>Año</th>
               <th>Numero de Ciclo</th>
               <th class="text-center">Fecha Inicio</th>
               <th class="text-center">Fecha Fin</th>
               <th>Estado</th>
               <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ciclos as $ciclo)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$ciclo->anio}}</td>
                    <td class="text-center">{{$ciclo->num_ciclo}}</td>
                    <td class="text-center">{{date('d/m/Y', strtotime($ciclo->inicio_ciclo))}}</td>
                    <td class="text-center">{{date('d/m/Y', strtotime($ciclo->fin_ciclo))}}</td>
                    @if($ciclo->estado == 0)
                    <td class="text-center"><span class="badge badge-primary">Inactivo</span></td>
                    @else
                    <td class="text-center"><span class="badge badge-success">Activo</span></td>
                    @endif
                    <td class="text-center">
                        <a class="btn btn-option btn-sm" title="Listado de Materias Ciclo" href="{{ URL::signedRoute('materias_ciclo', ['id' => $ciclo->id_ciclo]) }}">
                            <span class="icon-file-text"></span>
                        </a>
                    @if($ciclo->estado == 1)
                        <a class="btn-editar btn btn-sm ml-2" title="Editar Ciclo" id="btn_editar" href="{{ URL::signedRoute('ciclo.edit', ['id' => $ciclo->id_ciclo]) }}">
                            <span class="icon-edit">
                            </span>
                        </a>
                        <a class="btn-eliminar btn ml-2 btn-sm" data-target="#modal1" data-toggle="modal" id="btn_eliminar" title="Eliminar Ciclo" data-id="{{$ciclo->id_ciclo}}">
                            <span class="icon-delete">
                            </span>
                        </a>
                    @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Año</th>
                <th>Numero de Periodo</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Estado</th>
                <th>Acciones</th>
             </tr>
        </tfoot>
    </table>
    </div>
</div>


<!-- Modal2-->
<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal1" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="h4 text-danger icon-information-solid mr-2 mt-1"></span>
                <h5 class="modal-title" id="exampleModalLabel">
                   Eliminar Ciclo
                </h5>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>
            <form method="POST" id="form-delete" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-12 col-form-label" for="inputPassword">
                            <strong>¿Esta seguro que desea eliminar el ciclo seleccionado? </strong>
                        </label>
                        <input hidden="" id="id_ciclo" name="id_ciclo" type="number"/>
                        <p class="ml-3 mr-3 mb-0 text-justify">Si este ya posee materias inscritas no se podra eliminar el ciclo.</p>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <input class="btn btn-danger" id="eliminar" type="submit" value="Eliminar"/>
                    <button class="btn btn-secondary" data-dismiss="modal" id="salir_eli" type="button">
                        Salir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('js')
  <!-- Bootstrap core JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js' )}}"></script>

    <!-- Core plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/jquery-easing/jquery.easing.min.js' )}}"></script>

    <!-- Page level plugin JavaScript-->
    <script type="text/javascript" src="{{asset('vendor/datatables/jquery.dataTables.js' )}}"></script>
    <script type="text/javascript" src="{{asset('vendor/datatables/dataTables.bootstrap4.js' )}}"></script>
      <script type="text/javascript" src="{{asset('js/demo/datatables-demo.js')}}"></script>
      <script>
        $('#dataTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "columnDefs": [
                { "width": "18%", "targets": 2 }
            ]
        });

        $('#btn_add').on('click',function(){
            $('#modal').modal('show');
        });
        $('.btn-eliminar').on('click',function(){
            $('#form-delete').attr('action','/ciclo/'+$(this).data('id'));
        });
      </script>
@endsection