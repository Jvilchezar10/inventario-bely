<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProofOfPayment;
use App\Models\Employee;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'proof_of_payment_id',
        'voucher_number',
        'employee_id',
        'sales_code',
        'sales_date',
        'client_id',
        'total',
        'created_at',
        'updated_at',
    ];

    public function proofofpayment() : BelongsTo
    {
        return $this->belongsTo(ProofOfPayment::class);
    }

    public function employee() : BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
