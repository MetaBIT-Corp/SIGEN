<div class="modal" id="edit-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Opciones de Pregunta</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{ route('actualizar-opcion-revision')}}" method="POST">

				<div class="modal-body pt-2 mt-0" style="max-height: calc(100vh - 200px); overflow-y: auto;">

					<div class="form-group d-none">
						<label class="col-form-label" for="id-pregunta">ID Pregunta:</label>
						<input type="text" id="id-pregunta" name="id_pregunta" class="form-control-plaintext" readonly="">
						<label class="col-form-label" for="tipo-pregunta">Tipo Pregunta:</label>
						<input type="text" id="tipo-pregunta" name="tipo_pregunta" class="form-control-plaintext" readonly="">
						<label class="col-form-label" for="cantidad-opciones">Cantidad Opciones:</label>
						<input type="text" id="cantidad-opciones" name="cantidad_opciones" class="form-control-plaintext" readonly="">
						<label class="col-form-label" for="cantidad-nuevas">Cantidad Nuevas:</label>
						<input type="text" id="cantidad-nuevas" name="cantidad_nuevas" class="form-control-plaintext" readonly="" value="0">
					</div>

					<label for="pregunta-edit" class="form-control-label"><strong>Pregunta:</strong></label>
					<p type="text" id="pregunta-edit" class="form-control-plaintext"></p>

					<div class="row mt-3 px-1 no-rc">
						<div class="col-sm-1"><strong>N°</strong></div>
						<div class="col-sm-8"><strong>Opciones</strong></div>
						<div class="col-sm-3 text-center"><strong>Correcta</strong></div>
					</div>
					<div class="row mt-3 px-1 rc">
						<div class="col-sm-1"><strong>N°</strong></div>
						<div class="col-sm-11"><strong>Opciones</strong></div>
					</div>
					
					<hr>
					
					<div id="opciones-div" class="px-1 mt-2">
					</div>

					<div id="opciones-nuevas-div" class="px-1 mt-2">						
					</div>

					<div class="rc text-right mt-3 mb-2">
						<button type="button" id="agregar-opcion" class="btn btn-outline-dark">Agregar Opcion</button>
					</div>

					<div class="alert alert-danger m-3" id="alerta-div">
						<ul id="alerta-ul">
						</ul>
					</div>

				</div>

				<div class="modal-footer">
					{{ csrf_field() }}
					<button type="submit" class="btn btn-primary">Guardar Cambios</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>

			</form>
		</div>
	</div>
</div>