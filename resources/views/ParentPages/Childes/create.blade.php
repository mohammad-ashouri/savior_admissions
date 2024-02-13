@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> New Child</h1>
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
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <form id="new-child" method="post" action="{{route('Childes.store')}}">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="first_name_en"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        First Name</label>
                                    <input type="text" id="first_name_en" value="{{ old('first_name_en') }}" name="first_name_en"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter first name of your child" required>
                                </div>
                                <div>
                                    <label for="last_name_en"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Last Name</label>
                                    <input type="text" id="last_name_en" value="{{ old('last_name_en') }}" name="last_name_en"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter last name of your child" required>
                                </div>
                                <div>
                                    <label for="first_name_en"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        First Name</label>
                                    <input type="text" id="first_name_en" value="{{ old('first_name_en') }}" name="first_name_en"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter first name of your child" required>
                                </div>
                                <div>
                                    <label for="last_name_en"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Last Name</label>
                                    <input type="text" id="last_name_en" value="{{ old('last_name_en') }}" name="last_name_en"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter last name of your child" required>
                                </div>
                                <div>
                                    <label for="birthdate"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Birthdate</label>
                                    <input type="date" id="birthdate" value="{{ old('birthdate') }}" name="birthdate"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           title="Select birthdate of your child" required>
                                </div>
                                <div>
                                    <label for="gender"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Gender</label>
                                    <select id="gender" name="gender"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select student birthplace" required>
                                        <option selected disabled value="">Select student gender</option>
                                        <option @if(old('gender')=='Male') selected
                                                @endif value="Male">Male
                                        </option>
                                        <option @if(old('gender')=='Female') selected
                                                @endif value="Female">Female
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="birthplace"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Birthplace</label>
                                    <select id="birthplace" name="birthplace"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select student birthplace" required>
                                        <option selected disabled value="">Select student birthplace</option>
                                        @foreach($birthplaces as $birthplace)
                                            <option @if(old('birthplace')==$birthplace->id) selected
                                                    @endif value="{{$birthplace->id}}">{{$birthplace->en_short_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="nationality"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Nationality</label>
                                    <select id="nationality" name="nationality"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select student nationality" required>
                                        <option selected disabled value="">Select student nationality</option>
                                        @foreach($nationalities as $nationality)
                                            <option @if(old('nationality')==$nationality->id) selected
                                                    @endif value="{{$nationality->id}}">{{$nationality->nationality}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="current_identification_type"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Current Identification Type</label>
                                    <select id="current_identification_type" name="current_identification_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select student identification type" required>
                                        <option selected disabled value="">Select student identification type</option>
                                        @foreach($identificationTypes as $identificationType)
                                            <option
                                                @if(old('current_identification_type')==$identificationType->id) selected
                                                @endif value="{{$identificationType->id}}">{{$identificationType->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="current_identification_code"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Current Identification Code</label>
                                    <input type="text" id="current_identification_code"
                                           value="{{ old('current_identification_code') }}"
                                           name="current_identification_code"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter current identification code of your child" required>
                                </div>
                            </div>
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save
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
