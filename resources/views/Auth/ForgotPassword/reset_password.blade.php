@php use App\Models\Auth\PasswordResetToken;use App\Models\User; @endphp
    <!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/login.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset password</title>
</head>
@php
    $tokenInfo=PasswordResetToken::where('token',$token)->first();
    $userInfo=User::find($tokenInfo->user_id);
@endphp
<body class=" bg-light-theme-color-base dark:bg-gray-800 flex items-center justify-center min-h-screen">
<div id="spinner" class="hidden fixed top-0 left-0 w-screen h-screen flex justify-center items-center bg-black bg-opacity-50 z-50">
    <div class="animate-spin rounded-full h-14 w-14 border-t-2 border-b-2 border-gray-900"></div>
    <p id="spinner-text" class="ml-4 font-bold text-black animate__animated animate__heartBeat animate__infinite infinite"></p>
</div>
<div class="bg-white dark:bg-gray-800 lg:w-4/6 w-full lg:m-0 m-8 rounded-lg shadow-lg flex">
    <div class="lg:w-2/5 pr-8 lg:inline-block hidden">
        <img src="https://flowbite.com/application-ui/demo/images/authentication/reset-password.jpg" alt="Login Image"
             class="w-full rounded-l-lg">
    </div>
    <div class="lg:w-1/2 w-full flex flex-col justify-center items-center p-8">
        <h2 class="lg:text-3xl text-2xl font-bold mb-8 w-full text-left dark:text-white">Reset your password
        </h2>
        <form id="reset-password" class="space-y-4 w-full">
            <div class="mb-6">
                @if($tokenInfo->type==1)
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                        email</label>
                    <input type="email" id="email" name="email" value="{{ $userInfo->email }}" disabled
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @elseif($tokenInfo->type==2)
                    <label for="mobile" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                        mobile</label>
                    <input type="text" id="mobile" name="mobile" value="{{ $userInfo->mobile }}" disabled
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @endif

            </div>

            <div class="mb-6">
                <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New
                    password</label>
                <input type="password" id="new_password" name="password" autocomplete="new-password"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="••••••••">
            </div>
            <div class="mb-6">
                <label for="Confirm_new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                    New Password
                </label>
                <input type="password" id="Confirm_new_password" name="password_confirmation" autocomplete="new-password"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="••••••••">
            </div>

            <div class="mb-6">
                <p class="text-white text-center">capcha code</p>
            </div>

            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="remember" type="checkbox" value="" required
                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                    >
                    <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-white">I accept the <a
                            href="#" class="text-blue-500">Terms and Conditions</a></label>
                </div>

            </div>

            <div class="flex justify-between items-center">
                <input type="hidden" name="token" id="token" value="{{ $token }}">
                <button type="submit"
                        class="lg:w-1/2 w-full bg-blue-700 text-white rounded-lg py-2 hover:bg-blue-800 transition duration-300">
                    Reset password
                </button>
            </div>
        </form>
    </div>
</div>
</body>


</html>
