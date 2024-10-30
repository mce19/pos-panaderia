<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'sale_id', 'amount', 'pay_way', 'type', 'bank', 'account_number', 'deposit_number'];

    function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
