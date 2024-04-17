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
        <h2 class="lg:text-3xl text-2xl font-bold mb-8 w-full text-left dark:text-white">Create an Account
        </h2>
        <form id="signup" method="post" action="{{ route('CreateAccount.authorization') }}" class="space-y-4 w-full">
            @csrf
            <div class="space-y-2">
                <h2 class="sm:text-2xl text-2xl font-bold text-gray-900 dark:text-white">Don't have account?</h2>
                <p class="font-normal text-base text-gray-900 dark:text-gray-400">
                    Do not worry! You can create a new account! Choose a method, then request to create a new account.
                </p>
            </div>
            <div>
                <label for="signup-method" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                    signup method</label>
                <select name="signup-method" id="signup-method"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected disabled value="">Choose an option</option>
                    <option value="Mobile">Mobile</option>
                    <option value="Email">Email</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Enter your email</label>
                <input type="email" id="email" name="email"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="name@gmail.com">
            </div>
            <div class="mb-6">
                <label for="phone-code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                    country phone
                    prefix</label>
                <select name="phone_code" id="phone-code" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @foreach($countryPhoneCodes as $countryPhoneCode)
                        <option @if($countryPhoneCode->phonecode==98) selected
                                @endif value="{{$countryPhoneCode->id}}">{{$countryPhoneCode->name}}
                            (+{{$countryPhoneCode->phonecode}})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-6">
                <label for="mobile" class="block mb-2 text-sm font-medium text-gray-900 ">Enter your mobile</label>
                <input type="text" id="mobile" name="mobile"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="Enter without + or country prefix">
            </div>

            <div class="mb-6">
                <div class="flex justify-evenly md:justify-normal">
                    <img id="captchaImg" src="{{ route('captcha') }}" alt="Captcha" class="w-32 h-10  mt-2 rounded"
                         title="Click on image for reload">
                    <input name="captcha"
                           class="bg-gray-50 border border-gray-300 h-10 mt-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           id="captcha" placeholder="Enter captcha" type="text">
                </div>
            </div>

            {{--            <div class="flex justify-between items-start mb-6">--}}
            {{--                <div class="flex items-center h-5">--}}
            {{--                    <input id="remember" type="checkbox" value="" required--}}
            {{--                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"--}}
            {{--                    >--}}
            {{--                    <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-white">I accept the <a--}}
            {{--                            href="#" class="text-blue-500">Terms and Conditions</a></label>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="lg:w-1/2 w-full bg-blue-700 text-white rounded-lg py-2 hover:bg-blue-800 transition duration-300">
                    Register
                </button>
            </div>
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center h-5">
                    <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-white">Already have an
                        account? <a
                            href="{{route('login')}}" class="text-blue-500">Login here</a></label>
                </div>
            </div>
        </form>
    </div>
</div>
</body>


</html>
