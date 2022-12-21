
<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1"> Nombre<span class="tx-danger">*</span></span>
	</div>
	{!! Form::select('cliente'
		, $cliente
		,'MEX'
		,['class' 		=> 'form-control select2'
			,'placeholder'	=> 'Seleccionar'
			,'required'	=> ''
			,'name'		=> 'cliente'
			,'id'		=> 'cliente'
		]);
	!!}
</div>
<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1"> CP <span class="tx-danger">*</span></span>
	</div>
	{!! Form::text('cp_d', null,
		['class' 		=> 'form-control cotizacionManual'
			,'id'		=> 'cp_d'
			,'placeholder'	=> 'Codigo Postal'
			,'required'	=> ''
			,'readonly' =>	'true'
			,'pattern'	=> '\d{5}'
		])
	!!}
	
</div>