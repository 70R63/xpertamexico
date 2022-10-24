$(".btnAsignarLtd").click(function(e) {
    console.log("btnAsignarLtd")
    e.preventDefault();
    
    var idModal = "#asignarLtd"+$(this).attr('id');
    
    var data = $(idModal).find('Form').serialize();
    var accion = $(idModal).find('Form').attr("action");
    var form = $('#generalForm').parsley().refresh();

    $(".modalAsignarLtd").modal('hide');
    console.log(accion)
    if ( form.validate() ){ 

         $.ajax({
            /* Usar el route  */
            url: accion,
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data
            
            /* remind that 'data' is the response of the AjaxController */
            }).done(function( response) {

                swal(
                    "Exito!",
                    "Asignacion correcta!",
                    "success"
                  )

            }).fail( function( data,jqXHR, textStatus, errorThrown ) {
                console.log( "fail" );
                console.log(textStatus);
                
                swal(
                    "Error!",
                    "Asignacion incorrecta!",
                    "error"
                  )

            }).always(function() {
                console.log( "complete" );
            });

    } else {
        console.log( "enviosForm con errores" );
        return false;
    }
    
});

$("#asignarRcPG").click(function(e) {
    console.log("asignarRcPG")
    e.preventDefault();

    $("#rfc").val("XAXX010101000");

});