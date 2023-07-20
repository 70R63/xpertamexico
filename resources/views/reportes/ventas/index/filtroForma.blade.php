<!-- Row -->
{!! Form::open([ 'route' => 'api.reportes.ventas', 'method' => 'POST' , 'class'=>'parsley-style-1', 'id'=>'reporteVentasForm' ]) !!}
    <div class="card custom-card">
        <div class="card-body">
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">CLIENTE</span>
                        </div>
                        {!! Form::select('clienteIdCombo'
                            , array()
                            ,null
                            ,['class'       => 'form-control select2'
                                ,'placeholder'  => 'Seleccionar'
                                ,'id'       => 'clienteIdCombo'
                                
                            ]);
                        !!}
                    </div>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">LTD</span>
                        </div>
                        {!! Form::select('ltdId'
                            , Config('ltd.general')
                            ,null
                            ,['class'       => 'form-control select2'
                                ,'placeholder'  => 'Seleccionar'
                                ,'id'       => 'ltdId'
                                
                            ]);
                        !!}
                    </div>
                </div><!-- col-4 -->
            </div>
            
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">SERVICIO</span>
                        </div>

                        {!! Form::select('servicio_id'
                            , ['1' => 'Terrestre', '2'=>'Dia Sig.', '3'=>'2 Dias']
                            ,null
                            ,['class'       => 'form-control select2'
                                ,'placeholder'  => 'Seleccionar'
                                ,'id'       => 'servicio_id'
                                
                            ]);
                        !!}
                        
                    </div>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <i class="fe fe-calendar  lh--9 op-6"></i>
                        </div>
                        <input type="text" class="form-control pull-right datepicker" id="fecha_ini" name="fecha_ini" placeholder="Fecha Inicial">
                    </div>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <i class="fe fe-calendar  lh--9 op-6"></i>
                        </div>
                        <input type="text" class="form-control pull-right datepicker" id="fecha_fin" name="fecha_fin" placeholder="Fecha Final">
                    </div>
                </div>
            </div>

            
        </div>
        <!-- fin car-body -->
    </div>
    <div class="col-lg-12">
    <div class="form-group row justify-content-around">     
        <div>
            <a id="generarReporte" class="btn btn-primary" >Generar</a>    
            <a id="limpiar" class="btn badge-dark" >Limpiar</a>
            
        </div>   
    </div>  
</div>  


{!! Form::close() !!}
<!-- End Row -->