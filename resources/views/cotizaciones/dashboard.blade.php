@extends('dashboard')
@section('content')


@include('cotizaciones.dashboard.header')
<!--Row-->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card custom-card mg-b-20">
            <div class="card-body">
                <div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
                    <div>
                        <label class="main-content-label mb-2">Tasks q</label> <span class="d-block tx-12 mb-3 text-muted">A task is accomplished by a set deadline, and must contribute toward work-related objectives.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Row end -->

<!--Row-->
<div class="row row-sm">
    <div class="col-lg-12 col-xl-8  col-md-12">
        <div class="card custom-card ">
            {!! Form::open([ 'route' => 'api.cotizaciones.index', 'method' => 'GET' , 'class'=>'parsley-style-1', 'id'=>'cotizacionesForm' ]) !!}
            <div class="row row-sm">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="card-body">
                        <div class="col-sm-5 ">
                            <div>

                                <span class="tx-18 mb-3">ORIGIEN</span> 
                            </div>
                        </div>
                        @include('cotizaciones.dashboard.forma.origen')
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="card-body">
                        <div class="col-sm-5">
                            <div>
                                <span class="tx-18 mb-3">DESTINO</span> 
                            </div>
                        </div>
                        @include('cotizaciones.dashboard.forma.destino')
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
            
            </div>
            <!-- Fin row --> 
            {!! Form::close() !!}           
        </div>

        <div class="card custom-card">
            <div class="card-body">
                <div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
                    
                </div>
                @include('cotizaciones.dashboard.tabla')    
            </div>
        </div>

    </div>

    <div class="col-lg-12 col-xl-4  col-md-4">
        <div class="card custom-card mg-b-20">
            <div class="card-body">
                <div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
                    <label class="main-content-label mb-4">GRAFICAS</label>
                </div>
                @include('cotizaciones.dashboard.grafica') 
            </div>
        </div>
    </div>
</div>
<!-- Row end -->

@endsection
