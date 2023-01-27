$(document).ready(function() {
    console.log("MenuDireccion")
})

var cpLista = 0;


$("#cp").on("change keyup paste", function (){
    
        if ( $("#cp").val().length > 2)
            listaColonias();
    });

function listaColonias(){
    $.ajax({
        url: route('api.cp.colonias'),
        type: 'GET',
        data: "cp="+$("#cp").val()
        /* send the csrf-token and the input to the controller */
        ,headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        
    }).done(function( response) {
        console.log( "done" );
        cpLista = response.data;
        
        $('#colonia').empty();
        $("#colonia").append('<option value=null> Seleccionar</option>');
        $.each(response.data,function(key, cps) {
            $("#colonia").append('<option selector='+key+' value="'+cps.colonia+'" >'+cps.colonia+'</option>');
          });   
        
    }).fail( function( data,jqXHR, textStatus, errorThrown ) {
        console.log( "fail" );
        console.log(data);
        swal(
            "Error!",
            textStatus,
            "error"
          )

    }).always(function() {
            console.log( "complete" );
    });
}


$("#colonia").on("change keyup", function (){
    console.log( "cambio la colonia")
    
    var selector = $('#colonia option:selected').attr("selector")
    var registroCP = cpLista[selector];

    $("#ciudad").val(registroCP.municipio);
    $("#entidad_federativa").val(registroCP.entidad_federativa);
    
});