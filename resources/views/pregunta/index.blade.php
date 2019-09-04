@extends("../layouts.plantilla")

@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>

<!--Css para Datatable-->
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section("ol_breadcrumb")
<li class="breadcrumb-item">
	<a href="{{ route('areas.index',[$area->materia->id_cat_mat]) }}">
        Áreas
    </a>
</li>
<li class="breadcrumb-item">
	<a>
        {{ $area->titulo }}
    </a>
</li>
<li class="breadcrumb-item">
	@if(Request::get('id_gpo')==1)
    	Listado de grupos emparejamiento
    @else
    	Listado de preguntas
    @endif
</li>
 <div class="col-5 text-right">
    <a class="btn" href="">
        <span class="icon-add text-primary">
        </span>
    </a>
    <strong>
    @if(Request::get('id_gpo')==1)
    	Agregar Grupo
    @else
    	Agregar Pregunta
    @endif
	</strong>
</div>

@endsection
@section('main')
<div id="message-success" class="alert alert-success alert-dismissible fade show text-center" role="alert" hidden>
  <strong id="text-success">Exito</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div id="message-error" class="alert alert-danger alert-dismissible fade show text-center" role="alert" hidden>
  <strong id="text-error">Error</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<!--DATA TABLE-->
    <div class="container mt-3 mb-3">
        <table class="table table-striped table-bordered" id="areas" style="width:100%">
            <thead>
                <tr>
                	<th>
                        #
                    </th>
                    <th>
                    @if(Request::get('id_gpo')==1)
    				Descripcion grupo emparejamiento
    				@else
    				Pregunta
    				@endif
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
        </table>
    </div>
<!-- Modal-->
<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal1" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="h4 text-danger icon-information-solid mr-2 mt-1"></span>
                <h5 class="modal-title" id="exampleModalLabel">
                  Eliminar Area
                </h5>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>
            <form id="form-elim" method="DELETE">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-12 col-form-label" for="inputPassword">
                            <strong>¿Esta seguro que desea eliminar la pregunta?</strong>
                        </label>
                        <input hidden="" id="id_preg_eli" name="id" type="number"/>
                        <p class="ml-3 mr-3 mb-0 text-justify">Se eliminaran las opciones asociadas con la pregunta, al ejecutar esta accion. </p>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" id="salir_eli" type="button">
                        Salir
                    </button>
                    <input class="btn btn-danger" id="eliminar" type="button" value="Eliminar"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!--Scripts para datatables con Laravel-->
@section("js")
<script src="https://code.jquery.com/jquery-3.3.1.js">
</script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js">
</script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js">
</script>

<script type="text/javascript">
	$(document).ready(function() {
    $(function() {
        var table = $('#areas').DataTable({
            "serverSide": true,
            "ajax": window.location.href,
            "columns": [
                @if(Request::get('id_gpo') == 1) 
                {
                    data: 'id'
                },
                {
                    data: 'descripcion_grupo_emp'
                }, {
                    data: 'actions',
                    orderable: false,
                    searchable: false
                },
                @else {
                    data: 'id'
                }, {
                    data: 'pregunta'
                }, {
                    data: 'actions',
                    orderable: false,
                    searchable: false
                },
                @endif
            ],
            "language": {
                "info": "Mostrando Pagina _PAGE_ de _PAGES_",
                "search": "Buscar:",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior",
                },
                "lengthMenu": 'Mostrar <select class="browser-default custom-select">' + '<option value="5">5</option>' + '<option value="10">10</option>' + '<option value="25">25</option>' + '<option value="50">50</option>' + '<option value="-1">TODOS</option>' + '</select> registros',
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "emptyTable": "No hay datos",
                "zeroRecords": "Lo sentimos, no hay coincidencias.",
                "infoEmpty": "",
                "infoFiltered": "",
            },
            //Centrar datos dentro de una columna target=3
            columnDefs: [{
                'className': 'text-center',
                'targets': 2
            }, {
                "searchable": false,
                "orderable": false,
                "targets": 0
            }]
        });
        table.on('order.dt search.dt', function() {
            table.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        var id_preg ="";
        var id_gpo="";
        $('body').on('click', '.btn-eliminar', function() {
            id_preg = $(this).data('id');
            id_gpo=$(this).data('gpo');
            $.get('/area/'+id_gpo+'/pregunta/'+ id_preg).done(function(data) {
                $("#id_preg_eli").val(data.id);
            }).fail(function() {
                console.log("Error");
            });
        });

        //Peticion para eliminar
        $("#eliminar").click(function() {
            $(this).attr("disabled", true);
            $.ajax({
                url: '/area/'+id_gpo+'/pregunta/'+ id_preg,
                type: "DELETE",
                data: $("#form-elim").serialize(),
                dataType: "json"
            }).done(function(datos) {
                $("#salir_eli").click();
                $("#eliminar").removeAttr("disabled");
                table.draw();
                //Mostrando mensaje de exito
                if (datos.type == 2) {
                    exito(datos);
                } else {
                    $("#message-error").removeAttr("hidden");
                    $("#text-error").text(datos.error);
                    setTimeout(function() {
                        $("#message-error").attr('hidden', true);
                    }, 4000);
                    //Para mover al inicio de la pagina el control
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                }
            }).fail(function(xhr, status, e) {
                console.log(e);
            });
        });

    });
});
</script>
@endsection
