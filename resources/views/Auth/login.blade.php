<!DOCTYPE html>
<html class="" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/login.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Savior Schools Login</title>
</head>
<body class="bg-light-theme-color-nav-base dark:bg-gray-800 flex items-center justify-center min-h-screen">
<div id="spinner"
     class="hidden fixed top-0 left-0 w-screen h-screen flex justify-center items-center bg-black bg-opacity-50 z-50">
    <div class="animate-spin rounded-full h-14 w-14 border-t-2 border-b-2 border-gray-900"></div>
    <p id="spinner-text"
       class="ml-4 font-bold text-black animate__animated animate__heartBeat animate__infinite infinite"></p>
</div>
<div class="bg-light-theme-color-base lg:w-5/6 w-full lg:m-0 m-8 rounded-lg shadow-lg flex">
    <div class="lg:w-2/5 pr-8 lg:inline-block loginPic">
    </div>

    <div class="lg:w-2/3 w-full flex flex-col justify-center items-center p-8">

        <div>
            <h2 class="lg:text-3xl text-2xl font-bold mb-8 w-full text-left ">Sign in to Savior International
                School's Admission Portal</h2>
        </div>
        <div>
            @if ($errors->has('WrongToken'))
                @vite(['resources/js/Swals/WrongToken.js'])
            @endif
            @if( $errors->has('SMSSent') )
                @vite(['resources/js/Swals/SMSSent.js'])
            @endif
            @if( $errors->has('EmailSent') )
                @vite(['resources/js/Swals/EmailSent.js'])
            @endif
            @if( $errors->has('SMSSendingFailed') )
                @vite(['resources/js/Swals/SMSSendingFailed.js'])
            @endif
            @if( $errors->has('EmailSendingFailed') )
                @vite(['resources/js/Swals/EmailSendingFailed.js'])
            @endif
            @if( $errors->has('MobileError') )
                @vite(['resources/js/Swals/MobileError.js'])
            @endif
            @if( $errors->has('EmailError') )
                @vite(['resources/js/Swals/EmailError.js'])
            @endif
            @if( $errors->has('captchaError') )
                @vite(['resources/js/Swals/CaptchaError.js'])
            @endif
            @if( $errors->has('ServerError') )
                @vite(['resources/js/Swals/ServerError.js'])
            @endif
        </div>
        <form id="login-form" method="post" action="/login" class="space-y-4 w-full">
            @csrf
{{--            <div class="mb-6">--}}
{{--                <div>--}}
{{--                    <label for="login-method"--}}
{{--                           class="block mb-2 text-sm font-medium text-gray-900">Login method</label>--}}
{{--                    <select name="login-method" id="login-method"--}}
{{--                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">--}}
{{--                        <option selected disabled value="">Choose an option</option>--}}
{{--                        <option value="mobile">Mobile</option>--}}
{{--                        <option value="email">Email</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="mb-6">--}}
{{--                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Your email</label>--}}
{{--                <input type="email" id="email" name="email"--}}
{{--                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"--}}
{{--                       placeholder="name@gmail.com">--}}
{{--            </div>--}}
            <div class="mb-6" id="">
                <label for="mobile" class="block mb-2 text-sm font-medium text-gray-900 ">Your mobile</label>
                <div class="flex">
                    <select name="phone_code" id="phone_code" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-bl-md rounded-tl-md focus:ring-blue-500 focus:border-blue-500 block w-1/10 p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        @foreach($countryPhoneCodes as $phoneCodes)
                            <option value="{{$phoneCodes->id}}" @if($phoneCodes->id==101) selected @endif>
                                +{{$phoneCodes->phonecode}}</option>
                        @endforeach
                    </select>
                    <input type="text" id="mobile" name="mobile" required value="{{ old('mobile') }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-br-md rounded-tr-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="Enter without 0 or phone code at the beginning of it ">
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Your
                    password</label>
                <input type="password" id="password" name="password" autocomplete="new-password" required value="{{ old('password') }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="••••••••">
            </div>

            <div class="mb-6">
                <div class="flex justify-evenly md:justify-normal">
                    <img id="captchaImg" src="{{ route('captcha') }}" alt="Captcha" class="w-32 h-10  mt-2 rounded"
                         title="Click on image for reload">
                    <input name="captcha" required
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
                    <a href="{{ route('CreateAccount') }}" class="text-blue-500">Create account (parents)</a>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="lg:w-1/2 w-full bg-blue-700 text-white rounded-lg py-2 hover:bg-blue-800 transition duration-300">
                    Login
                    Your Account
                </button>
            </div>
            <div>
                <p class="text-red-600">
                    Please note the following conditions for admissions at Savior International Schools:<br>
                    1. The student's latest average score must be at least 75%<br>
                    2. The student must be able to speak in English fluently
                </p>
            </div>
        </form>
    </div>
</div>
</body>
<div class="fixed bottom-8 right-8 z-50 flex items-center justify-center" id="show-contact-us">
    <div class="absolute top-0 transform -translate-y-full -translate-x-1/2 text-center left-1/2">
        <span class="inline-block p-2 rounded-lg bg-gray-800 text-white whitespace-nowrap">Contact us!</span>
    </div>
    <div
        class="w-20 h-20 mt-1 rounded-full bg-yellow-500 hover:bg-blue-600 transition duration-300 ease-in-out cursor-pointer animate-pulse flex items-center justify-center">
        <i class="las la-phone-volume" style="font-size: 35px"></i>
    </div>
</div>

<div id="contact-us-modal" hidden="">
    <div id="contact-us-modal-overlay" class="fixed top-0 right-0 left-0 bottom-0 bg-black opacity-50 z-40 "></div>
    <div id="default-modal" tabindex="-1" aria-hidden="true"
         class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 bottom-0 z-50 flex justify-center items-center w-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Facing problems? Contact us!
                    </h3>
                    <button type="button" id="close-modal-button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <i class="las la-times" style="font-size: 24px"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <p class="font-bold leading-relaxed text-gray-800 dark:text-gray-400">
                        Taha Branch-Admissions Department
                    </p>
                    <p class="text-base leading-relaxed text-gray-800 dark:text-gray-400">
                        <i class="las la-mobile" style="font-size: 24px"></i>Mobile number: <a
                            href="tel:+989058384005">+989058384005
                            <span class="text-blue-500">(Click to call)</span></a>
                    </p>
                    <p class="text-base leading-relaxed text-gray-800 dark:text-gray-400">
                        <i class="las la-phone" style="font-size: 24px"></i>Phone number: <a
                            href="tel:+982537727780">+982537727780
                            <span class="text-blue-500">(Click to call)</span></a>
                    </p>
                    <p class="text-base leading-relaxed text-gray-800 dark:text-gray-400">
                        <i class="las la-share-alt" style="font-size: 24px"></i>eitaa: <a
                            class="text-blue-500 underline"
                            href="https://eitaa.com/admissionsaviorboys">https://eitaa.com/admissionsaviorboys</a>
                    </p>
                    <p class="font-bold leading-relaxed text-gray-800 dark:text-gray-400">
                        Kawthar Branch-Admissions Department
                    </p>
                    <p class="text-base leading-relaxed text-gray-800 dark:text-gray-400">
                        <i class="las la-mobile" style="font-size: 24px"></i>Mobile number: <a
                            href="tel:+989028384002">+989028384002
                            <span class="text-blue-500">(Click to call)</span></a>
                    </p>
                    <p class="text-base leading-relaxed text-gray-800 dark:text-gray-400">
                        <i class="las la-share-alt" style="font-size: 24px"></i>eitaa: <a
                            class="text-blue-500 underline"
                            href="https://eitaa.com/Admissions">https://eitaa.com/Admissions</a>
                    </p>
                    <p class="font-bold leading-relaxed text-gray-800 dark:text-gray-400">
                        Tuba Branch-Admissions Department
                    </p>
                    <p class="text-base leading-relaxed text-gray-800 dark:text-gray-400">
                        <i class="las la-mobile" style="font-size: 24px"></i>Mobile number: <a
                            href="tel:+989333234105">+989333234105
                            <span class="text-blue-500">(Click to call)</span></a>
                    </p>
                    <p class="text-base leading-relaxed text-gray-800 dark:text-gray-400">
                        <i class="las la-share-alt" style="font-size: 24px"></i>eitaa: <a
                            class="text-blue-500 underline"
                            href="https://eitaa.com/KgSupervisor">https://eitaa.com/KgSupervisor</a>
                    </p>


                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="button" id="close-modal"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Fine!
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</html>
