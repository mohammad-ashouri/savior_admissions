@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> New user</h1>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> General information</h1>
                        </div>

                        {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <div>
                                <label for="first_name"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                    name</label>
                                <input type="text" id="first_name" value="" disabled
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="John" required>
                            </div>
                            <div>
                                <label for="last_name"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                    name</label>
                                <input type="text" id="last_name" value="" disabled
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Doe" required>
                            </div>
                            <div>
                                <label for="father-name"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Father
                                    name</label>
                                <input type="text" id="father-name"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Max" required>
                            </div>
                            <div>
                                <label for="Birthdate"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Birthdate</label>
                                <input type="date" id="Birthdate"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="15/08/1999" required>
                            </div>
                            <div>
                                <label for="Country"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Country</label>
                                <input type="text" id="Country"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Iran" required>
                            </div>
                            <div>
                                <label for="city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State/City</label>
                                <input type="text" id="city"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Qom" required>
                            </div>
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                                    address</label>
                                <input type="email" id="email" value="" disabled
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="john.doe@company.com" required>
                            </div>
                            <div>
                                <label for="mobile"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mobile
                                    number</label>
                                <input type="tel" id="mobile" value="" disabled
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="123-45-678" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                            </div>
                            <div>
                                <label for="address"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                                <input type="url" id="address"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Qom,st moalem haram" required>
                            </div>
                            <div>
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone
                                    number</label>
                                <input type="tel" id="phone"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="123-45-678" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                            </div>
                            <div>
                                <label for="zip/postal code"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">zip/postal
                                    code</label>
                                <input type="text" id="zip/postal code"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       pattern="[0-9]{10}" placeholder="1234567890" required>
                            </div>
                            <div>
                                <label for="Role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
                            </div>
                        </div>

                        <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Save all
                        </button>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
