<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
        'price',
        'subtotal',
        'total',
        'sales_id',
        'product_id',
        'created_at',
        'updated_at',
    ];
}
