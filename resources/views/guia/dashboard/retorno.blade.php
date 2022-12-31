<a href="" class="text-nowrap tx-20" data-toggle="modal" data-target="#asignarLtd{{ $objeto->id }}" >
	<i title="Retorno de la guia" class="si si-action-undo"> </i>
</a>

{!! Form::open([ 'route' => 'guiaretorno.create', 'method' => 'GET' , 'class'=>'parsley-style-1', 'id'=>'generalForm' ]) !!}
<div class="modalAsignarLtd modal fade" id="asignarLtd{{ $objeto->id }}" tabindex="-1" role="dialog" aria-labelledby="modalAsignarLtd" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	            <h5 class="modal-title" id="exampleModalLabel"></h5>
	            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
	            	<span aria-hidden="true">×</span>
	            </button>
	        </div>

	    	<div class="modal-body">
                <div class="card custom-card pricingTable2"> 
                    <div class="pricing-plans  bg-primary">
                        <h2>Retorno de guia - ID {{$objeto->id}} </h2>
                    </div>
                    <div class="pricingContent2">
                        <ul>
                            <h4>
                                <li><b>Remitente :</b>{{ $objeto->contacto }}   
                                </li>
                                <li>
                                	<b>Destinatorio :</b> {{ $objeto->contacto_d }}
                                </li>
                            </h4>

                            <h4>
                                <li><b>Nuevo Remitente:</b> {{ $objeto->contacto_d }}   
                                </li>
                                <li>
                                	<b>Nuevo Destinatorio:</b> {{ $objeto->contacto }}
                                </li>
                            </h4>
                        </ul>
                    </div>
                    <div class="pricing-plans  bg-primary">
                        Para continuar con la creación, precionar el boton continuar
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary ml-3" >Continuar</button>
                    <a class="btn badge-dark" data-dismiss="modal" type="button">Cerrar</a>
                </div>
            </div>
            <!-- FIN class="modal-body" -->
	    </div> <!-- modal-content -->
  	</div> <!-- modal-dialog -->
</div> <!--modal fad -->

<!-- Campos ocultos -->

{!! Form::hidden('guia_id'
    , $objeto->id
    ,['class'       => 'form-control'
        ,'id'       => 'guia_id'
    ])
!!}


{!! Form::close() !!} 