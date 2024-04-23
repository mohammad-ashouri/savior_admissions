<!DOCTYPE html>
<html class="" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/signup.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account</title>
</head>

<body class=" bg-light-theme-color-nav-base dark:bg-gray-800 flex items-center justify-center h-screen">

<div class="bg-light-theme-color-base dark:bg-gray-800 lg:w-4/6 w-full lg:m-0 m-8 rounded-lg shadow-lg flex">
    <div class="lg:w-2/5 pr-8 lg:inline-block signupPic">
    </div>
    <div class="lg:w-1/2 w-full flex flex-col justify-center items-center p-8">
        @if(session()->has('errors') && session('errors')->has('MobileInvalid'))
            @vite(['resources/js/Swals/MobileInvalid.js'])
        @endif
        @if(session()->has('errors') && session('errors')->has('EmailInvalid'))
            @vite(['resources/js/Swals/EmailInvalid.js'])
        @endif
        @if(session()->has('errors') && session('errors')->has('MobileExists'))
            @vite(['resources/js/Swals/MobileExists.js'])
        @endif
        <h2 class="lg:text-3xl text-2xl font-bold mb-8 w-full text-left dark:text-white">Enter the code sent
        </h2>
        <form id="signup" method="post" action="{{ route('CreateAccount.authorize') }}" class="space-y-4 w-full">
            @csrf
            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Enter your email</label>
                <input type="email" id="email" name="email"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="name@gmail.com">
            </div>
            <div class="mb-6">
                <div class="flex justify-evenly md:justify-normal">
                    <input name="captcha"
                           class="bg-gray-50 border border-gray-300 h-10 mt-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           id="captcha" placeholder="Enter captcha" type="text">
                </div>
            </div>
            <div class="flex justify-between items-center">
                <button type="submit"
                        class="lg:w-1/2 w-full bg-blue-700 text-white rounded-lg py-2 hover:bg-blue-800 transition duration-300">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>
</body>


</html>
