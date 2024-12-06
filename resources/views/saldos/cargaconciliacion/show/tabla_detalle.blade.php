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
                <th colspan="9">COMERCIALIZADORA XPERTA MEXICO</th>
            </tr >
        </thead>

    <tbody class="cabecera">

        <tr >
            <td colspan="2">CONCILIACION DE CUENTAS POR PAGAR</td>
            <td>NUMERO DE ENVIOS:</td>
            <td colspan="4">42</td>
            <td>$ PRECIO DE VENTA SIN IVA </td>
            <td>$26,104.85</td>
        </tr>
        <tr>
            <td>FACTURA LTD: </td>
            <td>MEXR005047386</td>
            <td colspan="5"></td>
            
            <td>$ COSTO DE VENTA SIN IVA</td>
            <td>$18,386.78</td>
             
        </tr>
        <tr>
            <td>TRANSPORTISTA: </td>
            <td>DHL</td>
            <td colspan="5"> </td>
            
            <td>$% UTILIDAD FACTURA LTD </td>
            <td>30%</td>
             
        </tr>

        <tr>
            <td>FECHA FACTURA: </td>
            <td>04/10/2024</td>
            <td colspan="5"> </td>
            <td>$ UTILIDAD FACTURA LTD </td>
            <td>$7,718.07</td>
             
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

        @foreach( $conciliacionesTabla  as $row)
            <tr>
                
                <td>{{ $row['fecha_envio'] }}</td>
                <td>{{ $row['tracking_number'] }}</td>
                <td>{{ $row['user_id'] }}</td>
                <td>Precio venta</td>
                <td>Costo Venta</td>
                <td>$ Utilidad</td>
                <td>% Utilidad</td>
                <td>% Costo Venta</td>
               
            </tr>
            
                
        @endforeach

                                
        <tfoot>
            <tr>
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