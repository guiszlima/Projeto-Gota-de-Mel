<div class="relative flex flex-col max-w-md w-full rounded-2xl bg-white shadow-lg p-6 self-center text-gray-700">
    <nav class="flex flex-col gap-4">
        <!-- Opção de Pagamento Reutilizável -->
        <div class="flex items-center">
            <input id="pix" type="radio" value="pix" name="payment_method" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" required />
            <label for="pix" class="ms-2 text-lg font-medium text-gray-700">Pix</label>
        </div>

        <div class="flex items-center">
            <input id="credito" type="radio" value="credit" name="payment_method" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" required />
            <label for="credito" class="ms-2 text-lg font-medium text-gray-700">Crédito</label>
        </div>

        <div class="flex items-center">
            <input id="debit" type="radio" value="debit" name="payment_method" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" required />
            <label for="debit" class="ms-2 text-lg font-medium text-gray-700">Débito</label>
        </div>

        <div class="flex items-center">
            <input id="dinheiro" type="radio" value="dinheiro" name="payment_method" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" required />
            <label for="dinheiro" class="ms-2 text-lg font-medium text-gray-700">Dinheiro</label>
        </div>

        <div class="flex items-center">
            <input id="voucher" type="radio" value="voucher" name="payment_method" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" required />
            <label for="voucher" class="ms-2 text-lg font-medium text-gray-700">Voucher</label>
        </div>
    </nav>
</div>