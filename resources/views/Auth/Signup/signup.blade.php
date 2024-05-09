<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css',  'resources/js/signup.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Savior Signup</title>
</head>
<body class="bg-light-theme-color-nav-base flex items-center justify-center min-h-screen">

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
                <div class="mb-6">
                    <div>
                        <label for="mobile" class="block mb-2 text-sm font-medium text-gray-900 ">Your mobile</label>
                        <input type="text" id="mobile" name="mobile" value="{{ $tokenInfo->value }}" disabled required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>
            @endif
            <div class="lg:flex mb-6">
                <div class="w-full flex flex-col md:flex-row md:space-x-2">
                    <div class="w-full mb-2 md:mb-0">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Your password</label>
                        <input type="password" id="password" name="password" required minlength="8" maxlength="24"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               placeholder="Min: 8 characters | Max: 24 characters">
                    </div>
                    <div class="w-full md:ml-2">
                        <label for="repeat-password" class="block mb-2 text-sm font-medium text-gray-900">Repeat
                            password</label>
                        <input type="password" id="repeat-password" name="repeat-password" required minlength="8"
                               maxlength="24"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-2 md:mt-0"
                               placeholder="Min: 8 characters | Max: 24 characters">
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row mb-6">
                <div class="w-full mb-2 md:mb-0 md:mr-2">
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">First name
                        (English)</label>
                    <input type="text" id="first_name" name="first_name" required value="" placeholder="Like: John"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div class="w-full md:ml-2">
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">Last name
                        (English)</label>
                    <input type="text" id="last_name" name="last_name" required value="" placeholder="Like: Doe"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-2 md:mt-0">
                </div>
            </div>

            <div class="flex flex-col md:flex-row mb-6">
                <div class="w-full mb-2 md:mb-0 md:mr-2">
                    <label for="gender" class="block mb-2 text-sm font-medium text-gray-900">Gender</label>
                    <select name="gender" id="gender" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected disabled value="">Choose an option</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="w-full md:ml-2">
                    <label for="birthdate" class="block mb-2 text-sm font-medium text-gray-900">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" required value="" title="Select your birthdate"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
