<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vender</title>
</head>
<body>
    
    <form action="{{ route("products.update")  }}" method="POST">
        @csrf
        @method('PUT')
        @if (session('mensagem'))
        <div class="alert alert-success">
            {{ session('mensagem') }}
        </div>
    @endif
        <input required autocomplete="off" type="text" name="product" id="product">
        <button id="atualizar" type="button">Atualizar</button>
        <button type="submit">Vender product</button>
    </form>
  <script>

const consumerKey = "{{ env('WOOCOMMERCE_CONSUMER_KEY') }}";
const consumerSecret = "{{ env('WOOCOMMERCE_CONSUMER_SECRET') }}";



     const atualizar = document.getElementById('atualizar');
var pedidosArray
// Adiciona um listener para o evento de clique no botão
// Captura o botão de atualização pelo ID


// Adiciona um evento de clique ao botão de atualização
atualizar.addEventListener('click', async function() {
    try {
        // Captura o valor do input pelo ID
        let valores = document.getElementById('product').value;

        // Divide os valores por vírgula
        if (valores.endsWith(',')) {
            // Remove o último caractere da string
            valores = valores.slice(0, -1);
        }

        // Separa os IDs dos produtos em um array
        

        // Constrói a URL da API do WooCommerce com os IDs dos produtos
        const baseUrl = 'http://localhost:10013/wc/v3/products';
        const idsString = valores
        const url = `${baseUrl}?include=${idsString}`;

      

        const headers = new Headers();
        headers.set('Authorization', 'Basic ' + btoa(`${consumerKey}:${consumerSecret}`));

        // Faz a requisição GET usando fetch
        const response = await fetch(url, {
  mode: 'cors',
  headers: {
    'Access-Control-Allow-Origin':'*'
  }
})


        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Obtém os produtos em formato JSON
        const products = await response.json();

        // Exibe os produtos ou faz alguma manipulação com eles
        displayProducts(products);
    } catch (error) {
        console.error('Error fetching products:', error);
    }
});

// Função para exibir os produtos (substitua pelo seu próprio código)
function displayProducts(products) {
    consule.log("funcionou")
    console.log('Produtos:', products);
    // Aqui você pode implementar a lógica para exibir os produtos na sua aplicação
}



  </script>
</body>
</html>
