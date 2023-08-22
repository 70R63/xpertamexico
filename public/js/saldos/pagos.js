$(document).ready(function() {
    console.log("document ready tablaSaldosPagosResumenAjax")
    if ($('#tablaSaldosPagosResumenAjax').length) {
        console.log("Inicializar tablaSaldosPagosResumenAjax")
        tablaSaldosPagosResumen()
        var table = null;  
    }

    if ($('#tablaSaldosPagosAjax').length) {
        console.log("Inicializar tablaSaldosPagosAjax")
        tablaSaldosPagos()
        var table = null;  
    }

});

function linkPagos(row){

    //var htmlRetorno = '<span> '+ row.nombre+'  </span>';
    html='<a href="pagos/'+row.empresa_id+'" rel="noopener noreferrer" class="text-dark"> \
            '+row.nombre +'</a>';

    return html;
}

function tablaSaldosPagosResumen(){
 $.ajax({
        url: '../api/saldos/pagos/resumen',
        type: 'GET',
        /* send the csrf-token and the input to the controller */
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //data: $('#reporteRepesajesForm').serialize()
        
        /* remind that 'data' is the response of the AjaxController */
    }).done(function( response) {
        console.log(response.data)
        table = $('#tablaSaldosPagosResumenAjax').DataTable({
                "oLanguage": {
                    "sEmptyTable": "No se puede mostrar los registros"
                }
                //,searching: false
                ,bSortCellsTop: true
                ,responsive: true
                ,processing: true
                ,pagingType: "full_numbers"
                ,deferRender: true
                ,bDestroy: true
                ,data: response.data
                ,autoWidth: false
                ,order: [[0, 'asc']]
                ,lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10', '25', '50', 'Todo' ]
                ]
                ,dom: 'lrt'
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
                    { "data": "nombre" 
                        ,render: function(data, type, row){   
                                return linkPagos(row); 
                            }
                    }
                    ,{ "data": "importe" }
                    ,{ "data": "importe7" }
                    ,{ "data": "importe15" }
                    ,{ "data": "importe30" }
                    
                ],
            });
        //table.columns( [12] ).visible( false );

            
    }).fail( function( data,jqXHR, textStatus, errorThrown ) {
        console.log( "fail" );
        console.log(data);
        swal(
            "Error!",
            textStatus,
            "error"
          )


    }).always(function() {
        console.log( "complete tablaSaldosPagosResumenAjax" );
    });

   
$('input.search').on('keyup change', function () {
    var rel = $(this).attr("rel");
    table.columns(rel).search(this.value).draw();
});

};


function tablaSaldosPagos(){
    var url_params = new URLSearchParams(window.location.search);
    var url_params = window.location;
    console.log(url_params)
 $.ajax({
        url: '../../api/saldos/pagos/88',
        type: 'GET',
        /* send the csrf-token and the input to the controller */
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //data: $('#reporteRepesajesForm').serialize()
        
        /* remind that 'data' is the response of the AjaxController */
    }).done(function( response) {
        console.log(response.data)
        table = $('#tablaSaldosPagosAjax').DataTable({
                "oLanguage": {
                    "sEmptyTable": "No se puede mostrar los registros"
                }
                //,searching: false
                ,bSortCellsTop: true
                ,responsive: false
                ,processing: true
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
                    { "data": "empresa_nombre" }
                    ,{ "data": "importe" }
                    ,{ "data": "banco_nombre" }
                    ,{ "data": "referencia" }
                    ,{ "data": "fecha_deposito" }
                    
                ],
            });
        //table.columns( [12] ).visible( false );

            
    }).fail( function( data,jqXHR, textStatus, errorThrown ) {
        console.log( "fail" );
        console.log(data);
        swal(
            "Error!",
            textStatus,
            "error"
          )


    }).always(function() {
        console.log( "complete tablaSaldosPagosResumenAjax" );
    });

   
$('input.search').on('keyup change', function () {
    var rel = $(this).attr("rel");
    table.columns(rel).search(this.value).draw();
});

};