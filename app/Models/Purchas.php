<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Provider;
use App\Models\ProofOfPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchas extends Model
{
    use HasFactory;
    protected $table='purchases';
    protected $fillable = [
        'proof_payment_id',
        'voucher_number',
        'employee_id',
        'purchase_code',
        'purchase_date',
        'provider_id',
        'total',
        'created_at',
        'updated_at',
    ];

    public function proofofpayment() : BelongsTo
    {
        return $this->belongsTo(ProofOfPayment::class);
    }

    public function provider() : BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
