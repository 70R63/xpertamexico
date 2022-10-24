<div class="payment-type d-flex mg-b-20">
	@for ($ii = $i*3; $ii < ($i*3)+3; $ii++)
    	
		@if($registros <= $ii)
            @break
        @endif

	<input type="radio" name="radio3" id="credit" value="credit" >
		<label class="credit-label payment-cards four ml-0 col" for="credit">
			<span class="d-none d-md-block"> {{ $tabla[$ii]->nombre }}</span>
			<img src="{{ asset( $tabla[$ii]->imagen_ruta ) }}" alt="estafeta">
			
			<div class="text-right">
						
				<a href="{{ route('ltds.edit', $tabla[$ii]->id) }}" class="text-info tx-20" data-abc="true">
					<i class="fe fe-edit"></i>
				</a>
				@include('ltd.modals.eliminar')	
			</div>
		</label>
	@endfor
</div>

