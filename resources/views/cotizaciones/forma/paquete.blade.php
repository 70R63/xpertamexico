<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1"> Peso (Kg.) <span class="tx-danger">*</span></span>
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

<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text" id="basic-addon1">Dimensiones (LxWxH) <span class="tx-danger">*</span></span>
		</div>
		{!! Form::text('largo[]', null,
			['class' 		=> 'form-control'
			,'id'			=> 'largo'
			,'placeholder'	=> 'Largo '
			
			])
		!!}

		{!! Form::text('ancho[]', null,
			['class' 		=> 'form-control'
			,'id'			=> 'ancho'
			,'placeholder'	=> 'Ancho  '
			
			])
		!!}

		{!! Form::text('alto[]', null,
			['class' 		=> 'form-control'
			,'id'			=> 'alto'
			,'placeholder'	=> 'Alto '
			
			])
		!!}
	</div>