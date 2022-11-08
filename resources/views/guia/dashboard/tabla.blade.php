<!--Row-->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card custom-card mg-b-20">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="exportGeneral" class="table table-striped table-bordered text-nowrap" >
                        <thead>
                            <tr>
                                <th>CLAVE</th>
                                <th>MENSAJERIA</th>
                                <th>TRACKING</th>
                                <th>SERVICIO</th>
                                <th>USUARIO</th>
                                
                                <th>REMITENTE</th>
                                <th>DESTINATARIO</th>
                                <th>GUIA</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $tabla  as $objeto)
                            <tr>
                                <td>{{ $objeto->id }}</td>
                                <td>{{ $ltdActivo[$objeto->ltd_id] }}</td>
                                <td>{{ $objeto->tracking_number }} </td>
                                <td>{{ $objeto->id }}</td>
                                <td>{{ $objeto->usuario }}</td>
                                
                                <td>{{ $sucursal[$objeto->cia] }}</td>
                                <td>{{ $cliente[$objeto->cia_d] }}</td>
                                <td>@include('guia.dashboard.documento')</td>
                            </tr>
                            @endforeach
                        </tbody>
                        
                        <tfoot>
                            <tr>
                              <td colspan="7">Los datos son responsalidad del usuario</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row-->
