@extends('dashboard')
@section('content')

@include('saldos.cargaconciliacion.show.header')

<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card ">
            <div class="card-header bg-transparent border-bottom-0 card-item">
                <div class="card-item-body">
                    <div>
                        <label class="main-content-label mb-2">Carga Conciliacion
                        </label>
                        <span class="d-block tx-12 mb-0 text-muted">Subir archivo con la realacion de factura y guias cobradas por las LTDs.
                        </span>    

                    </div>
                    <div class="mb-2">
                        <a href="{{route('saldos.cargaconciliacion.descarga',['facturaId'=>$facturaId]) }}" class="badge-success">
                        Descarga tu PDF
                        </a>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>


<!-- End Row -->
<!-- Row -->
<div class="row row-sm">
     
    <div class="col-xxl-10 col-xl-12 col-lg-12 col-md-12">
         <div class="card custom-card">
            <div class="card-body ">
                @include("saldos.cargaconciliacion.show.tabla_detalle")
            </div>
        </div>
    </div>
  
    
</div>
<!-- End Row -->
     
@endsection
