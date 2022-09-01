
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
			,'name'		=> 'pais'
			,'id'		=> 'pais'
		]);
	!!}
</div>
<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1"> CP <span class="tx-danger">*</span></span>
	</div>
	{!! Form::text('cp_d', null,
		['class' 		=> 'form-control'
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
	{!! Form::text('peso_d', null,
		['class' 		=> 'form-control'
		,'placeholder'	=> 'Peso aproximado'
		,'required'	=> ''
		])
	!!}
	
</div>

