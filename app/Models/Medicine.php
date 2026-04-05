<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name', 
        'batch_number', 
        'stock_quantity', 
        'low_stock_threshold', 
        'expiration_date', 
        'usage_history'
    ];
}