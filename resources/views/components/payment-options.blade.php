<div class="relative flex h-max w-max max-w-[27rem] flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md self-center">
  <nav class="flex min-w-max flex-row gap-1 p-2 font-sans text-base font-normal text-blue-gray-700">
    <div role="button"
      class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
      <label htmlFor="pix" class="flex items-center w-full px-3 py-2 cursor-pointer">
        <div class="grid mr-3 place-items-center">
          <div class="inline-flex items-center">
            <label class="relative flex items-center p-0 rounded-full cursor-pointer"
              htmlFor="payment_method">
              <input name="payment_method" id="payment_method" value="pix" type="radio"
                class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border border-blue-gray-200 text-gray-900 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:before:bg-gray-900 hover:before:opacity-0" />
              <span
                class="absolute text-gray-900 transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="currentColor">
                  <circle data-name="ellipse" cx="8" cy="8" r="8"></circle>
                </svg>
              </span>
            </label>
          </div>
        </div>
        <p class="block font-sans text-base antialiased font-medium leading-relaxed text-blue-gray-400">
          Pix
        </p>
      </label>
    </div>
    <div role="button"
      class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
      <label htmlFor="credito" class="flex items-center w-full px-3 py-2 cursor-pointer">
        <div class="grid mr-3 place-items-center">
          <div class="inline-flex items-center">
            <label class="relative flex items-center p-0 rounded-full cursor-pointer" htmlFor="payment_method">
              <input name="payment_method" id="payment_method" value="credit" type="radio"
                class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border border-blue-gray-200 text-gray-900 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:before:bg-gray-900 hover:before:opacity-0" />
              <span
                class="absolute text-gray-900 transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="currentColor">
                  <circle data-name="ellipse" cx="8" cy="8" r="8"></circle>
                </svg>
              </span>
            </label>
          </div>
        </div>
        <p class="block font-sans text-base antialiased font-medium leading-relaxed text-blue-gray-400">
          Crédito
        </p>
      </label>
    </div>
    <div role="button"
      class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
      <label htmlFor="payment_method" class="flex items-center w-full px-3 py-2 cursor-pointer">
        <div class="grid mr-3 place-items-center">
          <div class="inline-flex items-center">
            <label class="relative flex items-center p-0 rounded-full cursor-pointer"
              htmlFor="horizontal-list-svelte">
              <input name="payment_method" id="payment_method" value="debit" type="radio"
                class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border border-blue-gray-200 text-gray-900 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:before:bg-gray-900 hover:before:opacity-0" />
              <span
                class="absolute text-gray-900 transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="currentColor">
                  <circle data-name="ellipse" cx="8" cy="8" r="8"></circle>
                </svg>
              </span>
            </label>
          </div>
        </div>
        <p class="block font-sans text-base antialiased font-medium leading-relaxed text-blue-gray-400">
          Débito
        </p>
      </label>
    </div>
    <div role="button"
      class="flex items-center w-full p-0 leading-tight transition-all rounded-lg outline-none text-start hover:bg-blue-gray-50 hover:bg-opacity-80 hover:text-blue-gray-900 focus:bg-blue-gray-50 focus:bg-opacity-80 focus:text-blue-gray-900 active:bg-blue-gray-50 active:bg-opacity-80 active:text-blue-gray-900">
      <label htmlFor="payment_method" class="flex items-center w-full px-3 py-2 cursor-pointer">
        <div class="grid mr-3 place-items-center">
          <div class="inline-flex items-center">
            <label class="relative flex items-center p-0 rounded-full cursor-pointer"
              htmlFor="horizontal-list-svelte">
              <input name="payment_method" id="payment_method" value="dinheiro" type="radio"
                class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-full border border-blue-gray-200 text-gray-900 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:before:bg-gray-900 hover:before:opacity-0" />
              <span
                class="absolute text-gray-900 transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="currentColor">
                  <circle data-name="ellipse" cx="8" cy="8" r="8"></circle>
                </svg>
              </span>
            </label>
          </div>
        </div>
        <p class="block font-sans text-base antialiased font-medium leading-relaxed text-blue-gray-400">
          Dinheiro
        </p>
      </label>
    </div>
  </nav>
</div>