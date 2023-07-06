<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;
use App\Models\Purchas;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class PurchasesDetail extends Model
{
    use HasFactory;
    protected $table='purchases_details';

    protected $fillable = [
        'purchase_id',
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

    public function purchas()
    {
        return $this->belongsTo(Purchas::class, 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
