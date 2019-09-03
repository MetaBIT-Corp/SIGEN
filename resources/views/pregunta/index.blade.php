@extends("../layouts.plantilla")

@section("css")
<link href="{{asset('icomoon/style.css')}}" rel="stylesheet"/>

<!--Css para Datatable-->
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section("ol_breadcrumb")
<li class="breadcrumb-item">
    <a href="{{ route('areas.index',[$area->materia->id_cat_mat]) }}">
        Areas
    </a>
</li>
<li class="breadcrumb-item">
    {{ $area->titulo }}
</li>
<li class="breadcrumb-item">
    @if(Request::get('id_gpo')==1)
    Listado de grupos emparejamiento
    @else
    Listado de preguntas
    @endif
</li>
@endsection
@section('main')
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
    });
});
</script>
@endsection
