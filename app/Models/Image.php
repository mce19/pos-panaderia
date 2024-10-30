<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'model_type',
        'file',
    ];

    /*
    La función image() es una relación polimórfica que se utiliza para establecer una relación entre el modelo Image y otros modelos.
     La relación polimórfica permite que un modelo tenga una relación con varios modelos diferentes en la base de datos.
    */
    public function image()
    {
        return $this->morphTo(); //La función morphTo() se utiliza para definir el tipo de relación polimórfica.
    }
}
