<div class="pd-5">
    <span class="input-group-text" id="basic-addon1">LTD</span>
    {!! Form::select('ltd_id'
        , $ltds
        ,null
        ,['class'       => 'form-control select2'
            ,'placeholder'  => 'TODOS'
            ,'id'       => 'ltd_id'
            ,'required' => ''
            
        ]);
    !!}    

</div>

<div class="pd-5">
    <span class="input-group-text" id="basic-addon1">Fecha Factura</span>

    {{Form::date('fecha_factura', null
        , ['class' => 'form-control'
            ,'required' => ''
        ])
    }}
</div>
<div class="pd-5">

    <span class="input-group-text" id="basic-addon1">Cargar Archivo Conciliciacion</span>
    
        <p class="tx-12 mb-0 text-muted">
            Archivo con extencion csv.
        </p>
    
    
</div>
<input accept=".csv" type="file" class="dropify" data-height="200" id="fileCargaConciliacion" name="fileCargaConciliacion" required />