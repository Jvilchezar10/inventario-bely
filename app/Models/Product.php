<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $fillable = [
        'id',
        'cod_product',
        'category_id',
        'desc',
        'color',
        'size',
        'stock_min',
        'stock',
        'purchase_price',
        'sale_price',
        'created_at',
        'updated_at',
    ];

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self())->getTable());
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
