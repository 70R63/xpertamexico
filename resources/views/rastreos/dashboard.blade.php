@extends('dashboard')
@section('content')

@include('rastreos.dashboard.header')
<!--Row-->
<div class="row row-sm">
    <div class="col-lg-10">
        <div class="card custom-card mg-b-20">
            <div class="card-body">
                <div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
                    <div>
                        <label class="main-content-label mb-2">Rastreos </label> <span class="d-block tx-12 mb-3 text-muted">La vista pretende dar un resuemn del rastreo (tracking) de cada guia realizada.</span>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <div class="col-lg-2">
        <div class="card custom-card mg-b-10">
            <div class="card-body">
                <div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
                    <div class="justify-content-center">
                        <h6 class="mb-2">Ultimo rastreo <p>{{ $rastreoPeticion->peticion_fin}}</h6>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>


<div class="d-flex">
        
    </div>
@include('rastreos.dashboard.tabla')
     
@endsection
