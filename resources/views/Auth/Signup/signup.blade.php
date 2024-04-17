<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css',  'resources/js/signup.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Savior Signup</title>
</head>
<body class="bg-light-theme-color-nav-base flex items-center justify-center h-screen">

<div class="bg-light-theme-color-base lg:w-4/6 w-full lg:m-0 m-8 rounded-lg shadow-lg flex">
    <div class="lg:w-2/5 pr-8 lg:inline-block loginPic">
    </div>
    <div class="lg:w-1/2 w-full flex flex-col justify-center items-center p-8">
        <h2 class="lg:text-3xl text-2xl font-bold mb-8 w-full text-left ">Register to savior school</h2>
        <form id="signup-form" method="post" action="{{route('CreateAccount.createAccount')}}" class="space-y-4 w-full">
            @csrf
            @if($tokenInfo->register_method=='Email')
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Your
                        email</label>
                    <input type="email" id="email" name="email" value="{{ $tokenInfo->value }}" disabled required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
            @elseif($tokenInfo->register_method=='Mobile')
                <label for="mobile" class="block mb-2 text-sm font-medium text-gray-900 ">Your mobile</label>
                <input type="text" id="mobile" name="mobile" value="{{ $tokenInfo->value }}" disabled required
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
            @endif
            <div class="flex mb-6">
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Your
                        password</label>
                    <input type="password" id="password" name="password" required minlength="8" maxlength="24"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="••••••••">
                </div>
                <div class="ml-2">
                    <label for="repeat-password" class="block mb-2 text-sm font-medium text-gray-900">Repeat
                        password</label>
                    <input type="password" id="repeat-password" name="repeat-password" required minlength="8"
                           maxlength="24"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="••••••••">
                </div>
            </div>
            <div class="flex mb-6">
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">First name
                        (English)</label>
                    <input type="text" id="first_name" name="first_name" required value=""
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="Like: John">
                </div>
                <div class="ml-2">
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">Last name
                        (English)</label>
                    <input type="text" id="last_name" name="last_name" required value=""
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="Like: Doe">
                </div>
            </div>
            <div class="flex mb-6">
                <div>
                    <label for="gender" class="block mb-2 text-sm font-medium text-gray-900">Gender</label>
                    <select name="gender" id="gender" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected disabled value="">Choose an option</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-evenly md:justify-normal">
                    <img id="captchaImg" src="{{ route('captcha') }}" alt="Captcha" class="w-32 h-10  mt-2 rounded"
                         title="Click on image for reload">
                    {{--                        <button type="button" onclick="reloadCaptcha()" title="Reload"--}}
                    {{--                                class="h-10 p-1 bg-gray-300 hover:bg-gray-400 rounded mt-2">--}}
                    {{--                            <i class="fas fa-sync-alt"></i>--}}
                    {{--                        </button>--}}
                    <input name="captcha" required
                           class="bg-gray-50 border border-gray-300 h-10 mt-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           id="captcha" placeholder="Enter captcha" type="text">
                </div>
            </div>
            <div class="flex justify-between items-center">
                <input type="hidden" value="{{ $tokenInfo->token }}" name="token">
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
