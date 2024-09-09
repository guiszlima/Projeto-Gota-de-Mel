<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSell extends Model
{
    use HasFactory;
    protected $table = 'report_sells';
    protected $fillable = ['product_id', 'nome', 'preco','CPF','pagamento','type'];
}