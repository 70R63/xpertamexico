<?php

namespace App\Models\Saldos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuiasExternas extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','estatus','no_guias', 'importe_total', 'file_nombre'];
}
