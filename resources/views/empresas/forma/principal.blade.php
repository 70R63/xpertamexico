<div class="col-sm-12 ">
    <div class="card custom-card">
        <div class="card-body">
        	<div class="card-item">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">EMPRESA
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
						<span class="input-group-text" id="basic-addon1">EMAIL
						</span>
					</div>

					{!! Form::text('email'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'email'
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