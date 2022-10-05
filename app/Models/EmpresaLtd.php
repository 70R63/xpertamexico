<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaLtd extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function scopeLtdEmpresa($query) {
    
       return $query->join('ltds', 'ltds.id', '=', 'ltd_id');
    }
}
