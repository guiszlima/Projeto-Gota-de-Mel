<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $fillable = [
        'sell_id',
        'product_ids', // IDs de produtos em formato JSON
    ];

    /**
     * Define que `product_ids` será convertido automaticamente de/para um array JSON.
     */


    /**
     * Relacionamento com `ReportSell`.
     */
    public function Sell()
    {
        return $this->belongsTo(Sell::class);
    }
}