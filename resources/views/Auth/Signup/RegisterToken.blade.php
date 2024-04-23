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
            Your registration code is : <span>{{$register_token}}</span>
        </p>
        <p class="font-normal text-gray-900 dark:text-gray-400 mb-4">
            Don't share this to anyone!
        </p>
    </div>
</div>
</body>
</html>
