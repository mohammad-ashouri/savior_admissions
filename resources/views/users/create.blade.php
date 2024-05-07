@extends('Layouts.panel')
@php
    use App\Models\User;
    $me=User::find(auth()->user()->id);
@endphp
@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> New user</h1>
            </div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong>Something went wrong.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <div>
                                <label for="first_name_fa"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                    name (Persian)</label>
                                <input type="text" id="first_name_fa" name="first_name_fa" value=""
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="مثال: علی" required>
                            </div>
                            <div>
                                <label for="last_name_fa"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                    name (Persian)</label>
                                <input type="text" id="last_name_fa" name="last_name_fa" value=""
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="مثال: حسینی" required>
                            </div>
                            <div>
                                <label for="first_name_en"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                    name (English)</label>
                                <input type="text" id="first_name_en" name="first_name_en" value=""
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="For example: John" required>
                            </div>
                            <div>
                                <label for="last_name_en"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                    name (English)</label>
                                <input type="text" id="last_name_en" name="last_name_en" value=""
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="For example: Doe" required>
                            </div>
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                                    address</label>
                                <input type="email" id="email" name="email" value=""
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="john.doe@company.com" required>
                            </div>
                            <div>
                                <label for="mobile"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mobile
                                    number</label>
                                <input type="tel" id="mobile" name="mobile" value=""
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Like: +989123456789" required>
                            </div>
                            <div>
                                <label for="password"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                <input type="password" id="password" name="password" value="" minlength="8"
                                       maxlength="20"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Minimum length:8 | Maximum length=20" required>
                            </div>
{{--                            <div>--}}
{{--                                <label for="school"--}}
{{--                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">School</label>--}}
{{--                                <select id="school" name="school"--}}
{{--                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"--}}
{{--                                        required>--}}
{{--                                    <option value="" disabled selected>Select school...</option>--}}
{{--                                    @foreach($schools as $school)--}}
{{--                                        <option value="{{ $school->id }}">{{ $school->name }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
                            <div>

                                @if($me->hasRole('Super Admin'))
                                    <label for="role"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role(s)</label>
                                    @foreach ($roles as $value)
                                        <label>{{ Form::checkbox('role[]', $value->name, false, array('class' => 'name')) }}
                                            {{ $value->name }}</label>
                                        <br/>
                                    @endforeach
                                @else
                                    <label for="role"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                    <select name="role" id="role" required
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option value="" selected disabled>Select role</option>
                                        @foreach ($roles as $value)
                                            @if (($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) and ($value->name == 'Super Admin' or $value->name == 'Principal' or $value->name == 'Admissions Officer' or $value->name == 'Financial Manager' or $value->name == 'Interviewer'))
                                                @continue
                                            @endif

                                            <option value="{{ $value->name }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
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
