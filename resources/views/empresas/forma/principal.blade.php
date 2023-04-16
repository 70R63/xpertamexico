<div class="col-sm-12 ">
    <div class="card custom-card">
        <div class="card-body">
        	<div class="card-item">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">CLIENTE
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

					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">R.F.C<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::text('rfc'
						, null
						,['class' 		=> 'form-control'

							,'id'		=> 'rfc'
							,'required'	=>	'true'
							,'placeholder'=>'Ingrese el rfc en mayusculas o precione RFC para asignar XAXX010101000'
							,'pattern'	=> '^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[A-Z\d]{3})?$'

						])
					!!}
					<a href="" class="text-nowrap tx-20" id="asignarRcPG" >
						<i title="RFC PUBLICO GENERAL" class="">rfc </i>
					</a>
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">EMAIL
						</span>
					</div>

					{!! Form::text('email'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'email'
						])
					!!}
				
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">TELEFONO
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::text('telefono'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'telefono'
							,'required'	=>	'true'
						])
					!!}

					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">CLASIFICACIÓN
							<span class="tx-danger">*</span>
						</span>
					</div>

					{!! Form::select('clasificacion'
						, Config('general.cliente.clasificacion')
						,null
						,['class' 		=> 'form-control'
							,'placeholder'	=> 'Seleccionar'
							,'required'	=> 'true'
							,'id'		=> 'clasificacion'
						]);
					!!}
				</div>

				<div class="row row-sm">
					<div class="col-lg-3">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									DESCUENTO %<span class="tx-danger">*</span>
								</span>
							</div>

							{!! Form::text('descuento'
								, null
								,['class' 		=> 'form-control'
									,'id'		=> 'descuento'
								])
							!!}
						</div>
					</div>
					<div class="col-lg-3">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									FSC %<span class="tx-danger">*</span>
								</span>
							</div>

							{!! Form::text('fsc'
								, null
								,['class' 		=> 'form-control'
									,'id'		=> 'fsc'
									,'required'	=>	'true'
								])
							!!}
						</div>
					</div>
					<div class="col-lg-3">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									ÁREA EXTENDIDA<span class="tx-danger">*</span> $
								</span>
							</div>

							{!! Form::text('area_extendida'
								, null
								,['class' 		=> 'form-control'
									,'id'		=> 'area_extendida'
									,'required'	=>	'true'
								])
							!!}
						</div>
					</div>

					<div class="col-lg-3">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									MULTIPIEZA<span class="tx-danger">*</span>$
								</span>
							</div>

							{!! Form::text('precio_mulitpieza'
								, null
								,['class' 		=> 'form-control'
									,'id'		=> 'precio_mulitpieza'
									,'required'	=>	'true'
								])
							!!}
						</div>
					</div>

					<div class="col-lg-3">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									PREMIUM 10:30 - $
								</span>
							</div>
							{!! Form::text('premium10'
								, null
								,['class' 		=> 'form-control'
									,'id'		=> 'premium10'
									,'required'	=>	'true'
								])
							!!}
						</div>
					</div>

					<div class="col-lg-3">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									PREMIUM 12:00 - $
								</span>
							</div>
							{!! Form::text('premium12'
								, null
								,['class' 		=> 'form-control'
									,'id'		=> 'premium12'
									,'required'	=>	'true'
								])
							!!}
						</div>
					</div>
				</div>
				<!-- fin <div class="row row-sm"> -->
			</div>
			<!-- fin class="card-item" -->
		</div>
		<!-- fin class="card-body" -->
	</div>
	<!-- fin class="card custom-card" -->
</div>