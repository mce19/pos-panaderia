<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'flete',
        'discount',
        'items',
        'status',
        'type',
        'supplier_id',
        'user_id',
        'notes',
    ];

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }


    function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name');
    }
}
