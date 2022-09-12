
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text" id="basic-addon1"> Pais <span class="tx-danger">*</span></span>
		</div>
		{!! Form::select('pais', array(
		    'MEX' 	=> 'Mexico'
		    )
			,'MEX'
			,['class' 		=> 'form-control'
				,'placeholder'	=> 'Seleccionar'
				,'required'	=> ''
				,'name'		=> 'pais'
				,'id'		=> 'pais'
			]);
		!!}
	</div>
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text" id="basic-addon1"> CP <span class="tx-danger">*</span></span>
		</div>
		{!! Form::text('cp', null,
			['class' 		=> 'form-control'
				,'id'		=> 'cp'
				,'placeholder'	=> 'Codigo Postal'
				,'required'	=> ''
				,'pattern'	=> '\d{5}'
			])
		!!}
		
	</div>
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text" id="basic-addon1"> Peso <span class="tx-danger">*</span></span>
		</div>
		{!! Form::text('peso', null,
			['class' 		=> 'form-control',
				'data-parsley-type' => 'number'
				,'min'	=>	'0.1'
				,'placeholder'	=> 'Peso aproximado'
				,'required'	=> ''
			])
		!!}
		
	</div>

