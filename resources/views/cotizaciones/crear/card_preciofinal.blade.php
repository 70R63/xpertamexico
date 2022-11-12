<!-- Seccion del precio final -->
<div class="card custom-card mg-b-20">
    <div class="card-body">
        <div class="card custom-card pricingTable2">
            <div class="pricingTable2-header">
                <h3>PRECIO FINAL</h3>
            </div>
            <div class="pricing-plans  bg-primary">
                <span class="price-value1">
                    $<span id="spanPrecio">{{$precio}}</span> MXP
                </span>
            </div>
            <div class="pricingContent2">
                <ul>
                    <h4>
                        <li><b>Mensajeria:</b> <span id="spanMensajeria"> {{$ltd_nombre}} - {{$servicio->nombre}}</span></li>
                        <li>
                            <b>Piezas:</b> <span id="spanPieza"> {{ $piezas}}</span>
                        </li>
                    </h4>
                </ul>
            </div>
            <div class="pricing-plans  bg-primary">
                Saldo restante $0.0
            </div>
        </div>
    </div>
</div>

{!! Form::hidden('ltd_id'
    , $objeto['ltd_id']
    ,['class'       => 'form-control'
        ,'id'       => 'ltd_id' 
    ])
!!}

{!! Form::hidden('piezas'
    , $piezas
    ,['class'       => 'form-control'
        ,'id'       => 'piezas' 
    ])
!!}

{!! Form::hidden('servicio_id'
    , $servicio->id
    ,['class'       => 'form-control'
        ,'id'       => 'servicio_id' 
    ])
!!}