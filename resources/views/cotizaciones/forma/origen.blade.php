<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1"> Nombre <span class="tx-danger">*</span></span>
	</div>
	{!! Form::select('sucursal'
		, $sucursal
		,'MEX'
		,['class' 		=> 'form-control'
			,'placeholder'	=> 'Seleccionar'
			,'required'	=> ''
			,'name'		=> 'sucursal'
			,'id'		=> 'sucursal'
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
			,'readonly' =>	'true'
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
