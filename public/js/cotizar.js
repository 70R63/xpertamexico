$("#limpiar").click(function() {
    $('#cotizacionesForm').trigger('reset');
});

var table;

$("#cotizar").click(function(e) {
    console.log("cotizar")
    e.preventDefault();

    var form = $('#cotizacionesForm').parsley().refresh();
    var action = $('#cotizacionesForm').attr("action"); 
    
    console.log( action );

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
                        { "data": "nombre" },
                        { "data": "kg_ini" },
                        { "data": "kg_fin" },
                        { "data": "costo" },
                        { "data": "kg_extra" },
                        { "data": "extendida" },
                        { "data": "costo_total"
                            ,render: function (data, type, row, meta) {
                                var piezas = $('#piezas').val();
                                console.log(piezas);    
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
    var cp = $('#cp').val();
    var cp_d = $('#cp_d').val();
    
    $("#spanPrecio").text(table.row(this).data()['costo_total']*piezas);
    $("#spanMensajeria").text(table.row(this).data()['nombre']);
    $("#spanRemitente").text(cp);
    $("#spanDestinatario").text(cp_d);
    $("#spanPieza").text(piezas);
    
    $("#myModal").modal("show");
});