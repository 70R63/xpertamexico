<div class="col-sm-12 ">
    <div class="card custom-card">
        <div class="card-body">
        	<div class="card-item">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">NOMBRE REMITENTE
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::text('nombre'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'nombre'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">CONTACTO
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::text('contacto'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'contacto'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">DIRECCIÓN
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::text('direccion'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'direccion'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">REFERENCIA
						</span>
					</div>

					{!! Form::text('direccion2'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'direccion2'
							,'placeholder'=>'Entre calles, color de fachada, locales cercanos'
							
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">C.P.
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::text('cp'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'cp'
							,'required'	=>	'true'
						])
					!!}
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">COLONIA
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::text('colonia'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'colonia'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">CIUDAD
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::text('ciudad'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'ciudad'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">ENTIDAD FEDERATIVA
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::select('entidad_federativa'
						, Config('general.entidad_federativa')
						,null
						,['class' 		=> 'form-control'
							,'placeholder'	=> 'Seleccionar'
							,'required'	=> 'true'
							,'name'		=> 'entidad_federativa'
							,'id'		=> 'entidad_federativa'
						]);
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">CELULAR
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::text('celular'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'celular'
							,'required'	=>	'true'
						])
					!!}
				
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">TELEFONO
						</span>
					</div>

					{!! Form::text('telefono'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'telefono'
							
						])
					!!}
				</div>

			</div>
			<!-- fin class="card-item" -->
		</div>
		<!-- fin class="card-body" -->
	</div>
	<!-- fin class="card custom-card" -->
</div>