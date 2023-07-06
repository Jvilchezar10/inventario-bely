<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProofOfPayment;
use App\Models\Employee;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchas extends Model
{
    use HasFactory;
    protected $table = 'purchases';

    protected $fillable = [
        'proof_of_payment_id',
        'voucher_number',
        'employee_id',
        'purchase_code',
        'purchase_date',
        'provider_id',
        'origin',
        'total',
        'created_at',
        'updated_at',
    ];

    public function proofofpayment(): BelongsTo
    {
        return $this->belongsTo(ProofOfPayment::class, 'proof_of_payment_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function purchasesDetails()
    {
        return $this->hasMany(PurchasesDetail::class, 'purchase_id');
    }
}
