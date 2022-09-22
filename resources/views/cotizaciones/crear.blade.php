@extends('dashboard')
@section('content')


@include('cotizaciones.crear.header')
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
{!! Form::open([ 'route' => 'guia.store', 'method' => 'POST' , 'class'=>'parsley-style-1', 'id'=>'generalForm' ]) !!}
<div class="row row-sm">
    
    <div class="col-lg-12 col-xl-4 col-md-4">
        @include('cotizaciones.forma.sucursal_readonly')
        <div>
            <a href="{{ route('cotizaciones.index') }}" class="btn badge-dark" >Cancelar</a>
            <button type="submit" class="btn btn-primary ml-3" >Crear Guia</button>
        </div>    
    </div>
    <!-- class="col-lg-12 col-xl-4 col-md-4" -->
    <div class="col-lg-12 col-xl-4 col-md-4">
        @include('cotizaciones.forma.cliente_readonly')    
    </div>
    <!-- class="col-lg-12 col-xl-4 col-md-4" -->
        
    <div class="col-lg-12 col-xl-4 col-md-4">
        @include('cotizaciones.crear.card_preciofinal')
    </div>
</div>
<!-- Row end -->
    {!! Form::close() !!}

@endsection
