<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css',  'resources/js/login.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Savior Login</title>
</head>
<body class="bg-light-theme-color-nav-base flex items-center justify-center h-screen">

<div class="bg-light-theme-color-base lg:w-4/6 w-full lg:m-0 m-8 rounded-lg shadow-lg flex">
    <div class="lg:w-2/5 pr-8 lg:inline-block loginPic">
    </div>

    <div class="lg:w-1/2 w-full flex flex-col justify-center items-center p-8">

        <div>
            <h2 class="lg:text-3xl text-2xl font-bold mb-8 w-full text-left ">Sign in to savior school</h2>
        </div>
        <div>
            @if( session()->has('success') )
                <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md"
                     role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">0
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">{{ session()->get('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <form id="login-form" class="space-y-4 w-full">
            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Your
                    email</label>
                <input type="email" id="email" name="email"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="name@gmail.com">
            </div>

            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Your
                    password</label>
                <input type="password" id="password" name="password"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="••••••••">
            </div>

            <div class="mb-6">
                <div class="flex justify-evenly md:justify-normal">
                    <img id="captchaImg" src="{{ route('captcha') }}" alt="Captcha" class="w-32 h-10  mt-2 rounded"
                         title="Click on image for reload">
                    {{--                        <button type="button" onclick="reloadCaptcha()" title="Reload"--}}
                    {{--                                class="h-10 p-1 bg-gray-300 hover:bg-gray-400 rounded mt-2">--}}
                    {{--                            <i class="fas fa-sync-alt"></i>--}}
                    {{--                        </button>--}}
                    <input name="captcha"
                           class="bg-gray-50 border border-gray-300 h-10 mt-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           id="captcha" placeholder="Enter captcha" type="text">
                </div>
            </div>

            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="remember" type="checkbox" name="remember"
                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300  dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                    >
                    <label for="remember" class="ml-2 text-sm font-medium">Remember
                        me</label>
                </div>
            </div>
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center">
                    <a href="{{ route('ForgetPassword') }}" class="text-blue-500">Lost password?</a>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('CreateAccount') }}" class="text-blue-500">Create account</a>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="lg:w-1/2 w-full bg-blue-700 text-white rounded-lg py-2 hover:bg-blue-800 transition duration-300">
                    Login
                    Your Account
                </button>
            </div>
        </form>
    </div>
</div>
</body>

</html>
