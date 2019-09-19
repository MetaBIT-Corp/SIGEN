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
@if(Request::get('id_gpo')==0)
 <div class="col-5 text-right">
    <a class="btn" href="javascript:void(0)" data-target="#modal" data-toggle="modal" id="add_pregunta">
        <span class="icon-add text-primary">
        </span>
    </a>
    <strong>
        Agregar Pregunta
    </strong>
</div>
    @else
<div class="col-5 text-right">
    <a class="btn" href="" id="add_gpo">
        <span class="icon-add text-primary">
        </span>
    </a>
    <strong>
        Agregar Grupo
    </strong>
</div>
@endif


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
<!-- Modal -->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal">
                        Ejemplo
                    </h5>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">
                            ×
                        </span>
                    </button>
                </div>

                <form id="form-edit" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-danger" hidden="" id="validacion" role="alert">
                            Campo requerido para continuar.
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="inputPassword">
                                Pregunta
                            </label>
                            <div class="col-sm-8">
                                <input type="hidden" id="pregunta_id" name="pregunta_id"/>
                                <input class="form-control" id="pregunta" name="pregunta" placeholder="Titulo" required="" type="text"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" id="salir" type="button"
                        onclick="$('#validacion').attr('hidden',true);">
                            Salir
                        </button>
                        <input class="btn btn-primary" id="modificar" type="button" value="Modificar"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Modal2-->
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

        function exito(datos) {
            $("#message-success").removeAttr("hidden");
            $("#text-success").text(datos.success);
            setTimeout(function() {
                $("#message-success").attr('hidden', true);
            }, 4000);
            //Para mover al inicio de la pagina el control
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
        }

        var id_preg ="";
        var id_gpo="";

        //Evento para eliminar
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

        //Evento para crear una pregunta
         $('body').on('click', '#add_pregunta', function() {
            $('#form-edit').trigger("reset");
            $("#modificar").val('Crear');
            $("#title-modal").html("Crear Pregunta");
            $("#pregunta_id").val("{{ $area->id }}");
            $("#pregunta").val("");
        });

        //Evento para crear una edicion de la pregunta
        $('body').on('click', '.btn-editar', function() {
            $('#form-edit').trigger("reset");
            id_preg = $(this).data('id');
            id_gpo=$(this).data('gpo');
            $.get('/area/'+ id_gpo+'/pregunta/'+id_preg).done(function(data) {
                $("#title-modal").html("Editar Pregunta");
                $("#pregunta_id").val(data.id);
                $("#pregunta").val(data.pregunta);
                $('#modificar').val('Modificar');
            }).fail(function() {
                console.log("Error");
            });
        });
        //Peticion para modificar
        $("#modificar").click(function() {
            if ($("#pregunta").val().length > 0) {
                $(this).attr("disabled", true);
                if($(this).val()=="Modificar"){
                    $.ajax({
                        url: '/area/'+ id_gpo+'/pregunta/'+id_preg,
                        type: "POST",
                        data: $("#form-edit").serialize(),
                        dataType: "json"
                    }).done(function(datos) {
                        $("#salir").click();
                        $("#modificar").removeAttr("disabled");
                        $("#modificar").attr('data-type','create');
                        table.draw();
                        //Mostrando mensaje de exito
                        exito(datos);
                    }).fail(function(xhr, status, e) {
                        console.log(e);
                    });
                }else{
                    $.ajax({
                        url: '/area/1/pregunta/',
                        type: "POST",
                        data: $("#form-edit").serialize(),
                        dataType: "json"
                    }).done(function(datos) {
                        $("#salir").click();
                        $("#modificar").removeAttr("disabled");
                        $("#modificar").attr('data-type','update');
                        table.draw();
                        //Mostrando mensaje de exito
                        exito(datos);
                    }).fail(function(xhr, status, e) {
                        console.log(e);
                    });
                }
            } else {
                $("#validacion").removeAttr("hidden");
                $("#modificar").removeAttr("disabled");
            }
        });
    });
});
</script>
@endsection
