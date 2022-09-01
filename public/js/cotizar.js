$("#limpiar").click(function() {
    $('#cotizacionesForm').trigger('reset');
});

$("#cotizar").click(function(e) {
    console.log("cotizar")
    e.preventDefault();
    var form = $('#cotizacionesForm').parsley().refresh();
    var action = $('#cotizacionesForm').attr("action"); 
    //var url = "{{ route('postSubmit') }}"
    //var url = !{ route("api.cotizaciones.index") }! 

    console.log( action );

    if ( form.validate() ){
        console.log($('.tipo_envio').val() )

        
        $.ajax({
            /* Usar el route  */
            url: action,
            type: 'GET',
            /* send the csrf-token and the input to the controller */
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $('#cotizacionesForm').serialize()
            
            /* remind that 'data' is the response of the AjaxController */
            }).done(function( data) {
                console.log("done");
                console.log(data);
                 $('#exportGeneral tbody').empty();
                var tr_str = "<tr>" +
                 "<td align='center'> id </td>" +
                 "<td align='center'>ltd</td>" +
                 "<td align='center'> servicio </td>" +
                 "<td align='center'> inicial</td>" +
                 "<td align='center'> final </td>" +
                 "<td align='center'> extendida</td>" +
                 "<td align='center'> otro</td>" +
                 "<td align='center'> extendida</td>" +
                 "<td align='center'> otro</td>" +
               "</tr>";

               $("#exportGeneral tbody").append(tr_str);
                
                //$("#modalEnviar").modal("show");

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
