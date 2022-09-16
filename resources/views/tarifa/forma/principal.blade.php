<div class="col-sm-12 ">
    <div class="card custom-card">
        <div class="card-body">
        	<div class="card-item">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">
							LTD 
							<span class="tx-danger">*</span>
						</span>
					</div>
					{!! Form::select('ltds_id'
						, $pluckLtd
						,$tarifa['ltds_id'] ?? '0'
						,['class' 		=> 'form-control'
							,'placeholder'	=> 'Seleccionar'
							,'required'	=> 'true'
						]);
					!!}
					   
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">
							SERVICIO <span class="tx-danger">*</span>
						</span>
					</div>
					
					{!! Form::select('servicio_id'
						,$pluckServicio
						,$tarifa['servicio_id'] ?? '0'
						,['class' 		=> 'form-control'
							,'placeholder'	=> 'Seleccionar'
							,'required'	=> 'true'
						]);
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">COSTO de ENVIO
						</span>
					</div>

					{!! Form::text('costo'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'costo'
							,'data-parsley-type'		=> 'number'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">Kg Inicial <span class="tx-danger">*</span></span>
					</div>
					<div class="handle-counter" id="handleCounterMax100_ini">
						<text class="counter-minus btn btn-light">-</text>
						
						{!! Form::text('kg_ini'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'kg_ini'
							,'min'		=> '1'
							,'required'	=>	'true'
						])
					!!}

						<text class="counter-plus btn btn-light">+</text>
					</div>
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">Kg Final <span class="tx-danger">*</span></span>
					</div>
					<div class="handle-counter" id="handleCounterMax100_fin">
						<text class="counter-minus btn btn-light">-</text>
						
						{!! Form::text('kg_fin'
							, null
							,['class' 		=> 'form-control'
								,'id'		=> 'kg_fin'
								,'min'		=> '1'
								,'required'	=>	'true'
							])
						!!}

						<text class="counter-plus btn btn-light">+</text>
					</div>
				</div>
				
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">COSTO KG EXTRA 
						</span>
					</div>

					{!! Form::text('kg_extra'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'kg_extra'
							,'data-parsley-type'		=> 'number'
							,'required'	=>	'true'
						])
					!!}
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">AREA EXTENDIDA
						</span>
					</div>

					{!! Form::text('extendida'
						, null
						,['class' 		=> 'form-control'
							,'id'		=> 'extendida'
							,'data-parsley-type'		=> 'number'
							,'required'	=>	'true'
						])
					!!}
				</div>
			</div>
		</div>
	</div>
</div>