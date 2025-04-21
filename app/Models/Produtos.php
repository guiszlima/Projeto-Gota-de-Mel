<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    use HasFactory;
    protected $connection = 'second'; // Conexão secundária
    protected $table = 'wp_posts'; // A tabela onde os produtos estão no WooCommerce
    protected $primaryKey = 'ID';
    public $timestamps = false;

    // Relacionamento com meta (valores como preço, sku, etc.)
   
    public static function listProducts($parametroNome = null, $parametroValor = null, $perPage = 40, $all = null)
    {
        $query = self::queryBase($all)
            ->select(['ID', 'post_title', 'post_type', 'post_status']) // apenas se apropriado
            ->with(['metas', 'variations.metas']);
    
        if ($parametroNome && $parametroValor) {
            switch (strtolower($parametroNome)) {
                case 'id':
                    $query->where('ID', $parametroValor);
                    break;
    
                case 'name':
                    $query->selectRaw('post_title COLLATE utf8mb4_unicode_ci')
                    ->where('post_title', 'like', '%' . $parametroValor . '%');
                
                    break;
    
                case 'sku':
                    $query->where(function ($q) use ($parametroValor) {
                        $q->whereHas('metas', function ($metaQuery) use ($parametroValor) {
                            $metaQuery->where('meta_key', '_sku')
                                      ->where('meta_value', 'like', '%' . $parametroValor . '%');
                        })
                        ->orWhereHas('variations', function ($variationQuery) use ($parametroValor) {
                            $variationQuery->whereHas('metas', function ($metaQuery) use ($parametroValor) {
                                $metaQuery->where('meta_key', '_sku')
                                          ->where('meta_value', 'like', '%' . $parametroValor . '%');
                            });
                        });
                    });
                    break;
    
                default:
                    break;
            }
        }
        
        return $query->paginate($perPage)
            
            ->through(fn($produto) => self::formatProduct($produto));
    }
    
    
    
    public static function listProductById($id, $all=null)
    {
        return self::queryBase($all)
            ->where('ID', $id)
            ->get()
            ->map(fn($produto) => self::formatProduct($produto));
    }
    
    public static function listarProdutoPorSku($sku)
    {
        return self::queryBase()
            ->whereHas('metas', function ($q) use ($sku) {
                $q->where('meta_key', '_sku')->where('meta_value', $sku);
            })
            ->get()
            ->map(fn($produto) => self::formatProduct($produto));
    }
    
    public static function listProductByName($nome)
    {
        // Produtos do tipo 'product' sem variações
        $produtosSimples = self::queryBase()
            ->where('post_type', 'product')
            ->where('post_status', 'publish')
            ->where('post_title', 'like', '%' . $nome . '%') // Nome do produto
            ->paginate(50);
            
    
        // Variações cujo produto pai tem nome semelhante ao parâmetro
        $variacoes = self::queryBase()
            ->where('post_type', 'product_variation')
            ->where('post_status', 'publish')
            ->whereHas('parent', function ($query) use ($nome) {
                $query->where('post_title', 'like', '%' . $nome . '%'); // Nome do produto pai
            })->paginate(50);
            
            ;
    
        // Junta os resultados e formata tudo
        return $produtosSimples->merge($variacoes)
            ->map(fn($produto) => self::formatProduct($produto));
    }
    
    public static function listVariationsById($id)
{
    return self::where('post_type', 'product_variation')  // Filtra para variações de produto
        ->where('post_parent', $id)  // Filtra pela associação de variação ao produto principal
        ->get()
        ->map(fn($variacao) => self::formatProduct($variacao));  
}



public function metas()
{
    return $this->hasMany(ProdutoMeta::class, 'post_id', 'ID');
}
// Accessor para pegar um meta específico
public function getMetaValue($key)
{
    return optional($this->metas->firstWhere('meta_key', $key))->meta_value;
}
public function parent()
{
    return $this->belongsTo(Produtos::class, 'post_parent', 'ID');

}
public function variations()
{
    return $this->hasMany(self::class, 'post_parent', 'ID')
                ->where('post_type', 'product_variation');
                
}
    private static function queryBase($all=null)
    { 
        if(!$all){
            return self::whereIn('post_type', ['product', 'product_variation'])
            ->where('post_status', 'publish')
            ->whereDoesntHave('variations')
            ->with(['metas', 'parent'])
            ->orderBy('ID', 'desc');;

        }
        if($all){
            return self::whereIn('post_type', ['product'])
            ->where('post_status', 'publish')
            ->with(['metas', 'parent'])
            ->orderBy('ID', 'desc');
        }

            }
    
    private static function formatProduct($produto)
    {

        
    if (!$produto) {
        return (object)[]; // ou `null` se preferir retornar null
    }
    $variations = [];

    if ($produto->post_type === 'product') {
        $variations = self::where('post_parent', $produto->ID)
            ->where('post_type', 'product_variation')
            ->get();
    }
    
    // Montar o objeto
    $dados = (object)[
        'id'              => $produto->ID,
        'name'            => $produto->post_title,
        'stock_quantity'  => $produto->getMetaValue('_stock'),
        'sku'             => $produto->getMetaValue('_sku'),
        'permalink'       => $produto->post_parent
                                ? optional($produto->parent)->guid
                                : $produto->guid,
        'type'            => $produto->post_type,
        'price'           => $produto->getMetaValue('_regular_price'),
        'variations'      => false, // valor padrão
    ];
    
    // Se for variação, adiciona o parent_id
    if ($produto->post_type === 'product_variation') {
        $dados->parent_id = $produto->post_parent;
    }
    
    // Se tiver exatamente uma variação, define como true
    if ($produto->post_type === 'product' && !$variations->isEmpty()) {
        $dados->variations = true;
    }
    
        
        return (object)$dados;

    }


    


    
    
}
