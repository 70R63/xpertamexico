var table;
var peso;
var piezas = 0;
var costoSeguro = 0;
var valorEnvio = 0;
var costoPesoExtra = 0;
var dimensional = 0;
var bascula = 0;
var sobrePesoKg = 0;
var costoCoberturaExtendida = 0;
var costoKgExtra = 0;
var saldoNegativo = false;
var saldoMinimo = 90;
var esDimensionExcedidaDhl = false
var esPesoExcedidoDhl = false
var esPzaNoConvencionalDhl = false
var costosAdicionales = 0;
var empresaObj = null;

function pesoDimensionalyBascula(){

    var piezas = $("#piezas").val()
    var iteracionClone = 0
    $('.registroMultipieza').each(function(){
        console.log("--------------"+iteracionClone)
        var control = +iteracionClone *4
        var indexPeso = 0 +control
        var indexLargo = 1 +control
        var indexAncho = 2 +control
        var indexAlto = 3 +control 
        

        var peso = $('.registroMultipieza .multi').get()[indexPeso].value
        var largo = $('.registroMultipieza .multi').get()[indexLargo].value
        var ancho = $('.registroMultipieza .multi').get()[indexAncho].value
        var alto = $('.registroMultipieza .multi').get()[indexAlto].value
        
        if ($('.registroMultipieza').length == 1){
            pesoBascula = peso*piezas
            pesoDimensional = (((alto*ancho*largo)/5000)*piezas)    
            
        }else{
            pesoBascula = peso
            pesoDimensional = ((alto*ancho*largo)/5000)
        }
        
        console.log("bascula "+pesoBascula+ ">"+ pesoDimensional+" dimensional")
        iteracionClone++

    })  

    pesoFacturado = pesoFacturado + ((bascula > dimensional) ? Math.ceil(bascula) : Math.ceil(dimensional));
    return pesoFacturado;
}

function pesofacturado(){

    var peso = 0
    var piezas = $("#piezas").val()
    var iteracionClone = 0
    var pesoFacturado =+0

    bascula = +0
    dimensional =+0

    $('.registroMultipieza').each(function(){
        console.log("--------------"+iteracionClone)
        var control = +iteracionClone *4
        var indexPeso = 0 +control
        var indexLargo = 1 +control
        var indexAncho = 2 +control
        var indexAlto = 3 +control 
        

        var peso = $('.registroMultipieza .multi').get()[indexPeso].value

        esPzaNoConvencionalDhl = false
        if (peso >= 25 && peso < 70 ) {
            esPzaNoConvencionalDhl = true
        }

        var largo = $('.registroMultipieza .multi').get()[indexLargo].value
        var ancho = $('.registroMultipieza .multi').get()[indexAncho].value
        var alto = $('.registroMultipieza .multi').get()[indexAlto].value

        esDimensionExcedidaDhl  = false
        if (largo >=100 || ancho >=100 || alto >=100) {
            esDimensionExcedidaDhl = true
        }

        if ($('.registroMultipieza').length == 1){
            bascula = peso*piezas
            dimensional = (((alto*ancho*largo)/5000)*piezas)    
            
        }else{
            bascula = peso
            dimensional = ((alto*ancho*largo)/5000)
        }
        
        pesoFacturado = pesoFacturado + ((bascula > dimensional) ? Math.ceil(bascula) : Math.ceil(dimensional));
        console.log("bascula "+bascula+ ">"+ dimensional+" dimensional")
        iteracionClone++

    })       
    console.log("peso facturado = "+pesoFacturado)
    esPesoExcedidoDhl = false
    if (pesoFacturado >=70 ) {
        esPesoExcedidoDhl = true
    }
    $("#pesoFacturado").val(pesoFacturado);
}

function costoSeguroValidar(seguro){
    costoSeguro = 0;
    valorEnvio = 0;
    
    
    if ($('#checkSeguro').is(":checked")) {
        valorEnvio = $("#valor_envio").val();
        costoSeguro = (valorEnvio * seguro)/100;  
    }
    return costoSeguro;
}

function preciofinal(dataRow){
    //Variable Global;

    piezas = $('#piezas').val();
    peso = $('#pesoFacturado').val();
    costoCoberturaExtendida = 0;
    costoPesoExtra = 0;
    
    costoSeguroValidar(dataRow.seguro);

    if (peso > dataRow.kg_fin) {
        sobrePesoKg = peso - dataRow.kg_fin ;
        costoPesoExtra = sobrePesoKg * dataRow.kg_extra ;
        costoKgExtra = dataRow.kg_extra;
    }

    var textAreaExtendida = dataRow.extendida_cobertura.toUpperCase()
    if ( textAreaExtendida == "SI"){
        costoCoberturaExtendida = dataRow.extendida
        console.log(costoCoberturaExtendida);
    } 

    console.log("CA " + costosAdicionales)
    precioTotalSinIva = parseFloat(dataRow.costo+ costoPesoExtra + costoSeguro + costoCoberturaExtendida + costosAdicionales ).toFixed(2);
    return parseFloat(precioTotalSinIva)
}

function fechaTentativa(row){
    
    var ahora = new Date();
    var diaLaboral = 0
    
    ahora.setDate(ahora.getDate()+row.tiempo_entrega)

    if (ahora.getDay() === 0 || ahora.getDay() === 6 )
        diaLaboral = 2

    ahora.setDate(ahora.getDate()+diaLaboral)
    return ahora.toLocaleDateString('es-MX');
}

function obtenerCP(id, modelo) {

    $.ajax({
        /* Usar el route  */
        url: "api/cp",
        type: 'GET',
        /* send the csrf-token and the input to the controller */
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: "id="+id+"&modelo="+modelo
        
        /* remind that 'data' is the response of the AjaxController */
        }).done(function( response) {
            console.log("done");
            var contador = response.data.length

            if ("Sucursal" == modelo) {
                if (contador == 1) {
                    $("#cp").val(response.data[0].cp);    
                } else {
                    $("#cp").val("00000");
                }
                
            } else {
                if (contador == 1) {
                    $("#cp_d").val(response.data[0].cp);    
                } else {
                    $("#cp_d").val("00000");
                }
            }
            
        
        }).fail( function( data,jqXHR, textStatus, errorThrown ) {
            console.log( "fail" );
            console.log(textStatus);
            
            alert( data.responseJSON.message);

        }).always(function() {
            console.log( "complete" );
        });

}

function validaSaldo(response){
    console.log("validaSaldo")

    if (response.data.tipoPagoId == 2) {
         if (response.data.saldo <saldoMinimo) {
            saldoNegativo = true;
            swal(
                "El Saldo: "+response.data.saldo +" es menor al limite permitido",
                "Revisar con tu Administrador!",
                "error"
              )    
        }
        
        if (response.data.saldo <0) {
            saldoNegativo = true;
            swal(
                "Saldo Negativo: $"+response.data.saldo ,
                "Revisar con tu Administrador!",
                "error"
              )    
        }

       
    } else {

    }

    //tipo_pago_id
}


//actividades al vuelo

$("#limpiar").click(function() {
    $('#cotizacionesForm').trigger('reset');
    table.clear().draw();
    $('#cotizacionesForm').parsley().reset();
    //validar se se puede reutilizar
    $(".checkManualHtml").show()
    $(".clienteCombo").hide()
    $("#clienteIdCombo").removeAttr("required");
    $(".cotizacionManual").attr("readonly","true");
    $("#sucursal").attr("required","true");
    $("#cliente").attr("required","true");
    $("#cliente_id").removeAttr("required");
    
    $(".checkSemiHtml").show()
    $(".cotizacionSemi").attr("readonly","true");
    $("#esManual").val("NO");
    //FIN validar se se puede reutilizar

});


$("#cotizar").click(function(e) {
    console.log("cotizar")
    e.preventDefault();

    var form = $('#cotizacionesForm').parsley().refresh();
    var action = $('#cotizacionesForm').attr("action"); 
   
    saldoNegativo = false;
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

                empresaObj = response.data.empresaObj;

                validaSaldo(response)

                table = $('#cotizacionAjax').DataTable({
                    "oLanguage": {
                        "sEmptyTable": "No exiten tarifas con los datos para cotizar"
                    },
                    dom: '<"title"<"filter"f>>rtip',
                    
                    "processing": true,
                    "bDestroy": true,
                    order: [[1, 'desc']]

                    ,"data": response.data.data,
                    columnDefs: [
                        {  
                            targets: 10 
                            ,"createdCell": function(td, cellData, rowData, row, col) {
                                switch(cellData) {
                                    case "SI":
                                        $(td).addClass('text-danger si si-info');
                                        break;
                                    case "NO":
                                        $(td).addClass('text-success');

                                }
                            }
                        }
                    ]
                    ,"columns": [
                        { "data": "id" },
                        { "data": "nombre" },
                        { "data": "servicios_nombre" },
                        { "data": "fecha_tentativa" 
                            ,render: function (data, type, row) {
                                return fechaTentativa(row);   
                            } 
                        },
                        { "data": "zona" },
                        { "data": "costo" 
                            ,render: function (data,row) {
                                return '$ '+data;
                            } 
                        },
                        { "data": "kg_ini" },
                        { "data": "kg_fin" },
                        { "data": "kg_extra" },
                        { "data": "ocurre" },
                        { "data": "extendida_cobertura" },
                        { "data": "extendida" },
                        { "data": "seguro"
                            ,render: function (data, type, row, meta) {
                                return '$ '+costoSeguroValidar(row.seguro);   
                            } 
                        },
                        { "data": "otros" 
                            ,render: function (data, type, row, meta) {
                                return '$ '+costoAdicional(row,response.data.empresaObj);   
                            }
                        },
                        { "data": "costo_total"
                            ,render: function (data, type, row, meta) {
                                return '$ '+preciofinal(row);   
                            } 
                        }
                    ],
                    "autoWidth": false,
                });
                
            }).fail( function( data,jqXHR, textStatus, errorThrown ) {
                console.log( "fail" );
                console.log(textStatus);
                
                swal(
                    "Error!",
                    data.responseJSON.message,
                    "error"
                  )
                location.reload();

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
   
   var todoOk = true; 
   var todoOkMensaje = "Consulta a tu Administrador"; 
   let objFrecuencia = {}

   let mensajeSwalTitulo = "Error!"

    if (saldoNegativo) {
        swal(
            "Por el monento no puede hacer guias",
            "Revisar con tu Administrador!",
            "error"
          ) 

    } else {
        

        var dataRow = table.row(this).data(); 

        costoAdicional(dataRow ,empresaObj)

        //Valores de la cotizacion de la Forma Cotizacion
        var sucursal_id = $('#sucursal').val();
        var cliente_id = $('#cliente').val();
        var cp = $('#cp').val();
        var cp_d = $('#cp_d').val();
        var largo = $('#largo').val();
        var ancho = $('#ancho').val();
        var alto = $('#alto').val();
        var bSeguro = ( $('#checkSeguro').is(":checked") ? true : false);
        var valorEnvio = $('#valor_envio').val();
        var contenido = $('#contenido').val();
        var esManual = $("#esManual").val();
        var empresaId = $("#clienteIdCombo").val();

        //Inicializacion de variables del renglon de la cotizacion
        var tarifa_id = table.row(this).data()['id'];
        var ltd_nombre = table.row(this).data()['nombre'];
        var ltd_id = table.row(this).data()['ltds_id'];
        var servicioNombre = dataRow['servicios_nombre'];
        var servicioId  = dataRow['servicio_id'];
        var precio =  preciofinal(dataRow);
        var iva = precio*0.16;
        var precioIva = (precio+iva).toFixed(2);
        var ocurre  = dataRow['ocurre'];
        var areaExtendida  = dataRow['extendida_cobertura'];
        var zona  = dataRow['zona'];
        var costoBase  = dataRow['costo'];
       

        //valores para el modal resumen_cotizacion.blade
        $("#spanPrecio").text( precioIva );
        $("#spanMensajeria").text(ltd_nombre);
        $("#spanservicioId").text(servicioNombre);
        $("#spanRemitente").text(cp);
        $("#spanDestinatario").text(cp_d);
        $("#spanPieza").text(piezas);
        $("#spanSeguro").text(costoSeguro);
        $("#spanValorEnvio").text(valorEnvio);
        $("#spanPeso").text(peso);
        $("#spanCotizacionManual").text(esManual);
        $("#spanOcurre").text(ocurre);
        $("#spanAreaExtendida").text(areaExtendida);
        $("#spanZona").text(zona);

        $("#spanDimensionExcedida").text(dataRow.dimension_excedida);
        $("#spanPesoExcedido").text(dataRow.peso_excedido);
        $("#spanPzaNoConvencional").text(dataRow.pza_no_convencional);

        //valores para request, campos ocultos guiastore_ocultos -> card_preciofinal
        pesofacturado()
        $("#precio").val(precioIva);
        $("#tarifa_id").val(tarifa_id);
        $("#sucursal_id").val(sucursal_id);
        $("#cliente_id").val(cliente_id);
        $("#ltd_nombre").val(ltd_nombre);
        $("#ltd_id").val(ltd_id);
        $("#piezas_guia").val(piezas);
        $("#servicio_id").val(servicioId);
        $("#peso_facturado").val(peso);
        $("#bSeguro").val(bSeguro);
        $("#costo_seguro").val(costoSeguro);
        $("#contenido_r").val(contenido);
        $("#extendida_r").val(areaExtendida);
        $("#valor_envio_r").val(valorEnvio);
        $("#esManual").val(esManual);
        $("#cp_manual").val(cp);
        $("#cp_d_manual").val(cp_d);
        $("#empresa_id").val(empresaId);
        $("#ocurre").val(ocurre);
        $("#zona").val(zona);
        $("#costo_base").val(costoBase);
        $("#costo_kg_extra").val(costoPesoExtra); //costoKgExtra
        $("#peso_dimensional").val(dimensional);
        $("#peso_bascula").val(bascula);
        $("#sobre_peso_kg").val(sobrePesoKg);
        $("#costo_extendida").val(costoCoberturaExtendida);
        
        $("#dimension_excedida").val(dataRow.dimension_excedida);
        $("#peso_excedido").val(dataRow.peso_excedido);
        $("#pza_no_convencional").val(dataRow.pza_no_convencional);
        
        
        var iteracionClone = 0
        var pesos = []
        var largos = []
        var anchos = []
        var altos = []

        $('.registroMultipieza').each(function(){
            console.log("--------------"+iteracionClone)
            var control = +iteracionClone *4
            var indexPeso = 0 +control
            var indexLargo = 1 +control
            var indexAncho = 2 +control
            var indexAlto = 3 +control 
            

            var peso = $('.registroMultipieza .multi').get()[indexPeso].value
            var largo = $('.registroMultipieza .multi').get()[indexLargo].value
            var ancho = $('.registroMultipieza .multi').get()[indexAncho].value
            var alto = $('.registroMultipieza .multi').get()[indexAlto].value
            
            pesos.push(peso)
            largos.push(largo)
            anchos.push(ancho)
            altos.push(alto)
            iteracionClone++
        })      

        $("#pesos").val(pesos);
        $("#largos").val(largos);
        $("#anchos").val(anchos);
        $("#altos").val(altos);

        console.debug( ltd_nombre )
        switch (ltd_nombre) {
            case "FEDEX":

                if (servicioNombre === "Terrestre" || servicioNombre === "Dia Sig.") {
                    fedexMaximaDimension = (parseFloat(largos) + parseFloat(2*anchos) + parseFloat(2*altos)).toFixed(1);
                    console.info("FEDEX DIMENSIONAL")
                    console.info( largos + " "+ (2 * anchos)  + " "+ (2* altos) )
                    console.info(fedexMaximaDimension)
                    if ( fedexMaximaDimension > 330 ) {
                        todoOk = false;
                        todoOkMensaje = "Las dimensiones exceden el limite permitido de 330 cm"
                    }

                }
            break;
                
            case "ESTAFETA":
                mensajeSwalTitulo = ltd_nombre
                
                console.debug("CASOS ESTAFETA")
                objFrecuencia = frecuenciaLtd(cp, cp_d)

                console.debug( objFrecuencia )
                
                if (objFrecuencia.origin === null){
                    console.debug( "origin es null" )
                    todoOk = false

                }

                if (objFrecuencia.destinations === null){
                    console.debug( "destinations es null" )
                } else {
                    if (objFrecuencia.destinations[0].result.code != 0){
                        todoOk = false
                        todoOkMensaje = objFrecuencia.destinations[0].result.description
                    }    
                }

                if (objFrecuencia.origin[0].result.code != 0){
                    todoOk = false
                    todoOkMensaje = objFrecuencia.origin[0].result.description
                }

                
                
                
            break;
            
            default:
                console.info("DEFAULT CASOS LTD")
        }

        if ( todoOk ) {
            $("#myModal").modal("show");
        } else {
            swal(
                mensajeSwalTitulo,
                todoOkMensaje,
                "error"
            );

        }
        

    }
    
});


$("#sucursal").change(function() {
    var idSucursal = $('#sucursal').val();
    var empresa_id = $('#empresasCmb').val();

    console.log("sucursal "+idSucursal)
    obtenerCP(idSucursal, "Sucursal");
           
}); 

$("#cliente").change(function() {
    var idCliente = $('#cliente').val();
    console.log("sucursal "+idCliente)
    obtenerCP(idCliente, "Cliente");
});

$(function(){
    $(".multi").on("change keyup paste", function (){
        pesofacturado();
    });

    
    $("#handleCounterMax28").click( function (){
        pesofacturado();
    });
    
});

function obtenerClientes() {

    $.ajax({
        /* Usar el route  */
        url: route('api.clientes'),
        type: 'GET',
        /* send the csrf-token and the input to the controller */
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //data: "clienid="+id
        
        /* remind that 'data' is the response of the AjaxController */
        }).done(function( response) {
            console.log("done");
            //console.log(response.data);
           
            $('#clienteIdCombo').empty();
            $("#clienteIdCombo").append('<option selector="0" value="0"> TODOS</option>');
            
            $.each(response.data,function(key, empresa) {
                $("#clienteIdCombo").append('<option selector='+key+' value="'+empresa.id+'" >'+empresa.nombre+'</option>');
              });   
            
        
        }).fail( function( data,jqXHR, textStatus, errorThrown ) {
            console.log( "fail" );
            console.log(textStatus);
            
            swal(
                "Error!",
                data.responseJSON.message,
                "error"
              );
            

        }).always(function() {
            console.log( "complete" );
        });

}


function direccionesPorEmpresa(tipo, empresa_id=1){
    console.log( "direccionesPorEmpresa" );

    $.ajax({
        /* Usar el route  */
        //url: route('api.cp.colonias'), 
        url: route('api.direcciones.tipo', [tipo]),
        type: 'GET',
        /* send the csrf-token and the input to the controller */
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: "empresa_id="+empresa_id
        
        /* remind that 'data' is the response of the AjaxController */
        }).done(function( response) {
            console.log("done direccionesPorEmpresa");
           
            if (tipo === "remitente") {
                $('#sucursal').empty();
            
                $.each(response.data,function(key, empresa) {
                    $("#cp").val(response.data.values(0).toArray()[0].cp);

                    $("#sucursal").append('<option selector='+key+' value="'+empresa.id+'" >'+empresa.nombre+'</option>');
                }); 

            } else {
                $('#cliente').empty();
            
                $.each(response.data,function(key, empresa) {
                    $("#cp_d").val(response.data.values(0).toArray()[0].cp);

                    $("#cliente").append('<option selector='+key+' value="'+empresa.id+'" >'+empresa.nombre+'</option>');
                });    
            }
            
      
        }).fail( function( data,jqXHR, textStatus, errorThrown ) {
            console.log( "fail" );
            console.log(textStatus);
            
            swal(
                "Error!",
                data.responseJSON.message,
                "error"
              );
            
        }).always(function() {
            console.log( "complete" );
    });
    
}

$("#addRow").click(function () {
    console.log('AddRow')
    var piezas = $("#piezas").val()  
    var multipiezas = $(".registroMultipieza").length;
    
    
    var html = $("#clone").clone(true,true)

    $('.registroMultipieza').each(function( index ) {
        $(this).remove();    
        
    });

    console.log("piezas -> "+piezas)
    for (let i = 0; i < piezas ; i++) {
        console.log("iteracion piezas")
        html.clone(true,true).appendTo( "#multiPieza" ).show()
    }

    pesofacturado();
    
});

// La fucnion habilita el modo edicion de los CP, esto ayuda a realizar una cotizacion manual basda en tarifias de un cliente

$('#checkCotizacionManual').change(function() {

    console.log("checkCotizacionManual");
    var checkSeguro = $(this).is( ":checked" )
    if ( checkSeguro ) {
        $("#cliente").removeAttr("required");
        $(".cotizacionSemi").removeAttr("readonly");
        //$("cotizacionSemi").removeAttr("required");
        $("#esManual").val("SEMI");
        $(".checkSemiHtml").hide()
    } else {
        $(".checkSemiHtml").show()
        $(".cotizacionSemi").attr("readonly","true");
        $("#cliente").attr("required","true");
        $("#esManual").val("NO");
    }
  });

$('#checkManual').change(function() {

    console.log("checkManualHtml");
    var check = $(this).is( ":checked" )
    if ( check ) {
        $(".checkManualHtml").hide()
        $(".clienteCombo").show()
        $("#clienteIdCombo").attr("required","true");
        obtenerClientes()
        $(".cotizacionManual").removeAttr("readonly");
        $("#sucursal").removeAttr("required");
        $("#cliente").removeAttr("required");
        $("#cliente_id").attr("required","true");
        $("#esManual").val("SI");
        
    } else {
        $(".checkManualHtml").show()
        $(".clienteCombo").hide()
        $("#clienteIdCombo").removeAttr("required");
        $(".cotizacionManual").attr("readonly","true");
        $("#sucursal").attr("required","true");
        $("#cliente").attr("required","true");
        $("#cliente_id").removeAttr("required");
        $("#esManual").val("NO");
    }
});

function costoAdicional(row, data){

    row.dimension_excedida = 0
    row.peso_excedido = 0
    row.pza_no_convencional = 0
    
    //variable global
    costosAdicionales= 0
    

    switch (row.nombre) {
      case "DHL":
        console.log("LTD 4 ---------------")
        pesofacturado()  
        var costoDimensionExcedidaDhl = 0
        if (esDimensionExcedidaDhl) {
            costoDimensionExcedidaDhl = data.dimension_excedida
            row.dimension_excedida = data.dimension_excedida
        }

        var costoPesoExcedidoDhl = 0
        if (esPesoExcedidoDhl) {
            costoPesoExcedidoDhl = data.peso_excedido
            row.peso_excedido = data.peso_excedido
        }

        var costoPzaNoConvencionalDhl = 0
        if (esPzaNoConvencionalDhl) {
            costoPzaNoConvencionalDhl = data.pza_no_convencional
            row.pza_no_convencional = data.pza_no_convencional
        }

        costoExtraDHL = costoDimensionExcedidaDhl + costoPesoExcedidoDhl + costoPzaNoConvencionalDhl
        costosAdicionales = costoExtraDHL
        console.log( "funcion costoAdicional " + costosAdicionales)

        break;
      case "FEDEX":
        console.log("LTD 1 ---------------")

        costosAdicionales = row.costo_adicional_dimension + row.costo_adicional_peso;
        
        break;
      // ... more cases
      default:
        console.log("LTD 0 ---------------")
        costosAdicionales = 0
        
    }

    costosAdicionales = parseFloat(costosAdicionales) 
    return costosAdicionales;
}

//cjhs - 20250602
$("#empresasCmb").change(function() {
    var empresa_id = $('#empresasCmb').val();
    console.log("empresa_id "+empresa_id)
    
    direccionesPorEmpresa("remitente", empresa_id)
    direccionesPorEmpresa("destinatario", empresa_id)
               
}); 

//CJHS - 20251031
function  frecuenciaLtd(cp, cp_d){
    console.debug("fercuencia")
    let data = null
    $.ajax({
        /* Usar el route  */
        //url: route('api.cp.colonias'), 
        url: route('ajax.v1.ltds.estafeta.frecuencia', [cp ,cp_d]),
        type: 'GET',
        /* send the csrf-token and the input to the controller */
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //data: "empresa_id="+empresa_id
        async : false,
        
        /* remind that 'data' is the response of the AjaxController */
        }).done(function( response) {
            console.debug( "done frecuenciaLtd" );
            console.debug( response.data );
            data = response.data 
           
        }).fail( function( data,jqXHR, textStatus, errorThrown ) {
            console.log( "fail" );
            console.log(textStatus);
            
            swal(
                "Error!",
                data.responseJSON.message,
                "error"
              );
            
        }).always(function() {
            console.debug( "complete" );
    });
    
   return data

}//fin frecuenciaLtd

$(document).ready(function() {



});