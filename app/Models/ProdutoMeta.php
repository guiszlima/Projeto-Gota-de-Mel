<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoMeta extends Model
{
    protected $connection = 'second';
    protected $table = 'wp_postmeta';
    protected $primaryKey = 'meta_id';
    public $timestamps = false;

    protected $fillable = ['post_id', 'meta_key', 'meta_value'];
}
