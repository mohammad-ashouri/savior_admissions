@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Application Payment</h1>
            </div>
            @if (count($errors) > 0)
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                     role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                            </svg>
                        </div>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="font-bold">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-3"
                 role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20">
                            <path
                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                        </svg>
                    </div>
                    <span hidden="" id="deadline">{{ $deadline }}</span>
                    <div>
                        You have
                        <span id="timer"></span>
                        seconds to make the payment. Otherwise, your application will be removed from the reserve mode
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <form id="application-payment" method="post" action="/PayApplicationFee"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="mb-6">
                                <label for="payment_method"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Payment Method</label>
                                <select id="payment_method" name="payment_method"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        title="Select payment method" required>
                                    <option selected disabled value="">Select payment method</option>
                                    @foreach($paymentMethods as $paymentMethod)
                                        <option value="{{$paymentMethod->id}}">{{$paymentMethod->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-6" id="offline_payment_div" hidden="hidden">
                                <div
                                    class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-6"
                                    role="alert">
                                    <div class="flex">
                                        <div class="py-1">
                                            <svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 20 20">
                                                <path
                                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                            </svg>
                                        </div>
                                        <span hidden="" id="deadline">{{ $deadline }}</span>
                                        <div>
                                            You must deposit
                                            {{ number_format($checkApplication->applicationInfo->applicationTimingInfo->fee) }}
                                            Rials using one of the following methods (bank account number, bank card
                                            number or Shaba number) and upload the image of your bank slip from the box
                                            below.
                                        </div>
                                    </div>
                                </div>
                                @foreach($paymentMethods as $methods)
                                    @if(empty($methods->description))
                                        @continue
                                    @endif
                                    @php
                                        $descriptions = null;
                                        if ($methods->description) {
                                            $descriptions = json_decode($methods->description, true);
                                        }
                                    @endphp

                                    @if ($descriptions)
                                        @foreach($descriptions as $title => $description)
                                            <label class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                {{ $title }}: {{ $description }}
                                            </label>
                                        @endforeach
                                    @else
                                        <p>No descriptions available</p>
                                    @endif
                                @endforeach

                                <label class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                       for="document_file">Upload your bank slip </label>
                                <input
                                    class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                    id="document_file" name="document_file" type="file"
                                    accept=".png,.jpg,.jpeg,.pdf,.bmp">

                                <img id="image_preview" class="w-full h-auto" src="" alt="Preview Image"
                                     style="display:none; ">
                                <div class="info mb-5">
                                    <div class="dark:text-white font-medium mb-1">File requirements:</div>
                                    <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that these
                                        requirements
                                        are met:
                                    </div>
                                    <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                        <li>
                                            The files must be in this format: png, jpg, jpeg, pdf, bmp
                                        </li>
                                        <li>
                                            Maximum size: 5 MB
                                        </li>
                                    </ul>
                                </div>


                            </div>
                            <div class="mb-6" id="online_payment_iran_div" hidden="hidden">
                                <div
                                    class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-6"
                                    role="alert">
                                    <div class="flex">
                                        <div class="py-1">
                                            <svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 20 20">
                                                <path
                                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                            </svg>
                                        </div>
                                        <span hidden="" id="deadline">{{ $deadline }}</span>
                                        <div>
                                            You must pay the amount
                                            of {{ number_format($checkApplication->applicationInfo->applicationTimingInfo->fee) }}
                                            Rials through the bank payment portal. For this, click on the pay button
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-6" id="online_payment_paypal_div" hidden="hidden">
                                <div
                                    class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-6"
                                    role="alert">
                                    <div class="flex">
                                        <div class="py-1">
                                            <svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 20 20">
                                                <path
                                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                            </svg>
                                        </div>
                                        <span hidden="" id="deadline">{{ $deadline }}</span>
                                        <div>
                                            You must pay the amount
                                            of {{ number_format($checkApplication->applicationInfo->applicationTimingInfo->fee) }}
                                            Rials through the bank payment portal. For this, click on the pay button
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-6">
                                <label for="description"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Description</label>
                                <textarea id="description" placeholder="Enter additional description if you need"
                                          name="description"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>

                            </div>
                            <input type="hidden" name="id" value="{{ $checkApplication->id }}">
                            <button type="submit"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                Pay
                            </button>
                            <a href="{{ url()->previous() }}">
                                <button type="button"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    Back
                                </button>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
