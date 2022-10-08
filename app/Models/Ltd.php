<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ltd extends Model
{
    use HasFactory;

    protected $fillable = ['estatus','nombre','responsable_legal','email'];

}
