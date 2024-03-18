<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css',  'resources/js/login.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Forget password</title>
</head>

<body class=" bg-light-theme-color-base dark:bg-gray-800 flex items-center justify-center h-screen">

    <div
        class="mx-5 sm:mx-0 w-full max-w-2xl p-8 border-gray-200 rounded-lg shadow sm:p-6 md:p-16 dark:bg-gray-800 dark:border-gray-700">
        <form class="space-y-6 " id="forget-password">
            <div class="space-y-2">
                <h2 class="sm:text-3xl text-2xl font-bold text-gray-900 dark:text-white">Forgot your password?</h2>
                <p class="font-normal text-base text-gray-900 dark:text-gray-400">
                    Don't fret! Just select an option and type in your information and we will send you a code to reset your password!
                </p>
            </div>
            <div>
                <label for="reset-options" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an option</label>
                <select name="reset-options" id="reset-options" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected disabled value="">Choose an option</option>
{{--                    <option value="Mobile">Mobile</option>--}}
                    <option value="Email">Email</option>
                </select>
            </div>
            <div id="emailDIV" hidden="hidden">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                    email</label>
                <input type="email" name="email" id="email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="name@example.com">
            </div>
            <div id="mobileDIV" hidden="hidden">
                <label for="mobile" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                    mobile</label>
                <input type="text" name="mobile" id="mobile"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="+989123456789">
            </div>
            <div class="flex items-start">
                <div class="flex items-start ">
                    <div class="flex items-center h-5">
                        <input id="remember" type="checkbox" value=""
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800"
                            required>
                    </div>
                    <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I agree with
                        the <a href="#" class="text-blue-600 hover:underline dark:text-blue-500">terms and
                            conditions</a>.</label>
                </div>
            </div>
            <button type="submit"
                class="md:w-auto w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-6 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Reset
                password</button>

        </form>
    </div>

</body>


</html>
