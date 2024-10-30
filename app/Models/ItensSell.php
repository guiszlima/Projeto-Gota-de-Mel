<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItensSell extends Model
{
    use HasFactory;
protected $table = 'itens_sells'; 
   protected $fillable = [
        'product_id',
        'sell_id',
       
    ];
    public $timestamps = false;
}