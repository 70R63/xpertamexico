<!--Row-->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card custom-card mg-b-20">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="exportGeneral" class="table table-striped table-bordered text-nowrap" >
                        <thead>
                            <tr>
                                <th class='notexport'>ID</th>
                                <th class='notexport'>GUIA</th>
                                <th>MENSAJERIA</th>
                                <th>TRACKING</th>
                                <th>SERVICIO</th>
                                <th>USUARIO</th>
                                <th>CLIENTE XPERTA</th>
                                <th>REMITENTE <br>(CONTACTO)</th>
                                <th>DESTINATATIO <br>(CONTACTO)</th>
                                <th >CREACION</th>
                                <th >CANAL</th>

                                <th style="display:none;">PIEZAS</th>
                                <th style="display:none;">PESO KG</th>
                                <th style="display:none;">DIMENSIONES</th>
                                <th style="display:none;">CP ORIGEN</th>
                                <th style="display:none;">CIUDAD ORIGEN</th>
                                <th style="display:none;">CP DESTINO</th>
                                <th style="display:none;">CIUDAD DESTINO</th>
                                <th style="display:none;">APLICA A.E.</th>
                                
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $tabla  as $objeto)
                            <tr>
                                <td>{{ $objeto->id }}</td>
                                <td>@include('guia.dashboard.documento')</td>
                                <td >{{ $ltdActivo[$objeto->ltd_id] }}</td>
                                <td>{{ $objeto->tracking_number }} </td>
                                <td>{{ $servicioPluck[$objeto->servicio_id] }}</td>
                                <td>{{ $objeto->usuario }}</td>
                                <td>{{ $objeto->nombre}}</td>
                                <td>{{ $objeto->contacto }}</td>
                                <td>{{ $objeto->contacto_d }}</td>
                                <td>{{ $objeto->created_at }}</td>
                                <td >{{ $objeto->canal }}</td>

                                <td style="display:none;">{{ $objeto->piezas }}</td>
                                <td style="display:none;">{{ $objeto->peso }}</td>
                                <td style="display:none;">{{ $objeto->dimensiones }}</td>
                                <td style="display:none;" >{{ $objeto->cp }}</td>
                                <td style="display:none;" >{{ $objeto->ciudad }}</td>
                                <td style="display:none;">{{ $objeto->cp }}</td>
                                <td style="display:none;" >{{ $objeto->ciudad_d }}</td>
                                <td style="display:none;" >{{ $objeto->extendida }}</td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                        
                        <tfoot>
                            <tr>
                              <td class='notexport' colspan="17">Los datos son responsalidad del usuario</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row-->
