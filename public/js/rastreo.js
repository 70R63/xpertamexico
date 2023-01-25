$(document).ready(function() {
    console.log("Ready peticion ajax ")
    if ($('#rastreoTabla').length) {
        rastrearTabla()     
    }
   
}) 


$("#rastroActualizarAjax").click(function(e) {
    console.log("rastroActualizarAjax")
    e.preventDefault();
    rastreoActualizarAjax()
    swal(
        "Rastreo de guias",
        "El proceso tomara 30 minutos",
        "success"
      )
});


function rastrearTabla(){
    $.ajax({
        url: 'api/rastreoTabla',
        type: 'GET',
        /* send the csrf-token and the input to the controller */
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        
        
        /* remind that 'data' is the response of the AjaxController */
    }).done(function( response) {
        console.log(response.data)
        table = $('#rastreoTabla').DataTable({
                    "oLanguage": {
                        "sEmptyTable": "No se puede mostrar los registros"
                    }
                    ,processing: true
                    ,serverSide: false 
                    ,pagingType: "full_numbers"
                    ,deferRender: true
                    ,bDestroy: true
                    ,data: response.data
                    ,autoWidth: false
                    ,order: [[0, 'desc']]
                    ,lengthMenu: [
                        [ 10, 25, 50, -1 ],
                        [ '10', '25', '50', 'Todo' ]
                    ]
                    ,dom: 'Bfrtip'
                    ,buttons: [ 
                        'pageLength'
                      ,{ 
                         extend: 'excelHtml5'
                         , footer: true
                         , charset: 'utf-8' 
                         , fieldSeparator: ','
                         ,fieldBoundary: ''
                         ,exportOptions: {
                            columns: ':not(.notexport)'
                         }
                         ,customizeData: function(data) {
                           for(var i = 0; i < data.body.length; i++) {
                            console.log("custom")
                             data.body[i][2] = '\0' + data.body[i][2];
                           }
                         } 
                      }
                      ,{ 
                         extend: 'pdf'
                         ,orientation: 'landscape'
                         , footer: true 
                         ,exportOptions: {
                            columns: ':not(.notexport)'
                         } 
                      }
                      
                   ]
                    ,columnDefs: [
                        {  
                            targets: 7 
                            ,"createdCell": function(td, cellData, rowData, row, col) {
                                switch(cellData) {
                                case "CREADA":
                                        $(td).addClass('text-danger');
                                        break;
                                    case "RECOLECTADO":
                                        $(td).addClass('text-warning');
                                        break;
                                    case "TRANSITO":
                                        $(td).addClass('text-info');
                                        break;
                                    case "ENTREGADO":
                                        $(td).addClass('text-success');
                                        break;
                                }
                            }
                        }
                    ]
                    ,"columns": [
                        { "data": "id" }
                        ,{ "data": "mensajeria" }
                        ,{ "data": "tracking_number" }
                        ,{ "data": "servicio_nombre" }
                        ,{ "data": "nombre" }
                        ,{ "data": "ciudad" }
                        ,{ "data": "ciudad_d" }
                        ,{ "data": "rastreo_nombre" }
                        ,{ "data": "ultima_fecha" }
                        ,{ "data": "quien_recibio" }
                        ,{ "data": "rastreo_peso" }
                        ,{ "data": "largo" }
                        ,{ "data": "ancho" }
                        ,{ "data": "alto" }
                    ],
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

function rastreoActualizarAjax() {
$.ajax({
        url: 'api/rastreoActualizar',
        type: 'POST',
        async: false
        /* send the csrf-token and the input to the controller */
        ,headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        
        /* remind that 'data' is the response of the AjaxController */
    }).done(function( response) {
        console.log(response.data)
        
            
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