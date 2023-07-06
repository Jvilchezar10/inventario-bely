<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProofOfPayment extends Model
{
    use HasFactory;
    protected $table = 'proof_of_payments';
    protected $fillable = [
        'name',
        'state',
        'created_at',
        'updated_at',
    ];

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self())->getTable());
    }
}
