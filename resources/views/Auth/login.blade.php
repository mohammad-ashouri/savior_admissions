<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/login.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Savior Login</title>
</head>

<body class="bg-gray-200 dark:bg-gray-900 flex items-center justify-center h-screen">
    <div class="bg-white dark:bg-gray-800 lg:w-4/6 w-full lg:m-0 m-8 rounded-lg shadow-lg flex">
        <div class="lg:w-2/5 pr-8 lg:inline-block hidden">
            <img src="{{ resource_path('images/login.jpg') }}" alt="Login Image"
                class="w-full rounded-l-lg">
        </div>
        <div class="lg:w-1/2 w-full flex flex-col justify-center items-center p-8">
            <h2 class="lg:text-3xl text-2xl font-bold mb-8 w-full text-left dark:text-white">Sign in to platform</h2>
            <form id="login-form" class="space-y-4 w-full">
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                        email</label>
                    <input type="email" id="email" name="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="name@gmail.com" >
                </div>

                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                        password</label>
                    <input type="password" id="password" name="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="••••••••" >
                </div>

                <div class="mb-6">
                    <p class="text-white text-center">capcha code</p>
                </div>

                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center h-5">
                        <input id="remember" type="checkbox" value=""
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                            >
                        <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember
                            me</label>
                    </div>


                    <div class="flex items-center">
                        <a href="#" class="text-blue-500">Lost Password?</a>

                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit"
                        class="lg:w-1/2 w-full bg-blue-700 text-white rounded-lg py-2 hover:bg-blue-800 transition duration-300">Login
                        Your Account</button>
                </div>
            </form>
        </div>
    </div>
</body>


</html>
