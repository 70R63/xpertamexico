<!-- Campos para el sucursal con valor readonly -->
<div class="card custom-card">
    <div class="card-body">
    	<div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
            <label class="main-content-label mb-4">DETALLES DE LA SUCURSAL</label>
        </div>
    	<div class="card-item">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">SUCURSAL
					</span>
				</div>

				{!! Form::text('nombre'
					, $sucursal->nombre
					,['class' 		=> 'form-control'
						,'id'		=> 'nombre'
						,'required'	=>	'true'
						,'readonly' =>  'true'
					])
				!!}
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">CONTACTO
					</span>
				</div>

				{!! Form::text('contacto'
					, $sucursal->contacto
					,['class' 		=> 'form-control'
						,'id'		=> 'contacto'
						,'required'	=>	'true'
						,'readonly' =>  'true'
					])
				!!}
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">DIRECCIÓN
					</span>
				</div>

				{!! Form::text('direccion'
					, $sucursal->direccion
					,['class' 		=> 'form-control'
						,'id'		=> 'direccion'
						,'required'	=>	'true'
						,'readonly' =>  'true'
					])
				!!}
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">C.P.
					</span>
				</div>

				{!! Form::text('cp'
					, $sucursal->cp
					,['class' 		=> 'form-control'
						,'id'		=> 'cp'
						,'required'	=>	'true'
						,'readonly' =>  'true'
					])
				!!}
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">COLONIA
					</span>
				</div>

				{!! Form::text('colonia'
					, $sucursal->colonia
					,['class' 		=> 'form-control'
						,'id'		=> 'colonia'
						,'required'	=>	'true'
						,'readonly' =>  'true'
					])
				!!}
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">CIUDAD
					</span>
				</div>

				{!! Form::text('ciudad'
					, $sucursal->ciudad
					,['class' 		=> 'form-control'
						,'id'		=> 'ciudad'
						,'required'	=>	'true'
						,'readonly' =>  'true'
					])
				!!}
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">ENTIDAD FEDERATIVA
					</span>
				</div>

				{!! Form::text('entidad_federativa'
					, $sucursal->entidad_federativa
					,['class' 		=> 'form-control'
						,'id'		=> 'entidad_federativa'
						,'required'	=>	'true'
						,'readonly' =>  'true'
					])
				!!}
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">CELULAR
					</span>
				</div>

				{!! Form::text('celular'
					, $sucursal->celular
					,['class' 		=> 'form-control'
						,'id'		=> 'celular'
						,'required'	=>	'true'
						,'readonly' =>  'true'
					])
				!!}
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">TELEFONO
					</span>
				</div>

				{!! Form::text('telefono'
					, $sucursal->telefono
					,['class' 		=> 'form-control'
						,'id'		=> 'telefono'
						,'required'	=>	'true'
						,'readonly' =>  'true'
					])
				!!}
			</div>
		</div>
		<!-- fin class="card-item" -->
	</div>
	<!-- fin class="card-body" -->
</div>
<!-- fin class="card custom-card" -->