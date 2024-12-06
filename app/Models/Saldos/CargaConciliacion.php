<?php

namespace App\Models\Saldos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargaConciliacion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','empresa_id', 'fecha_factura', 'ltd_id', 'tracking_number', 'num_factura_ltd', 'servicio_id', 'fecha_envio', 'peso_facturado_ltd', 'alto_facturado_ltd', 'largo_facturado_ltd', 'ancho_facturado_ltd', 'subtotal_facturado_ltd', 'total_facturado_ltd', 'adicional_ae_ltd', 'adicional_seguro_ltd', 'adicional_envio_irregular_ltd', 'adicional_correcion_ltd', 'adicional_exceso_dimension_ltd'];
}
