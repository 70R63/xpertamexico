<style>
.registros td{
  border:1px solid black;
}

.registros th {
    font-family: 'arial';
    font-size:13px;
}

.cabecera td {
    font-family: 'arial';
    font-size:14px;
}

td {
    font-family: 'arial';
    font-size:12px;
}


</style>

<div >
    <table style="width:100%; ">
        <thead>
            <tr >
                <th colspan="9" class="mark">COMERCIALIZADORA XPERTA MEXICO</th>
            </tr >
        </thead>

    <tbody class="cabecera">

        <tr >
            <td colspan="2">CONCILIACION DE CUENTAS POR PAGAR</td>
            <td>NUMERO DE ENVIOS:{{ $conciliacionView['cantidad']}}</td>
            <td colspan="4"></td>
            <td>$ PRECIO DE VENTA SIN IVA </td>
            <td>{{ $conciliacionView['subtotal_facturado_ltd_sum']}}</td>
        </tr>
        <tr>
            <td>FACTURA LTD: </td>
            <td>{{ $conciliacionView['num_factura_ltd']}}</td>
            <td colspan="5"></td>
            
            <td>$ COSTO DE VENTA SIN IVA</td>
            <td>{{ $conciliacionView['costo_base_sum']}}</td>
             
        </tr>
        <tr>
            <td>TRANSPORTISTA: </td>
            <td>{{ $conciliacionView['ltd_nombre']}}</td>
            <td colspan="5"> </td>
            
            <td>% UTILIDAD FACTURA LTD </td>
            <td>{{ $conciliacionView['utilidad_factura_ltd_porcentaje_sum']}}%</td>
             
        </tr>

        <tr>
            <td>FECHA FACTURA: </td>
            <td>{{ $conciliacionView['fecha_factura']}}</td>
            <td colspan="5"> </td>
            <td>$ UTILIDAD FACTURA LTD </td>
            <td>${{ $conciliacionView['utilidad_factura_ltd_monetaria_sum']}}</td>
             
        </tr>
          
    </tbody>
    </table>
</div>

<table style="width:100%; border:5px double black;">
</table>

<div class="table-responsive">
    <table class="registros" style="width:100%;" >
        <thead>
            <tr>
                <th>ID</th>
                <th>FECHA ENVIO </th>
                <th>TRACKING </th>
                <th>CLIENTE XPERTA </th>
                <th>PRECIO VENTA SIN IVA</th>
                <th>COSTO DE VENTA FACTURA LTD SIN IVA</th>
                <th>$ UTILIDAD  </th>  
                <th> % UTILIDAD </th>
                <th> % COSTO DE VENTAS</th>
                
            </tr>
        </thead>

        @foreach( $conciliacionDetalleView  as $i=>$row)
            <tr>
                <td>{{ $i+1 }}</td> 
                <td>{{ $row['fecha_envio'] }}</td>
                <td>{{ $row['tracking_number'] }}</td>
                <td>{{ $row['nombre'] }}</td>
                <td>{{ $row['costo_base'] }}</td>
                <td>{{ $row['subtotal_facturado_ltd'] }}</td>
                <td>{{ $row['utilidad_monetaria'] }}</td>
                <td>{{ $row['utilidad_porcentaje'] }}</td>
                <td>{{ $row['costo_venta_porcentaje'] }}</td>
               
            </tr>
            
                
        @endforeach

                                
        <tfoot>
            <tr>
                <th>ID</th>
                <th>FECHA ENVIO </th>
                <th>TRACKING </th>
                <th>CLIENTE XPERTA </th>
                <th>PRECIO VENTA SIN IVA</th>
                <th>COSTO DE VENTA FACTURA LTD SIN IVA</th>
                <th>$ UTILIDAD  </th>  
                <th> % UTILIDAD </th>
                <th> % COSTO DE VENTAS</th>
            </tr>
        </tfoot>
    </table>
</div>