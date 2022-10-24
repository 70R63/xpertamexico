$("#limpiar").click(function() {
    $('#cotizacionesForm').trigger('reset');
});

var table;

$("#cotizar").click(function(e) {
    console.log("cotizar")
    e.preventDefault();

    var form = $('#cotizacionesForm').parsley().refresh();
    var action = $('#cotizacionesForm').attr("action"); 

    if ( form.validate() ){
        $.ajax({
            /* Usar el route  */
            url: action,
            type: 'GET',
            /* send the csrf-token and the input to the controller */
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $('#cotizacionesForm').serialize()
            
            /* remind that 'data' is the response of the AjaxController */
            }).done(function( response) {
                console.log("done");
                
                table = $('#cotizacionAjax').DataTable({
                    "oLanguage": {
                        "sEmptyTable": "No exiten tarifas con los datos para cotizar"
                    }
                    ,"processing": true,
                    "bDestroy": true,
                    "data": response.data.data,
                    "columns": [
                        { "data": "id" },
                        { "data": "nombre" },
                        { "data": "costo" 
                            ,render: function (data) {
                                return '$ '+data;
                            } 
                        },
                        { "data": "kg_ini" },
                        { "data": "kg_fin" },
                        { "data": "kg_extra" },
                        { "data": "extendida" },
                        { "data": "costo_total"
                            ,render: function (data, type, row, meta) {
                                var piezas = $('#piezas').val();
                                var costo = data* piezas;
                                return '<a href="#">$ '+costo+'</a>';
                            } 
                        }
                    ],
                    "autoWidth": false,
                });
                
            }).fail( function( data,jqXHR, textStatus, errorThrown ) {
                console.log( "fail" );
                console.log(textStatus);
                
                alert( data.responseJSON.message);

            }).always(function() {
                console.log( "complete" );
            });
        
    } else {
        console.log( "enviosForm con errores" );
        return false;
    }
});



table = $('#cotizacionAjax').DataTable({
    "oLanguage": {
        "sEmptyTable": "Ingresa los datos para cotizar"
    }
});
$('#cotizacionAjax tbody').on('click', 'tr', function () {
    
    console.log(table.row(this).data());
    var piezas = $('#piezas').val();
    var sucursal_id = $('#sucursal').val();
    var cliente_id = $('#cliente').val();
    var cp = $('#cp').val();
    var cp_d = $('#cp_d').val();

    var precio = table.row(this).data()['costo_total']*piezas;
    var tarifa_id = table.row(this).data()['id'];
    var ltd_nombre = table.row(this).data()['nombre'];
    var ltd_id = table.row(this).data()['ltds_id'];

    $("#spanPrecio").text(precio);
    $("#spanMensajeria").text(ltd_nombre);
    $("#spanRemitente").text(cp);
    $("#spanDestinatario").text(cp_d);
    $("#spanPieza").text(piezas);
    
    //valores para request, campos ocultos guiastore_ocultos
    $("#precio").val(precio);
    $("#tarifa_id").val(tarifa_id);
    $("#sucursal_id").val(sucursal_id);
    $("#cliente_id").val(cliente_id);
    $("#ltd_nombre").val(ltd_nombre);
    $("#ltd_id").val(ltd_id);
    $("#piezas_guia").val(piezas);
    
    console.log(cliente_id);
    console.log(sucursal_id);

    $("#myModal").modal("show");
});

function obtenerCP(id, modelo) {

    $.ajax({
        /* Usar el route  */
        url: "api/cp",
        type: 'GET',
        /* send the csrf-token and the input to the controller */
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: "id="+id+"&modelo="+modelo
        
        /* remind that 'data' is the response of the AjaxController */
        }).done(function( response) {
            console.log("done");
            var contador = response.data.length
            console.log(contador)


            if ("Sucursal" == modelo) {
                if (contador == 1) {
                    $("#cp").val(response.data[0].cp);    
                } else {
                    $("#cp").val("00000");
                }
                
            } else {
                if (contador == 1) {
                    $("#cp_d").val(response.data[0].cp);    
                } else {
                    $("#cp_d").val("00000");
                }
            }
            
        
        }).fail( function( data,jqXHR, textStatus, errorThrown ) {
            console.log( "fail" );
            console.log(textStatus);
            
            alert( data.responseJSON.message);

        }).always(function() {
            console.log( "complete" );
        });

}

$("#sucursal").change(function() {
    var idSucursal = $('#sucursal').val();
    obtenerCP(idSucursal, "Sucursal");
}); 

$("#cliente").change(function() {
    var idCliente = $('#cliente').val();
    obtenerCP(idCliente, "Cliente");
});
