<!DOCTYPE html>
<html class="" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css',  'resources/js/login.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="/build/plugins/jquery/dist/jquery.js"></script>
    <link href="/build/plugins/select2/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="/build/plugins/select2/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Choose an option',
                theme: "classic",
                width: '100%',
                dropdownCssClass: 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                containerCssClass: 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
            });
            $('#phone_code').val('101').trigger('change');

        });
    </script>
    <title>Forget Password</title>
</head>

<body class=" bg-light-theme-color-nav-base dark:bg-gray-800 flex items-center justify-center min-h-screen">

<div id="spinner"
     class="hidden fixed top-0 left-0 w-screen h-screen flex justify-center items-center bg-black bg-opacity-50 z-50">
    <div class="animate-spin rounded-full h-14 w-14 border-t-2 border-b-2 border-gray-900"></div>
    <p id="spinner-text"
       class="ml-4 font-bold text-black animate__animated animate__heartBeat animate__infinite infinite"></p>
</div>
<div
    class="bg-light-theme-color-base mx-5 sm:mx-0 w-full max-w-2xl p-8 border-gray-200 rounded-lg shadow sm:p-6 md:p-16 dark:bg-gray-800 dark:border-gray-700">
    <form class="space-y-6 " id="forget-password" method="post">
        @csrf
        <div class="space-y-2">
            <h2 class="sm:text-3xl text-2xl font-bold text-gray-900 dark:text-white">Forgot your password?</h2>
            <p class="font-normal text-base text-gray-900 dark:text-gray-400">
                No worries! Just select an option and type in your information and we will send you a code to reset
                your
                password!
            </p>
        </div>
        <div>
            <label for="reset-options" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an
                option</label>
            <select name="reset-options" id="reset-options" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected disabled value="">Choose an option</option>
                <option value="Mobile">Mobile</option>
            </select>
        </div>
        <div id="mobileDIV" hidden="hidden">
            <div class="mb-6">
                <label for="phone_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                    country phone
                    prefix</label>
                <select name="phone_code" id="phone_code"
                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option disabled selected value="">Select an option</option>
                    @foreach($countryPhoneCodes as $countryPhoneCode)
                        <option @if($countryPhoneCode->phonecode==98) selected
                                @endif value="{{$countryPhoneCode->id}}">{{$countryPhoneCode->name}}
                            (+{{$countryPhoneCode->phonecode}})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="mobile" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                    mobile</label>
                <input type="text" name="mobile" id="mobile"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                       placeholder="Enter without + or country prefix">
            </div>
        </div>
        <div class="mb-6 CaptchaDiv">
            <div class="flex justify-evenly md:justify-normal">
                <img id="captchaImg" src="{{ route('captcha') }}" alt="Captcha" class="w-32 h-10  mt-2 rounded"
                     title="Click on image for reload">
                <input name="captcha" required
                       class="bg-gray-50 border border-gray-300 h-10 mt-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       id="captcha" placeholder="Enter captcha" type="text">
            </div>
        </div>
        <div hidden="hidden" class="mt-6 VerificationCodeDiv">
            <label for="verification_code" class="block mb-2 text-sm font-medium text-gray-900 ">Enter verification
                code</label>
            <input name="verification_code"
                   class="bg-gray-50 border border-gray-300 h-10 mt-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   id="verification_code" placeholder="Enter the verification code sent" type="text">
            <div class="flex"><p class="mr-2" id="timer"></p>to resend code.</div>
        </div>
        {{--        <div class="flex items-start">--}}
        {{--            <div class="flex items-start ">--}}
        {{--                <div class="flex items-center h-5">--}}
        {{--                    <input id="remember" type="checkbox" value=""--}}
        {{--                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800"--}}
        {{--                           required>--}}
        {{--                </div>--}}
        {{--                <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I agree with--}}
        {{--                    the <a href="#" class="text-blue-600 hover:underline dark:text-blue-500">terms and--}}
        {{--                        conditions</a>.</label>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <button type="submit" id="get_code"
                class="md:w-auto w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-6 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Get Code
        </button>

    </form>
</div>

</body>


</html>
