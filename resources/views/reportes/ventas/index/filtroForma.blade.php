<!-- Row -->
{!! Form::open([ 'route' => 'api.cotizaciones.index', 'method' => 'GET' , 'class'=>'parsley-style-1', 'id'=>'cotizacionesForm' ]) !!}
    <div class="card custom-card">
        <div class="card-body">
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">CLIENTE</span>
                        </div><input aria-describedby="basic-addon1" aria-label="Cliente" class="form-control" placeholder="Username" type="text">
                    </div>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">LTD</span>
                        </div>
                        <select class="form-control select2">
                            <option label="Choose one">
                            </option>
                            <option value="Firefox">
                                ESTAFETA
                            </option>
                            <option value="Chrome">
                                FEDEX
                            </option>
                            <option value="Safari">
                                DHL
                            </option>
                            <option value="Opera">
                                REDPACK
                            </option>
                        </select>
                    </div>
                </div><!-- col-4 -->
            </div>
            
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">SERVICIO</span>
                        </div>
                        <select class="form-control select2">
                            <option label="Choose one">
                            </option>
                            <option value="Firefox">
                                DIA SIG
                            </option>
                            <option value="Chrome">
                                2 DIAS
                            </option>
                            <option value="Safari">
                                TERRESTRE
                            </option>

                        </select>
                    </div>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fe fe-calendar  lh--9 op-6"></i>
                            </div>
                        </div><input type="text" class="form-control pull-right" id="reservation">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
    <div class="form-group row justify-content-around">     
        <div>
            <a id="cotizar" class="btn btn-primary" >Cotizar</a>    
            <a id="limpiar" class="btn badge-dark" >Limpiar</a>
            
        </div>   
    </div>  
</div>  
{!! Form::close() !!}
<!-- End Row -->