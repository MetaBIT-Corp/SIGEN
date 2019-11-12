<!-- /#wrapper -->
<div id="wrapper">
  <div id="content-wrapper">
    <div class="container-fluid">
      <!-- DataTables Example -->
      <div class="card mb-3">
        <div class="card-header">
          <div class="row">
            <div class="col-8">
              <i class="fas fa-table"></i>
              Listado áreas asignadas
            </div>
            @if($visibilidad != 1)
            <div class="col-4" style="text-align: right;">
              <strong class="mb-3">Asignar Área</strong>

              @if($encuesta)
                <button class="btn" data-id-clave="{{$clave->id}}" data-toggle="modal" data-target="#areasEncuestaModal" id="btnEncuestasAreas" title="Asignar Área a Encuesta">
                  <span class="icon-add text-primary">
                  </span>
                </button>
              @else
                <button class="btn" data-id-turno="{{$turno->id}}" data-id-clave="{{$clave->id}}" data-peso-turno="{{$peso_turno}}" data-toggle="modal" data-target="#areasModal" onclick="$('#areasModal').modal();" title="Asignar Área a Turno">
                  <span class="icon-add text-primary">
                  </span>
                </button>
              @endif
              
            </div>
            @endif
          </div>
        </div>

        <?php
          $peso_total=0;
          $total_preguntas = 0;
          $areas_sin_preguntas = 0;
        ?>
        
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Modalidad</th>
                  <th>Cantidad de preguntas</th>
                  @if(!$encuesta)
                    <th>Peso</th>
                  @endif
                  <th>Opciones</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Modalidad</th>
                  <th>Cantidad de preguntas</th>
                  @if(!$encuesta)
                    <th>Peso</th>
                  @endif
                  <th>Opciones</th>
                </tr>
              </tfoot>
              <tbody>
                @if(count($claves[0]->clave_areas) > 0 )
                @foreach($claves[0]->clave_areas as $clave_area)
                <tr>
                  <input type="hidden" value="{{ $clave_area->id}}" id="id_clave_area_edit">
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    @if($clave_area->aleatorio)
                      <i class="icon-dice" title="Aleatorio">&nbsp;&nbsp;</i> 
                    @else
                      <i class="icon-hand-paper-o" title="Manual">&nbsp;&nbsp;</i> 
                    @endif
                    {{ $clave_area->area->tipo_item->nombre_tipo_item }}
                  </td>
                  <!--El atributo cantidad_preguntas es un campo calculado en el modelo Clave_Area apartado de accessors-->
                  @if($clave_area->cantidad_preguntas!=0)
                    <td id="id_cantidad" class="text-center">{{ $clave_area->cantidad_preguntas }}</td>
                    <?php $total_preguntas += $clave_area->cantidad_preguntas ?>
                  @else
                    <td id="id_cantidad" class="text-center">-</td>
                    <?php $areas_sin_preguntas++ ?>
                  @endif
                  @if(!$encuesta)
                    <td id="id_peso">{{ $clave_area->peso }}</td>
                  @endif
                  <?php $peso_total += $clave_area->peso ?>
                  <td>
                    @if($visibilidad != 1)
                      <button class="icon-delete btn btn-sm btn-danger" href="#" title="Eliminar Área" data-eliminar-ca="{{ $clave_area->id }}"></button>
                      <button class="icon-edit btn btn-sm btn-primary" href="#" title="Editar Área" data-editar-ca="{{ $clave_area->id }}" data-aleatorio="{{ $clave_area->aleatorio }}"></button>
                    @endif
                    @if($clave_area->aleatorio)
                      <a class="icon-list btn btn-sm btn-success"
                        @if($clave_area->area->tipo_item_id==3)
                          href="{{ URL::signedRoute('getPreguntas', ['id_area' => $clave_area->area->id ,'id_gpo'=>true]) }}" 
                          title="Ver grupos de esta área"
                        @else 
                          href="{{ URL::signedRoute('getPreguntas', ['id_area' => $clave_area->area->id ,'id_gpo'=>false]) }}" 
                          title="Ver preguntas de esta área"
                        @endif>
                      </a>
                    @else
                      @if($clave_area->area->tipo_item_id==3)
                        <button class="icon-list btn btn-sm btn-success" href="#" title="Ver preguntas agregadas" data-preguntas-emp="{{ $clave_area->id }}"></button>
                        @if($visibilidad != 1)
                            <button class="icon-add-solid btn btn-sm btn-info" title="Agregar preguntas" data-id-clave-area-emp="{{ $clave_area->id }}"></button>
                        @endif
                      @else
                        @if($visibilidad != 1)  
                          <button class="icon-list btn btn-sm btn-success" href="#" title="Ver preguntas agregadas" data-preguntas="{{ $clave_area->id }}"></button>
                        @endif
                        <button class="icon-add-solid btn btn-sm btn-info" title="Agregar preguntas" data-id-clave-area="{{ $clave_area->id }}"></button>
                      @endif
                    @endif
                  </td>
                </tr>
                 @endforeach
                 @else
                  <tr>
                    <td colspan="5">No se encuentran resultados disponibles</td>
                </tr>
                 @endif
              </tbody>
            </table>

            <div class="d-none">
              <input type="text" id="peso_total" value="{{$peso_total}}">
              <input type="text" id="total_preguntas" value="{{$total_preguntas}}">
              <input type="text" id="visibilidad" value="{{$visibilidad}}">
              <input type="text" id="areas_sin_preguntas" value="{{$areas_sin_preguntas}}">
            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->
</div>

@if($encuesta)

  @include('encuesta.areasclave')

@else

  @include('turno.areasclave')

@endif



<!-- Modal agregar preguntas-->
<div class="modal fade" id="asignarPreguntasClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Asignar preguntas a la clave</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('agregar_clave_area') }}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="clave_area" value="" id="id_clave_area_add">
          <input type="hidden" name="modalidad" value="" id="id_clave_area_add_emp">
          <div id="asignar-preguntas">
  
          </div>
      </div>
        <div> 
          <hr>
          <div class="d-inline float-left mb-3 ml-4">
            <label><input type="checkbox" id="todas">&nbsp;Seleccionar todas</label>
          </div>
          <div class="d-inline float-right mb-3 mr-3">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

  <!-- Modal listar preguntas-->
<div class="modal fade" id="listarPreguntasClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="listarModalCenterTitle">Preguntas asignadas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="listar-preguntas">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Aceptar</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal editar Asignación de área a clave-->
<div class="modal fade" id="editarClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarModalCenterTitle">Editar asignación de área a clave</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('editar_clave_area')}}" method="POST">
        {{ csrf_field() }}
        <div class="modal-body" id="editar-preguntas">
          <div class="form-group">
            <h6 id="val_peso"></h6>
          </div>
          <div class="form-group">
            <h6 id="val_asignable"></h6>
          </div>
          <hr>
          <input type="hidden" value="" id="val_peso_actual" name="peso_total">
          <input type="hidden" value="" id="id_ca" name="id_clave_area">
          <div class="form-group">
            <label for="cantidad_preguntas_id" id="msj_cant_preg">Cantidad de preguntas*</label>
            <input type="number"  min="1" class="form-control" id="cantidad_preguntas_id" name="numero_preguntas">
          </div>
          <div class="form-group">
            <label for="peso_ca_id">Peso del área*</label>
            <input type="number" min="0" max="100" class="form-control" id="peso_ca_id" name="peso">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal elimanr Asignación de área a clave-->
<div class="modal fade" id="eliminarClaveArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarModalCenterTitle">Eliminar área</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" id="elimanr-preguntas">
          <h3><strong>¿Desea eliminar esta área de la clave?</strong></h3>
        </div>
        <div class="modal-footer">
          <form action="{{ route('eliminar_clave_area')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="" id="id_ca_eliminar" name="id_clave_area">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
    </div>
  </div>
</div>