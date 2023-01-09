$(document).ready(function() {
    if ($('#guiasTabla').length) {
        guiasTabla()     
    }
   
}) 

function guiasTabla(){
    $.ajax({
        url: 'api/rastreoTabla',
        type: 'GET',
        /* send the csrf-token and the input to the controller */
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        
        
        /* remind that 'data' is the response of the AjaxController */
    }).done(function( response) {
        console.log(response.data)
        table = $('#guiasTabla').DataTable({
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
                             data.body[i][13] = '\0' + data.body[i][13];
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
                    ,columns: [
                        { "data": "id" }
                        ,{ "data": "mensajeria"
                                ,render: function(data, type, row){
                                    
                                return documentoRetorno(row); 
                            }
                        }
                        
                        ,{ "data": "mensajeria" }
                        ,{ "data": "servicio_nombre" }
                        ,{ "data": "usuario" }
                        ,{ "data": "nombre" }
                        ,{ "data": "contacto" }
                        ,{ "data": "contacto_d" }
                        ,{ "data": "cp" }
                        ,{ "data": "ciudad" }
                        ,{ "data": "cp_d" }
                        ,{ "data": "ciudad_d" }
                        ,{ "data": "created_at" }
                        ,{ "data": "canal" }
                        ,{ "data": "tracking_number" }
                        ,{ "data": "precio" }
                        ,{ "data": "piezas" }
                        ,{ "data": "peso" }
                        ,{ "data": "dimensiones" }
                        ,{ "data": "valor_envio" }
                        ,{ "data": "seguro" }
                        ,{ "data": "extendida" }

                    ],
                });
        table.columns( [8,9,10,11,15,16,17,18,19,20,21] ).visible( false );
            
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

function documentoRetorno(row){

    var doc = documento(row)
    var htmlRetorno = '<a href="#" id="retorno" name="retorno" class="text-nowrap tx-20" data-toggle="modal" data-target="#myModal" > \
        <i title="Retorno de la guia" class="si si-action-undo"> </i>       \
        </a>'

    return doc+htmlRetorno    
}

function documento(row){
    if (row.ltd_id==1) {
        cantidad = row.documento.split("|")
        var html = ""
        cantidad.forEach( (data) => {    
            html=html+ '<a href="'+data+'" target="_blank" rel="noopener noreferrer"><i class="text-info tx-20 fa fa-archive" data-toggle="tooltip" title="" data-original-title="fa fa-archive"></i></a>'
        })
        
    } else{
        html = '<a href="storage/'+row.documento+'" target="_blank" rel="noopener noreferrer"><i class="text-info tx-20 fa fa-archive" data-toggle="tooltip" title="" data-original-title="fa fa-archive"></i></a>'
    }
    return html;
}

$("body").on("click", 'tr', function (){
    var row = table.row(this).data(); 
    $("#spanRemitente").text( row.contacto );
    $("#spanDestinatario").text( row.contacto_d );
    $("#spanNuevoRemitente").text( row.contacto_d );
    $("#spanNuevoDestinatario").text( row.contacto );
    $("#spanID").text( row.id );

    $("#guia_id").val( row.id );

});

