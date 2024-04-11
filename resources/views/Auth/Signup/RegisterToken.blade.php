<!DOCTYPE html>
<html class="" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css',  'resources/js/signup.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Make new account</title>
</head>
<body class=" bg-light-theme-color-base dark:bg-gray-800 flex items-center justify-center h-screen">
<div
    class="mx-5 sm:mx-0 w-full max-w-2xl p-8 text-center border-gray-200 rounded-lg shadow sm:p-6 md:p-16 dark:bg-gray-800 dark:border-gray-700">
    <div class="space-y-2">
        <p class="font-normal text-gray-900 dark:text-gray-400 mb-4">
            Click the button below to complete the registration process.
        </p>
        <a href="{{$register_link}}">
            <button
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Complete Registration
            </button>
        </a>
    </div>
</div>
</body>
</html>
