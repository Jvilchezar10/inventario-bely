<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;
use App\Models\Sales;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'created_at',
        'updated_at',
    ];

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self())->getTable());
    }

    public function sales()
    {
        return $this->belongsTo(Sal::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
