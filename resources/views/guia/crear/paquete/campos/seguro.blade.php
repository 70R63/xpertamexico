<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1">Seguro </span>
	</div>
	<label class="custom-switch">
		<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" id="checkSeguro">
		<span class="custom-switch-indicator"></span>
		
	</label>
</div>
<!-- Inicio Class Seguro -->
<div class="seguro" style="display: none;" >
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text" id="basic-addon1"> Valor de Envio <span class="tx-danger">*</span></span>
		</div>
		{!! Form::text('valor_envio', null,
			['class' 		=> 'form-control'
			,'id'			=> 'valorEnvio'
			,'placeholder'	=> 'Valor factura Sin IVA'
			
			])
		!!}

		<div class="input-group-prepend">
			<span class="input-group-text" id="basic-addon1"> Costo del Seguro <span class="tx-danger">*</span></span>
		</div>
		{!! Form::text('envio_costo_seguro', null,
			['class' 		=> 'form-control'
			,'id'		=> 'costoSeguro'
			,'placeholder'	=> ''
			,'disabled'	=> ''
			])
		!!}
	</div>
</div>
<!-- Fin Class Seguro -->