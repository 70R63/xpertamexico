$("#limpiar").click(function() {
    $('#cotizacionesForm').trigger('reset');
});

$("#cotizar").click(function() {
    console.log("cotizar")
    var form = $('#cotizacionesForm').parsley().refresh();

    if ( form.validate() ){
        console.log($('.tipo_envio').val() )
        $.ajax({
            /* Usar el route  */
            url: "http://local.xpertamexico.com/api/create",
            type: 'GET',
            /* send the csrf-token and the input to the controller */
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $('#cotizacionesForm').serialize()
           
            /* remind that 'data' is the response of the AjaxController */
            }).done(function( data) {
                console.log("done");
                console.log(data);
                
                $("#spanPrecio").text( data.precio );
                $("#spanServicio").text( $("#servicio option:selected").text() );
                $("#spanEmbalaje").text( data.nombre );
                $("#spanPieza").text( data.piezas );
                $("#spanPeso").text( data.pesoMax );
                $("#spanSeguro").text("$"+costoSeguroGlobal);
                $("#modalEnviar").modal("show");

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
