<div class="card-item">
    <div class="table-responsive">
    	<table id="cotizacionAjax" class="table table-striped table-bordered text-nowrap" >
    		<thead>
                <tr>
                    <th>LTD</th>
                    <th>kg Inicial</th>
                    <th>kg Final</th>
                    <th>Costo Base</th>
                    <th>$ kg Extra.</th>
                    <th>$ Area Extendida</th>
                    <th>COSTO TOTAL</th>
                    
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>LTD</th>
                    <th>kg Inicial</th>
                    <th>kg Final</th>
                    <th>Costo Base</th>
                    <th>$ kg Extra.</th>
                    <th>$ Area Extendida</th>
                    <th>COSTO TOTAL</th>
                    
                </tr>
            </tfoot>		
    	</table>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">
                    <span id="spanTitulo"> </span>
                </h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card custom-card pricingTable2">
                    <div class="pricingTable2-header">
                        <h3>PRECIO </h3>
                    </div>
                    <div class="pricing-plans  bg-primary">
                        <span class="price-value1">
                            $<span id="spanPrecio"></span>
                        </span>
                    </div>
                    <div class="pricingContent2">
                        <ul>
                            <li><b>Mensajeria:</b> <span id="spanMensajeria"> </span></li>
                            <li><b>Remitente Postal:</b> <span id="spanRemitente"> </span></li>
                            <li><b>Destinatorio Postal:</b> <span id="spanDestinatario"> </span></li>
                            <li><b>Piezas:</b> <span id="spanPieza"></span></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="" class="btn btn-primary" >Continuar</a>
                    <a class="btn badge-dark" data-dismiss="modal" type="button">Cerrar</a>
                </div>
            </div>
        </div>
    </div>
</div>