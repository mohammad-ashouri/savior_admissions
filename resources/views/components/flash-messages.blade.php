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
                </div>
            @endif
        </div>
    </div>
@endif
