<!DOCTYPE html>
<html class="" dir="rtl" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css',  'resources/js/login.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ثبت نام مدارس منجی</title>
</head>
<body class="bg-light-theme-color-nav-base flex items-center justify-center h-screen">
<div class="bg-light-theme-color-base lg:w-4/6 w-full lg:m-0 m-8 rounded-lg shadow-lg flex">
    <div class="lg:w-2/5 pr-8 lg:inline-block loginPic">
    </div>
    <div class="lg:w-1/2 w-full flex flex-col justify-center items-center p-8">
        <h2 class="lg:text-2xl text-xl font-bold mb-8 w-full text-center ">ثبت نام در مجتمع بین المللی منجی</h2>
        <form id="signup-form" class="space-y-4 w-full">
            <div class="flex mb-6">
                <div class="flex-1 ml-2">
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">نام</label>
                    <input type="text" id="first_name" name="first_name" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="نام خود را وارد کنید">
                </div>
                <div class="flex-1">
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">نام خانوادگی</label>
                    <input type="text" id="last_name" name="last_name" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="نام خانوادگی خود را وارد کنید">
                </div>
            </div>
            <div class="flex mb-6">
                <div class="flex-1 ml-2">
                    <label for="father_name" class="block mb-2 text-sm font-medium text-gray-900 ">نام پدر</label>
                    <input type="text" id="father_name" name="father_name" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="نام پدر خود را وارد کنید">
                </div>
                <div class="flex-1">
                    <label for="birthdate" class="block mb-2 text-sm font-medium text-gray-900 ">تاریخ تولد</label>
                    <input type="date" id="birthdate" name="birthdate" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           title="تاریخ تولد خود را وارد کنید">
                </div>
            </div>
            <div class="flex mb-6">
                <div class="flex-1 ml-2">
                    <label for="national_code" class="block mb-2 text-sm font-medium text-gray-900 ">کدملی/شماره پاسپورت</label>
                    <input type="text" id="national_code" name="national_code" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="کدملی/شماره پاسپورت خود را وارد کنید">
                </div>
                <div class="flex-1">
                    <label for="nationality" class="block mb-2 text-sm font-medium text-gray-900 ">محل صدور</label>
                    <select required name="school" id="school" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected disabled value="">انتخاب کنید</option>
                        @foreach($nationalities as $nationality)
                        <option value="{{$nationality->id}}">{{$nationality->nationality}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex mb-6">
                <div class="flex-1 ml-2">
                    <label for="mobile" class="block mb-2 text-sm font-medium text-gray-900 ">شماره همراه</label>
                    <input type="text" id="mobile" name="mobile" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="شماره همراه خود را وارد کنید">
                </div>
                <div class="flex-1">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">ایمیل</label>
                    <input type="email" id="email" name="email" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="ایمیل خود را وارد کنید">
                </div>
            </div>
            <div class="flex mb-10">
                <div class="flex-1 ml-2">
                    <label for="school" class="block mb-2 text-sm font-medium text-gray-900 ">مدرسه</label>
                    <select required name="school" id="school" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected disabled value="">انتخاب کنید</option>
                        <option value="1">طه</option>
                        <option value="2">طوبی</option>
                        <option value="3">کوثر</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label for="payment_options" class="block mb-2 text-sm font-medium text-gray-900 ">گزینه های پرداخت</label>
                    <select required name="payment_options" id="payment_options" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected disabled value="">انتخاب کنید</option>
                        <option value="1000000">پرداخت هزینه مصاحبه (1.000.000 ریال)</option>
                        <option value="12000000">پرداخت شهریه اول (12.000.000 ریال)</option>
                        <option value="16000000">پرداخت شهریه دوم (16.000.000 ریال)</option>
                        <option value="18000000">پرداخت شهریه سوم (18.000.000 ریال)</option>
                        <option value="20000000">پرداخت شهریه چهارم (20.000.000 ریال)</option>
                    </select>
                </div>
            </div>

            <div class=" text-center">
                <button type="submit"
                        class="lg:w-1/2 w-full bg-blue-700 text-white rounded-lg py-2 hover:bg-blue-800 transition duration-300">
                    ثبت نام و پرداخت
                </button>
            </div>
        </form>
    </div>
</div>
</body>

</html>
