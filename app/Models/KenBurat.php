<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KenBurat extends Model
{
    protected $table = 'ken_burat';

    protected $fillable = [
        'name',
        'description',
        'is_pogi'
    ];
}