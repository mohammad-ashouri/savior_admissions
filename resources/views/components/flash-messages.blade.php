@props(['value'])
@if(session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info') || $errors->any())
    <div class="pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @session('success')
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-800 dark:border-green-600 dark:text-green-200 flex items-center justify-between"
                role="alert">
                <button type="button" class="mr-2" onclick="this.parentElement.remove()">
                    <svg class="fill-current h-6 w-6 text-green-500 dark:text-green-200" role="button"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/>
                    </svg>
                </button>
                <span class="flex-grow">{{ $value }}</span>
            </div>
            @endsession

            @session('error')
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-800 dark:border-red-600 dark:text-red-200 flex items-center justify-between"
                role="alert">
                <button type="button" class="mr-2" onclick="this.parentElement.remove()">
                    <svg class="fill-current h-6 w-6 text-red-500 dark:text-red-200" role="button"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/>
                    </svg>
                </button>
                <span class="flex-grow">{{ $value }}</span>
            </div>
            @endsession

            @session('warning')
            <div
                class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative dark:bg-yellow-800 dark:border-yellow-600 dark:text-yellow-200 flex items-center justify-between"
                role="alert">
                <button type="button" class="mr-2" onclick="this.parentElement.remove()">
                    <svg class="fill-current h-6 w-6 text-yellow-500 dark:text-yellow-200" role="button"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/>
                    </svg>
                </button>
                <span class="flex-grow">{{ $value }}</span>
            </div>
            @endsession

            @session('info')
            <div
                class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative dark:bg-blue-800 dark:border-blue-600 dark:text-blue-200 flex items-center justify-between"
                role="alert">
                <button type="button" class="mr-2" onclick="this.parentElement.remove()">
                    <svg class="fill-current h-6 w-6 text-blue-500 dark:text-blue-200" role="button"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/>
                    </svg>
                </button>
                <span class="flex-grow">{{ $value }}</span>
            </div>
            @endsession

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-r px-4 py-3 shadow-md dark:bg-red-900/20 dark:border-red-600 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="ml-3">
                                <div class="!text-red-500 px-4 py-3 rounded relative dark:text-red-200 flex items-center justify-between">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="this.parentElement.parentElement.remove()"
                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
