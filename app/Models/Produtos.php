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
        $query = self::queryBase($all);
    
        // Verificando os parâmetros de busca
        if ($parametroNome && $parametroValor) {
            switch (strtolower($parametroNome)) {
                case 'id':
                    $query->where('ID', $parametroValor);
                    break;
    
                case 'name':
                    $query->where('post_title', 'like', '%' . $parametroValor . '%');
                    break;
    
                case 'sku':
                    // Buscando o SKU do produto principal
                    $query->whereHas('metas', function ($metaQuery) use ($parametroValor) {
                        $metaQuery->where('meta_key', '_sku')
                                  ->where('meta_value', 'like', '%' . $parametroValor . '%');
                    });
    
                    // Buscando o SKU nas variações, se houver
                    $query->orWhereHas('variations', function ($variationQuery) use ($parametroValor) {
                        $variationQuery->whereHas('metas', function ($metaQuery) use ($parametroValor) {
                            $metaQuery->where('meta_key', '_sku')
                                      ->where('meta_value', 'like', '%' . $parametroValor . '%');
                        });
                    });
                    break;
    
                default:
                    // Campo inválido, pode opcionalmente lançar exceção ou ignorar
                    break;
            }
        }
    
        return $query->paginate($perPage)
            ->withQueryString()
            ->through(fn($produto) => self::formatProduct($produto));
    }
    
    
    public static function listProductById($id)
    {
        return self::queryBase()
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
            ->get();
    
        // Variações cujo produto pai tem nome semelhante ao parâmetro
        $variacoes = self::queryBase()
            ->where('post_type', 'product_variation')
            ->where('post_status', 'publish')
            ->whereHas('parent', function ($query) use ($nome) {
                $query->where('post_title', 'like', '%' . $nome . '%'); // Nome do produto pai
            })
            ->get();
    
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
        $dados =  (object)[
            'id'              => $produto->ID,
            'name'            => $produto->post_title,
            'stock_quantity'  => $produto->getMetaValue('_stock'),
            'sku'             => $produto->getMetaValue('_sku'),
            'permalink'       => $produto->post_parent
                                    ? optional($produto->parent)->guid
                                    : $produto->guid,
            'type'            => $produto->post_type,
            'price'           => $produto->getMetaValue('_regular_price'),
            'variations'      => [],  // Inicia o array de variações vazio
        ];

        if ($produto->post_type === 'product_variation') {
            $dados->parent_id = $produto->post_parent;
        }
        if ($produto->post_type === 'product') {
            $variacoes = Produtos::listVariationsById($produto->ID);
    
            if ($variacoes->isNotEmpty()) {
                $dados->variations = $variacoes->pluck('id')->toArray();  // Retorna um array simples de IDs
            }
        }
        return (object)$dados;

    }

    
    


    function getProductVariations($parent_id, $variation_id) {
        global $wpdb; // Usando o WordPress $wpdb para interação com o banco de dados
    
        // Consulta SQL para obter os dados da variação
        $query = "
            SELECT 
                vp.ID AS variation_id,
                vp.post_title AS variation_name,
                vp.post_content AS variation_description,
                vp.guid AS permalink,
                vp.post_type AS type,
                vp.post_status AS status,
                vp.post_parent AS parent_id,
                MAX(CASE WHEN pm.meta_key = '_sku' THEN pm.meta_value END) AS sku,
                MAX(CASE WHEN pm.meta_key = '_stock' THEN pm.meta_value END) AS stock_quantity,
                MAX(CASE WHEN pm.meta_key = '_regular_price' THEN pm.meta_value END) AS regular_price,
                MAX(CASE WHEN pm.meta_key = '_sale_price' THEN pm.meta_value END) AS sale_price,
                MAX(CASE WHEN pm.meta_key = '_weight' THEN pm.meta_value END) AS weight,
                MAX(CASE WHEN pm.meta_key = '_length' THEN pm.meta_value END) AS length,
                MAX(CASE WHEN pm.meta_key = '_width' THEN pm.meta_value END) AS width,
                MAX(CASE WHEN pm.meta_key = '_height' THEN pm.meta_value END) AS height,
                MAX(CASE WHEN pm.meta_key = '_product_attributes' THEN pm.meta_value END) AS attributes,
                MAX(CASE WHEN pm.meta_key = '_manage_stock' THEN pm.meta_value END) AS manage_stock,
                MAX(CASE WHEN pm.meta_key = '_stock_status' THEN pm.meta_value END) AS stock_status
            FROM {$wpdb->posts} vp
            LEFT JOIN {$wpdb->prefix}postmeta pm ON vp.ID = pm.post_id
            WHERE vp.ID = %d
              AND vp.post_type = 'product_variation'
              AND vp.post_status = 'publish'
              AND vp.post_parent = %d
            GROUP BY vp.ID, vp.post_title, vp.guid, vp.post_type, vp.post_status, vp.post_content, vp.post_parent;
        ";
    
        // Executando a consulta com parâmetros
        $results = $wpdb->get_row($wpdb->prepare($query, $variation_id, $parent_id));
    
        // Retornando os dados se encontrados
        if ($results) {
            return (array) $results;
        } else {
            return null; // Nenhuma variação encontrada
        }
    }
    
}
