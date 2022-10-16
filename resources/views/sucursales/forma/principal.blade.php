<div class="col-sm-12 ">
    <div class="card custom-card">
        <div class="card-body">
        	<div class="card-item">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">NOMBRE REMITENTE
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
						<span class="input-group-text" id="basic-addon1">C.P.
						</span>
					</div>

					{!! Form::text('cp'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'cp'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">COLONIA
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
						</span>
					</div>

					{!! Form::text('entidad_federativa'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'entidad_federativa'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">CELULAR
						</span>
					</div>

					{!! Form::text('celular'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'celular'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">TELEFONO
						</span>
					</div>

					{!! Form::text('telefono'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'telefono'
							,'required'	=>	'true'
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