<div class="table-responsive">
    <table id="exportGeneral" class="table table-striped table-bordered text-nowrap " >
        <thead>
            <tr>
                <th>FECHA CREACION</th>
                <th>CONCILIACION ID </th>
                <th>FECHA FACTURA </th>
                <th>NO FACTURA </th>
                <th>LTD</th>
                <th>USUARIO XPERTA </th>  
                <th> SUBTOTAL</th>
                <th> TOTAL</th>
                <th>TIPO CFDI</th>
                
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </thead>

        @foreach( $conciliacionesTabla  as $row)
            <tr>
                <td>{{ $row['created_at'] }}</td>
                <td>
                    <a class="nav-sub-link" href="{{ route('cargaconciliacion.show', ['cargaconciliacion'=>$row['num_factura_ltd']]) }}">{{ $row['num_factura_ltd'] }}</a>
                </td>
                <td>{{ $row['fecha_factura'] }}</td>
                <td>{{ $row['num_factura_ltd'] }}</td>
                <td>{{ $row['ltd_nombre'] }}</td>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['subtotal_facturado_ltd_sum'] }}</td>
                <td>{{ $row['total_facturado_ltd_sum'] }}</td>
                <td></td>
                
            </tr>
                
        @endforeach

                                
        <tfoot>
            <tr>
                <th>FECHA CREACION</th>
                <th>CONCILIACION ID </th>
                <th>FECHA FACTURA </th>
                <th>NO FACTURA </th>
                <th>LTD</th>
                <th>USUARIO XPERTA </th>  
                <th> SUBTOTAL</th>
                <th> TOTAL</th>
                <th>TIPO CFDI</th>
            </tr>
        </tfoot>
    </table>
</div>