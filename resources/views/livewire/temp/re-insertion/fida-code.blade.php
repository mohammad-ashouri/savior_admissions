<div id="content"
     class="p-4 md:ml-14 !mt-10 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800 min-h-screen">
    <div class="max-w-2xl mx-auto p-6 rounded-lg dark:border-gray-700 mt-10 bg-white dark:bg-gray-700 shadow-md">
        <x-flash-messages/>

        <!-- Header Section -->
        <div class="mb-8 text-center">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2">Account Activation</h1>
            <p class="text-gray-600 dark:text-gray-300">Please enter your FIDA code to activate your account</p>
        </div>

        <!-- Activation Form -->
        <div class="mb-4">
            <form wire:submit.prevent="save">
                <div class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label for="fida-code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">FIDA
                            Code</label>
                        <input
                            type="text"
                            id="fida-code"
                            wire:model="fida_code"
                            class="w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Enter your code here">
                    </div>

                    <div class="w-full sm:w-auto">
                        <button
                            type="submit"
                            wire:target="save"
                            wire:loading.remove
                            class="px-6 py-3 w-full h-[42px] text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm transition-colors duration-200 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Activate
                        </button>
                        <div wire:loading wire:target="save" class="mt-2 text-center">
                            <div class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-500"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
