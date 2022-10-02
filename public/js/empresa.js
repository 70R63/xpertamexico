

$("#btnAsignarLtd").click(function(e) {
    console.log("btnAsignarLtd")
    e.preventDefault();

    var form = $('#generalForm').parsley().refresh();
    var action = $('#generalForm').attr("action");

    if ( form.validate() ){ 

    } else {
        console.log( "enviosForm con errores" );
        return false;
    }
    
});
