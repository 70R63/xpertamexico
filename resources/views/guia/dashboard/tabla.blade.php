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
                                <th>USUARIO</th>
                                <th>MENSAJERIA</th>
                                <th>REMITENTE</th>
                                <th>DESTINATARIO</th>
                                <th>ESTATUS</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $tabla  as $objeto)
                            <tr>
                                <td>{{ $objeto->id }}</td>
                                <td>{{ $objeto->usuario }}</td>
                                <td>{{ $ltdActivo[$objeto->ltd_id] }}</td>
                                <td>{{ $objeto->cia }}</td>
                                <td>{{ $objeto->cia_d }}</td>
                                <td></td>
                                <td></td>
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
