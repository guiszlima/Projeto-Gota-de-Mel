<header class="bg-[#702b59] text-white shadow print:hidden">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <div class="text-lg font-semibold">
            <a href="{{route('menu')}}" class="hover:text-gray-300"><img class="w-32 h-32"
                    src="{{asset(path: 'images/logo-sem-fundo.png')}}" alt="Logo"></a>
        </div>
        <nav class="flex space-x-4">

            <a href="{{route('products.sell')}}" class="hover:text-gray-300">Vender</a>
            <a href="{{route('barcode.index')}}" class="hover:text-gray-300">Código de Barras</a>
            <a href="{{route('stock.index')}}" class="hover:text-gray-300">Produtos</a>

            <a href="{{route('report.sells')}}" class="hover:text-gray-300">R. Vendas</a>
            <a href="{{route('admin.index')}}" class="hover:text-gray-300">Admin</a>
        </nav>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit"
                class="border-2 border-red-600 rounded-lg px-6 py-1 text-red-400 cursor-pointer hover:bg-red-600 hover:text-red-200">Sair
            </button>
        </form>
    </div>
</header>