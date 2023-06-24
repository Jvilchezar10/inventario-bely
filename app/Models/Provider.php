<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $table = "providers";
    protected $fillable = [
        'provider',
        'DNI',
        'RUC',
        'phone',
        'contact',
        'contact_phone',
        'created_at',
        'updated_at',
    ];
}
