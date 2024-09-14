<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCreate extends Model
{
    use HasFactory;
    protected $table = 'report_create';
    protected $fillable = ['product_id', 'nome','estoque','estante','prateleira', 'preco', 'type'];
}