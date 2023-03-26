@extends('dashboard')
@section('content')

@include('rastreos.dashboard.header')
<!--Row-->
<div class="row row-sm">
    <div class="col-lg-6">
        <div class="card custom-card mg-b-20">
            <div class="card-body">
                <div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
                    <div>
                        <label class="main-content-label mb-2">Rastreo </label> 
                        <span class="d-block tx-14 mb-3 text-muted">La vista pretende dar un resuemn del rastreo (tracking) de cada guia realizada.</span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="col-lg-2">
        <div class="card custom-card mg-b-0">
            <div class="card-body">
                <div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
                    <div>
                        <label class="main-content-label mb-2">FEDEX </label> 
                        <span class="d-block tx-14 mb-3 text-success">{{ $rastreoPeticion->peticion_fin}}</span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="card custom-card mg-b-0">
            <div class="card-body">
                <div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
                    <div>
                        <label class="main-content-label mb-2">ESTAFETA </label> 
                        <span class="d-block tx-14 mb-3 text-success">{{ $rastreoPeticion->peticion_fin}}</span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="card custom-card mg-b-0">
            <div class="card-body">
                <div class="card-header border-bottom-0 pt-0 pl-0 pr-0 d-flex">
                    <div>
                        <label class="main-content-label mb-2">REDPACK </label> 
                        <span class="d-block tx-14 mb-3 text-success">{{ $rastreoPeticion->peticion_fin}}</span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@include('rastreos.dashboard.tabla')
     
@endsection
