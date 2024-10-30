<?php

namespace App\Models;

use App\Models\Delivery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'email', 'phone', 'type'
    ];

    function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
