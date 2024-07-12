@extends('Layouts.panel')
{{--@dd($myGeneralInformation)--}}
@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Profile</h1>
            </div>
            @include('GeneralPages.errors.session.error')
            @include('GeneralPages.errors.session.success')

            @if($myGeneralInformation->status==0)
                <div
                    class="bg-yellow-100 border-t-4 border-yellow-500 rounded-b mb-3 text-yellow-900 px-4 py-3 shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="fill-current h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">Please complete your profile to continue.</p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-3 gap-4 mb-4">
                {{--                <div class="lg:col-span-1 col-span-3 bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg">--}}
                {{--                    <div>--}}
                {{--                        <div class="lg:flex grid lg:items-center items-start lg:space-x-4">--}}
                {{--                            <img class="w-28 h-28 rounded-lg"--}}
                {{--                                 src="--}}
                {{--                                 @if($myDocuments)--}}
                {{--                                 {{ str_replace('public','storage',$myDocuments->src) }}--}}
                {{--                                 @else--}}
                {{--                                    {{Vite::asset('resources/images/Panel/default_user_icon.png')}}--}}
                {{--                                 @endif--}}
                {{--                                 "--}}
                {{--                                 alt="">--}}
                {{--                            <div class="font-bold dark:text-white">--}}
                {{--                                <div--}}
                {{--                                    class="text-l">{{ $myGeneralInformation->first_name_en }} {{ $myGeneralInformation->last_name_en }}</div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> General information</h1>
                        </div>

                        <form id="general-information" method="post" action="Profile/EditMyProfile">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="first_name_en"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                        name (English)</label>
                                    <input type="text" id="first_name_en"
                                           value="{{ $myGeneralInformation->first_name_en }}"
                                           disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="John" required>
                                </div>
                                <div>
                                    <label for="last_name_en"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                        name (English)</label>
                                    <input type="text" id="last_name_en"
                                           value="{{ $myGeneralInformation->last_name_en }}"
                                           disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Doe" required>
                                </div>
                                <div>
                                    <label for="first_name_fa"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                        name (Persian)</label>
                                    <input type="text" id="first_name_fa" name="first_name_fa"
                                           @if($myGeneralInformation->status===1) disabled
                                           @endif value="{{ $myGeneralInformation->first_name_fa }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="به عنوان مثال: علی"
                                           required>
                                </div>
                                <div>
                                    <label for="last_name_fa"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                        name (Persian)</label>
                                    <input type="text" id="last_name_fa" name="last_name_fa"
                                           @if($myGeneralInformation->status===1) disabled
                                           @endif value="{{ $myGeneralInformation->last_name_fa }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="به عنوان مثال: حسنی"
                                           required>
                                </div>
                                <div>
                                    <label for="father-name"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Father
                                        name</label>
                                    <input type="text" id="father-name" name="father-name"
                                           @if($myGeneralInformation->status===1)
                                               disabled @endif value="{{ $myGeneralInformation->father_name }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="For example: Max" required>
                                </div>
                                <div>
                                    <label for="gender"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                                    <select
                                        @if($myGeneralInformation->gender!==null and $myGeneralInformation->status===1) disabled
                                        @endif required
                                        id="gender" name="gender"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option value="" selected>Choose an option...</option>
                                        <option @if($myGeneralInformation->gender==='Male') selected
                                                @endif value="Male">Male
                                        </option>
                                        <option @if($myGeneralInformation->gender==='Female') selected
                                                @endif  value="Female">Female
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="Birthdate"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Birthdate</label>
                                    <input type="date" id="Birthdate" name="Birthdate"
                                           @if($myGeneralInformation->status===1)
                                               disabled @endif value="{{ $myGeneralInformation->birthdate }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="15/08/1999" required>
                                </div>
                                <div>
                                    <label for="nationality"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nationality</label>
                                    <select required id="nationality"
                                            name="nationality" @if($myGeneralInformation->status===1) disabled @endif
                                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option selected disabled value="">Select an option</option>
                                        @foreach($countries as $nationality)
                                            <option @if($myGeneralInformation->nationality==$nationality->id) selected
                                                    @endif value="{{ $nationality->id }}">{{$nationality->nationality}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="birthplace"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Birthplace</label>
                                    <select required id="birthplace"
                                            name="birthplace" @if($myGeneralInformation->status===1) disabled @endif
                                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option selected disabled value="">Select an option</option>
                                        @foreach($countries as $birthplaces)
                                            <option @if($myGeneralInformation->birthplace==$birthplaces->id) selected
                                                    @endif value="{{ $birthplaces->id }}">{{$birthplaces->en_short_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="passport-number"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Passport
                                        number</label>
                                    <input type="text" id="passport-number" name="PassportNumber"
                                           value="{{ $myGeneralInformation->passport_number }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="If not, enter 0" required>
                                </div>
                                <div>
                                    <label for="faragir-code"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Faragir
                                        code</label>
                                    <input type="text" id="faragir-code" name="FaragirCode"
                                           value="{{ $myGeneralInformation->faragir_code }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="If not, enter 0" required>
                                </div>
                                <div>
                                    <label for="Country"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Residence
                                        Country</label>
                                    <select required id="Country"
                                            name="Country"
                                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option selected disabled value="">Select an option</option>
                                        @foreach($countries as $country)
                                            <option @if($myGeneralInformation->country==$country->id) selected
                                                    @endif value="{{ $country->id }}">{{$country->en_short_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="city"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Residence
                                        State/City</label>
                                    <input type="text" id="city" name="city"
                                           value="{{ $myGeneralInformation->state_city }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Qom" required>
                                </div>
                                <div>
                                    <label for="email"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                                        address (Optional)</label>
                                    <input type="email" name="email" id="email"
                                           value="{{ $myGeneralInformation->user->email }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="@if($myGeneralInformation->status===0) Like: john.doe@company.com @endif">
                                </div>
                                <div>
                                    <label for="mobile"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mobile
                                        number</label>
                                    <input type="tel" id="mobile" name="mobile"
                                           value="{{ $myGeneralInformation->user->mobile }}"
                                           @if($myGeneralInformation->status===1 or $myGeneralInformation->user->mobile) disabled
                                           @endif
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter like this: +989123456789" required>
                                </div>
                                <div>
                                    <label for="address"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Residence
                                        Address</label>
                                    <input type="text" id="address" name="address"
                                           value="{{ $myGeneralInformation->address }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter like this: Qom, moallem st, 43th alley, no 1546" required>
                                </div>
                                <div>
                                    <label for="phone"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone
                                        number</label>
                                    <input type="tel" id="phone" name="phone"
                                           value="{{ $myGeneralInformation->phone }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="For example: +982531234567" required>
                                </div>
                                <div>
                                    <label for="postal-code"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">zip/postal
                                        code</label>
                                    <input type="text" id="postal-code" name="zip/postalcode"
                                           value="{{ $myGeneralInformation->postal_code }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           pattern="[0-9]{10}" placeholder="For example: 1234567890" required>
                                </div>
                                <div>
                                    <label for="Role"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role(s)</label>
                                    <label for="Role"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@if(!empty($myGeneralInformation->user->getRoleNames()))
                                            @foreach($myGeneralInformation->user->getRoleNames() as $v)
                                                {{ $v }}
                                            @endforeach
                                        @endif
                                    </label>
                                </div>
                            </div>

                            {{--                            @if($myGeneralInformation->status===0)--}}
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save all
                            </button>
                            {{--                            @endif--}}
                        </form>

                    </div>

                    {{--                    <div class="Password-information bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg">--}}
                    {{--                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">--}}
                    {{--                            <h1 class="text-2xl font-medium"> Password information</h1>--}}
                    {{--                        </div>--}}


                    {{--                        <form id="reset-password">--}}
                    {{--                            <div class="grid gap-6 mb-6 md:grid-cols-2">--}}
                    {{--                                <div>--}}
                    {{--                                    <label for="Current_password"--}}
                    {{--                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current--}}
                    {{--                                        password</label>--}}
                    {{--                                    <input type="password" id="Current_password" name="Current_password"--}}
                    {{--                                           autocomplete="new-password"--}}
                    {{--                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"--}}
                    {{--                                           placeholder="••••••••" required>--}}
                    {{--                                </div>--}}
                    {{--                                <div>--}}
                    {{--                                    <label for="New_password"--}}
                    {{--                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New--}}
                    {{--                                        password</label>--}}
                    {{--                                    <input type="password" id="New_password" name="New_password"--}}
                    {{--                                           autocomplete="new-password"--}}
                    {{--                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"--}}
                    {{--                                           placeholder="••••••••" required>--}}
                    {{--                                </div>--}}
                    {{--                                <div>--}}
                    {{--                                    <label for="Confirm_password"--}}
                    {{--                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm--}}
                    {{--                                        password</label>--}}
                    {{--                                    <input type="password" id="Confirm_password" name="Confirm_password"--}}
                    {{--                                           autocomplete="new-password"--}}
                    {{--                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"--}}
                    {{--                                           placeholder="••••••••" required>--}}
                    {{--                                </div>--}}


                    {{--                            </div>--}}
                    {{--                            <div class="info mb-5">--}}
                    {{--                                <div class="dark:text-white font-medium mb-1">Password requirements:</div>--}}
                    {{--                                <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that these requirements--}}
                    {{--                                    are met:--}}
                    {{--                                </div>--}}
                    {{--                                <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">--}}
                    {{--                                    <li>--}}
                    {{--                                        At least 8 characters (and up to 20 characters)--}}
                    {{--                                    </li>--}}
                    {{--                                    <li>--}}
                    {{--                                        At least one english character--}}
                    {{--                                    </li>--}}
                    {{--                                </ul>--}}

                    {{--                            </div>--}}

                    {{--                            <button type="submit"--}}
                    {{--                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">--}}
                    {{--                                Save all--}}
                    {{--                            </button>--}}
                    {{--                        </form>--}}

                    {{--                    </div>--}}

                </div>

            </div>

        </div>
    </div>
@endsection
