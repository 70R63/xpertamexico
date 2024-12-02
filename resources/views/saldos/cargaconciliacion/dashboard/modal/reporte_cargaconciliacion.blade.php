<div class="table-responsive">
    <table id="exportGeneral" class="table table-striped table-bordered text-nowrap " >
        <thead>
            <tr>
                <th>CONCILIACION ID </th>
                <th>FECHA FACTURA </th>
                <th>NO FACTURA </th>
                <th>LTD</th>
                <th>USUARIO XPERTA </th>  
                <th> SUBTOTAL</th>
                <th> TOTAL</th>
                <th>TIPO CFDI</th>
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

        @foreach( $tabla  as $row)
            <tr>
                <td>{{ $row['id'] }}</td>
                <td>{{ $row['id'] }}</td>
                <td>{{ $row['created_at'] }}</td>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['no_guias'] }}</td>
                <td>{{ $row['importe_total'] }}</td>
                <td>{{ $row['file_nombre'] }}</td>
                <td></td>
                <td></td>
            </tr>
                
        @endforeach

                                
        <tfoot>
            <tr>
                                <th>CONCILIACION ID </th>
                <th>FECHA FACTURA </th>
                <th>NO FACTURA </th>
                <th>LTD</th>
                <th>USUARIO XPERTA </th>  
                <th> SUBTOTAL</th>
                <th> TOTAL</th>
                <th>TIPO CFDI</th>
                <th>TIPO CFDI</th>
            </tr>
        </tfoot>
    </table>
</div>