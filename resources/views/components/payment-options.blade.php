<div class="relative flex flex-col max-w-md w-full rounded-2xl bg-white shadow-lg p-6 self-center text-gray-700">
    <nav class="flex flex-col gap-3">
        <!-- Opção de Pagamento Reutilizável -->
        <label for="pix" class="group flex items-center gap-3 p-4 border border-gray-300 rounded-lg cursor-pointer transition-all duration-200 hover:border-gray-400 peer-checked:bg-sky-200">
            <input name="payment_method" id="pix" value="pix" type="radio" class="hidden peer" required />
            <span class="w-5 h-5 border-2 border-gray-400 rounded-full flex items-center justify-center peer-checked:bg-sky-200">
                <svg class="w-3 h-3 text-blue-500 opacity-0 peer-checked:opacity-100 transition-opacity duration-200" viewBox="0 0 16 16" fill="currentColor">
                    <circle cx="8" cy="8" r="8" />
                </svg>
            </span>
            <span class="text-gray-700 font-medium">Pix</span>
        </label>

        <label for="credito" class="group flex items-center gap-3 p-4 border border-gray-300 rounded-lg cursor-pointer transition-all duration-200 hover:border-gray-400 peer-checked:bg-sky-200">
            <input name="payment_method" id="credito" value="credit" type="radio" class="hidden peer" required />
            <span class="w-5 h-5 border-2 border-gray-400 rounded-full flex items-center justify-center peer-checked:bg-sky-200">
                <svg class="w-3 h-3 text-blue-500 opacity-0 peer-checked:opacity-100 transition-opacity duration-200" viewBox="0 0 16 16" fill="currentColor">
                    <circle cx="8" cy="8" r="8" />
                </svg>
            </span>
            <span class="text-gray-700 font-medium">Crédito</span>
        </label>

        <label for="debit" class="group flex items-center gap-3 p-4 border border-gray-300 rounded-lg cursor-pointer transition-all duration-200 hover:border-gray-400 peer-checked:bg-sky-200">
            <input name="payment_method" id="debit" value="debit" type="radio" class="hidden peer" required />
            <span class="w-5 h-5 border-2 border-gray-400 rounded-full flex items-center justify-center peer-checked:bg-sky-200">
                <svg class="w-3 h-3 text-blue-500 opacity-0 peer-checked:opacity-100 transition-opacity duration-200" viewBox="0 0 16 16" fill="currentColor">
                    <circle cx="8" cy="8" r="8" />
                </svg>
            </span>
            <span class="text-gray-700 font-medium">Débito</span>
        </label>

        <label for="dinheiro" class="group flex items-center gap-3 p-4 border border-gray-300 rounded-lg cursor-pointer transition-all duration-200 hover:border-gray-400 peer-checked:bg-sky-200">
            <input name="payment_method" id="dinheiro" value="dinheiro" type="radio" class="hidden peer" required />
            <span class="w-5 h-5 border-2 border-gray-400 rounded-full flex items-center justify-center peer-checked:bg-sky-200">
                <svg class="w-3 h-3 text-blue-500 opacity-0 peer-checked:opacity-100 transition-opacity duration-200" viewBox="0 0 16 16" fill="currentColor">
                    <circle cx="8" cy="8" r="8" />
                </svg>
            </span>
            <span class="text-gray-700 font-medium">Dinheiro</span>
        </label>

        <label for="voucher" class="group flex items-center gap-3 p-4 border border-gray-300 rounded-lg cursor-pointer transition-all duration-200 hover:border-gray-400 peer-checked:bg-sky-200">
            <input name="payment_method" id="voucher" value="voucher" type="radio" class="hidden peer" required />
            <span class="w-5 h-5 border-2 border-gray-400 rounded-full flex items-center justify-center peer-checked:bg-sky-200">
                <svg class="w-3 h-3 text-blue-500 opacity-0 peer-checked:opacity-100 transition-opacity duration-200" viewBox="0 0 16 16" fill="currentColor">
                    <circle cx="8" cy="8" r="8" />
                </svg>
            </span>
            <span class="text-gray-700 font-medium">Voucher</span>
        </label>
    </nav>
</div>
