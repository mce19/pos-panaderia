<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = 'configurations';

    protected $fillable = [
        'business_name',
        'address',
        'phone',
        'taxpayer_id',
        'vat',
        'printer_name',
        'leyend',
        'website',
        'credit_days'
    ];
}
