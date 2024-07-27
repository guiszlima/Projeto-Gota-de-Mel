<header class="bg-gray-800 text-white shadow print:hidden">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <div class="text-lg font-semibold">
            <a href="#" class="hover:text-gray-300">My Logo</a>
        </div>
        <nav class="flex space-x-4">
            <a href="#" class="hover:text-gray-300">Home</a>
            <a href="#" class="hover:text-gray-300">About</a>
            <a href="#" class="hover:text-gray-300">Services</a>
            <a href="#" class="hover:text-gray-300">Contact</a>
        </nav>
        <form action="{{ route('logout') }}" method="post">
            <button type="submit"
                class="border-2 border-red-600 rounded-lg px-6 py-1 text-red-400 cursor-pointer hover:bg-red-600 hover:text-red-200">Sair</button>
        </form>
    </div>
</header>