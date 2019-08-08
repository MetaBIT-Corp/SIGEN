@extends("../layouts.plantilla")
@section("body")

@section("ol_breadcrumb")
    <li class="breadcrumb-item"><a href="#">Materia</a></li>
    <li class="breadcrumb-item"><a href="listado_estudiante">Listado de estudiantes</a></li>
    <li class="breadcrumb-item">Detalle de estudiante</li>
@endsection

@section("main")

@if (session('exito'))
  <div class="alert alert-success">
    <ul>
      <h4 class="text-center">{{session('exito')}}</h4>
    </ul>
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger">
    <ul>
      <h4 class="text-center">{{session('error')}}</h4>
    </ul>
  </div>
@endif

<div id="wrapper" style="width:800px; margin: 0 auto;">
    <div id="content-wrapper">
      <div class="container-fluid">
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Evaluación  |  Detalle de la evaluación </div>
          <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" style="width:700px; margin: 0 auto;">
                  <tr>
                    <th scope="row">Nombre</th>
                    <th scope="row" style="width:250px;">Opciones</th>
                  </tr>

                  @foreach($clave_area as $ca)
                  <tr>
                      <td>{{ $ca->id }}</td>
                      <td>
                        <button
                          data-id-clave-area="{{ $ca->id }}"
                          class="btn btn-primary">Asignar
                        </button>
                      </td>
                  </tr>

                  @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content-wrapper -->
  </div>

  <!-- Modal -->
<div class="modal fade" id="asignarPreguntasClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Asiganar preguntas a la clave</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="clave_area" value="" id="id_clave_area">
        <div class="modal-body" id="asignar-preguntas">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
@endsection

@section('js')

<script>
  $('[data-id-clave-area]').click(function(){
    var id_clave_area = $(this).data('id-clave-area');

    $('#id_clave_area').attr("value", id_clave_area);

    //AJAX
    $.get('/api/area/'+id_clave_area+'/preguntas', function(data){
      var html_modal ='';

      if(data.length>0){      
        for (var i = 0; i < data.length; ++i) {
         html_modal += i+1+'.  <label><input type="checkbox" id="cbox1" name="preguntas[]" value="'+data[i].id+'"> '+data[i].pregunta+'</label>';
         $('#modalCenterTitle').html('Seleccione las preguntas del area <em>'+data[0].titulo+'</em> que desea asignar');

         if(i<data.length-1)
          html_modal += '<hr>'; 
      }
      }else{
        html_modal = '<strong><h3>Esta área no contiene preguntas</h3></strong>'
      }

      //Asignando el resultado de la consulta al body del modal
    $('#asignar-preguntas').html(html_modal);

    });

    //Mostrar el modal
    $('#asignarPreguntasClaveArea').modal('show');
  });
</script>
@endsection