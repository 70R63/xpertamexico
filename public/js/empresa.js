function validaCheckAsignacion( elemento ){
    
    var check = elemento.is( ":checked" )

        
    var ltdName = elemento.attr("name"); 
    var comboActivo=$("select[name='clasificacion["+ltdName+"]']")
    console.log("validando combo")
    console.log(comboActivo.val())

    if ( check ) {
        comboActivo.attr("required","true");
    } else{
        comboActivo.removeAttr("required");
        comboActivo.prop('selectedIndex',0); 


    }
}

$(".btnAsignarLtd").click(function(e) {
    console.log("btnAsignarLtd")
    e.preventDefault();
    
    var idModal = "#asignarLtd"+$(this).attr('id');
    
    var data = $(idModal).find('Form').serialize();
    var accion = $(idModal).find('Form').attr("action");
   
    
    $(idModal+" .selectgroup-input").each(function(){
        validaCheckAsignacion( $(this) )
    })
    

    var form = $(idModal+' #generalForm').parsley().refresh();

    if ( form.validate() ){ 
        $(".modalAsignarLtd").modal('hide');
         $.ajax({
            /* Usar el route  */
            url: accion,
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data
            
            /* remind that 'data' is the response of the AjaxController */
            }).done(function( response) {
                console.log("done")
                swal(
                    "Exito!",
                    response.data.mensaje,
                    "success"
                  )

            }).fail( function( data,jqXHR, textStatus, errorThrown ) {
                console.log( "fail" );
                console.log(textStatus);
                
                swal(
                    "Error!",
                    "Asignacion incorrecta!. Consulte con su proveedor",
                    "error"
                  )

            }).always(function() {
                console.log( "complete" );
            });
        
    } else {
        console.log( "enviosForm con errores" );
        //return false;
    }
    
});

$("#asignarRcPG").click(function(e) {
    console.log("asignarRcPG")
    e.preventDefault();

    $("#rfc").val("XAXX010101000");

});


$('.selectgroup-input').change(function () {
    
    validaCheckAsignacion($(this))

});