<?php

namespace App\Models\Saldos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldos extends Model
{
    use HasFactory;

    protected $fillable = ['monto', 'monto_anterior'];
}
