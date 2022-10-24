<!-- Campos ocultos para uso de request -->
{!! Form::hidden('precio'
    , $objeto['precio']
    ,['class'       => 'form-control'
        ,'id'       => 'precio'
        
    ])
!!}

{!! Form::hidden('piezas'
    , $objeto['piezas_cotizacion']
    ,['class'       => 'form-control'
        ,'id'       => 'piezas'
        
    ])
!!}

{!! Form::hidden('ltd_id'
    , $objeto['ltd_id']
    ,['class'       => 'form-control'
        ,'id'       => 'ltd_id'
        
    ])
!!}

{!! Form::hidden('peso'
    , $objeto['peso_cotizacion']
    ,['class'       => 'form-control'
        ,'id'       => 'peso'
        
    ])
!!}