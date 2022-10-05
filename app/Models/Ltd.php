<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ltd extends Model
{
    use HasFactory;

    protected $fillable = ['estatus','nombre','responsable_legal','email'];

    public function scopeLtdEmpresa($query,$empresa_id) {
    
       return $query->join('empresa_ltds', 'id', '=', 'empresa_ltds.empresa_id')
    }
}
