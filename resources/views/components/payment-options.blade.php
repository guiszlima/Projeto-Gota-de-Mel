<div class="relative flex flex-col max-w-md w-full rounded-lg bg-white shadow-lg p-4 self-center text-gray-700">
    <nav class="flex flex-col gap-2">
        <div class="flex flex-row">
            <div role="button" class="flex items-center w-full p-2 transition-all rounded-lg text-start cursor-pointer hover:bg-gray-100">
                <label for="pix" class="flex items-center w-full cursor-pointer">
                    <div class="mr-3">
                        <input name="payment_method" id="pix" value="pix" type="radio" class="hidden peer" required />
                        <span class="flex items-center justify-center w-5 h-5 border border-gray-300 rounded-full peer-checked:bg-gray-900">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 16 16" fill="currentColor">
                                <circle cx="8" cy="8" r="8" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-blue-gray-600">Pix</p>
                </label>
            </div>
            <div role="button" class="flex items-center w-full p-2 transition-all rounded-lg text-start cursor-pointer hover:bg-gray-100">
                <label for="credito" class="flex items-center w-full cursor-pointer">
                    <div class="mr-3">
                        <input name="payment_method" id="credito" value="credit" type="radio" class="hidden peer" required />
                        <span class="flex items-center justify-center w-5 h-5 border border-gray-300 rounded-full peer-checked:bg-gray-900">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 16 16" fill="currentColor">
                                <circle cx="8" cy="8" r="8" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-blue-gray-600">Crédito</p>
                </label>
            </div>
            <div role="button" class="flex items-center w-full p-2 transition-all rounded-lg text-start cursor-pointer hover:bg-gray-100">
                <label for="debit" class="flex items-center w-full cursor-pointer">
                    <div class="mr-3">
                        <input name="payment_method" id="debit" value="debit" type="radio" class="hidden peer" required />
                        <span class="flex items-center justify-center w-5 h-5 border border-gray-300 rounded-full peer-checked:bg-gray-900">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 16 16" fill="currentColor">
                                <circle cx="8" cy="8" r="8" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-blue-gray-600">Débito</p>
                </label>
            </div>
            <div role="button" class="flex items-center w-full p-2 transition-all rounded-lg text-start cursor-pointer hover:bg-gray-100">
                <label for="dinheiro" class="flex items-center w-full cursor-pointer">
                    <div class="mr-3">
                        <input name="payment_method" id="dinheiro" value="dinheiro" type="radio" class="hidden peer" required />
                        <span class="flex items-center justify-center w-5 h-5 border border-gray-300 rounded-full peer-checked:bg-gray-900">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 16 16" fill="currentColor">
                                <circle cx="8" cy="8" r="8" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-blue-gray-600">Dinheiro</p>
                </label>
            </div>
        </div>
    </nav>
</div>
