
<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1"> Pais D<span class="tx-danger">*</span></span>
	</div>
	{!! Form::select('pais_d', array(
	    'MEX' 	=> 'Mexico'
	    )
		,'MEX'
		,['class' 		=> 'form-control'
			,'placeholder'	=> 'Seleccionar'
			,'required'	=> ''
			,'name'		=> 'pais_d'
			,'id'		=> 'pais_d'
		]);
	!!}
</div>
<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1"> CP <span class="tx-danger">*</span></span>
	</div>
	{!! Form::text('cp_d', null,
		['class' 		=> 'form-control'
			,'id'		=> 'cp_d'
			,'placeholder'	=> 'Codigo Postal'
			,'required'	=> ''
			,'pattern'	=> '\d{5}'
		])
	!!}
	
</div>
<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1"> Piezas <span class="tx-danger">*</span></span>
	</div>
	{!! Form::text('piezas', null,
		['class' 		=> 'form-control'
			,'data-parsley-type' => 'number'
			,'data-parsley-type' =>'integer'
			,'min'	=>	'1'
			,'placeholder'	=> 'Peso aproximado'
			,'id'		=> 'piezas'
			,'required'	=> ''
		])
	!!}
	
</div>
