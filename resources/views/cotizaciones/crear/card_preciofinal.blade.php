<!-- Seccion del precio final -->
<div class="card custom-card mg-b-20">
    <div class="card-body">
        <div class="card custom-card pricingTable2">
            <div class="pricingTable2-header">
                <h2>PRECIO </h2> <h6>CON IVA</h6>
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
                            <li>
                                <b>Remitente Postal:</b> {{ isset($sucursal->cp) ? $sucursal->cp : $objeto['cp_manual']}}</span>    
                            </li>
                            <li>
                                <b>Destinatorio Postal:</b> {{ isset($cliente->cp) ? $cliente->cp : $objeto['cp_d_manual']}} </span>
                            </li>
                            <li>
                                <b>Piezas: {{$piezas}}</b> <span id="spanPieza"></span>,
                                <b>Peso Facturado: {{$objeto['peso_facturado']}}</b> <span id="spanPeso"></span> Kg.
                            </li>
                        </li>
                    </h4>
                    <li>
                        <b>Valor de Envio:</b>${{$objeto['valor_envio_r']}} <span id="spanValorEnvio"></span>
                        <b>Seguro:</b>${{$objeto['costo_seguro']}} <span id="spanSeguro"></span>
                    </li>
                    <li>
                        <b>Guia Manual:</b>{{$objeto['esManual']}}<span id="spanCotizacionManual"></span>
                    </li>
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

{!! Form::hidden('peso_facturado'
    , $objeto['peso_facturado']
    ,['class'       => 'form-control'
        ,'id'       => 'peso_facturado' 
    ])
!!}


    @foreach ($objeto['pesos'] as $peso)
        {!! Form::hidden('pesos[]'
            , $peso
            ,['class'       => 'form-control'
                ,'id'       => 'pesos' 
            ])
        !!}
    @endforeach

    @foreach ($objeto['largos'] as $largo)
        {!! Form::hidden('largos[]'
            , $largo
            ,['class'       => 'form-control'
                ,'id'       => 'largos' 
            ])
        !!}
    @endforeach

    @foreach ($objeto['anchos'] as $ancho)
        {!! Form::hidden('anchos[]'
            , $ancho
            ,['class'       => 'form-control'
                ,'id'       => 'anchos' 
            ])
        !!}
    @endforeach

    @foreach ($objeto['altos'] as $alto)
        {!! Form::hidden('altos[]'
            , $alto
            ,['class'       => 'form-control'
                ,'id'       => 'altos' 
            ])
        !!}
    @endforeach




{!! Form::hidden('bSeguro'
    , $objeto['bSeguro']
    ,['class'       => 'form-control'
        ,'id'       => 'bSeguro' 
    ])
!!}

{!! Form::hidden('contenido'
    , $objeto['contenido_r']
    ,['class'       => 'form-control'
        ,'id'       => 'contenido' 
    ])
!!}

{!! Form::hidden('extendida'
    , $objeto['extendida_r']
    ,['class'       => 'form-control'
        ,'id'       => 'extendida' 
    ])
!!}

{!! Form::hidden('valor_envio'
    , $objeto['valor_envio_r']
    ,['class'       => 'form-control'
        ,'id'       => 'valor_envio_r' 
    ])
!!}

{!! Form::hidden('costo_seguro'
    , $objeto['costo_seguro']
    ,['class'       => 'form-control'
        ,'id'       => 'costo_seguro' 
    ])
!!}

{!! Form::hidden('precio'
    , $precio
    ,['class'       => 'form-control'
        ,'id'       => 'precio' 
    ])
!!}

{!! Form::hidden('esManual'
    , $objeto['esManual']
    ,['class'       => 'form-control'
        ,'id'       => 'esManual' 
    ])
!!}

{!! Form::hidden('empresa_id'
    , $objeto['empresa_id']
    ,['class'       => 'form-control'
        ,'id'       => 'empresa_id' 
    ])
!!}