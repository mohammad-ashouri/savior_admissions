@extends('Layouts.panel')
@php
    use App\Models\User;
    $myInfo=User::find(session('id'));
@endphp
@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Profile of {{ $user->generalInformationInfo->first_name }} {{ $user->generalInformationInfo->last_name }}</h1>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-1 col-span-3 bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg">
                    <div>
                        <div class="lg:flex grid lg:items-center items-start lg:space-x-4">
                            <img class="w-28 h-28 rounded-lg"
                                 src="{{ Vite::asset('resources/images/Panel/default_user_icon.png') }}"
                                 alt="">
                            <div class="font-bold dark:text-white">
                                <div class="text-l">{{ $user->generalInformationInfo->first_name_en }} {{ $user->generalInformationInfo->last_name_en }}</div>
                            </div>
                        </div>
                        @can('access-user-role')
                            <div class="mt-3">
                                <form id="change-rules">
                                    @if($myInfo->hasRole('Super Admin'))
                                        <label for="role"
                                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role(s)</label>
                                        @foreach($roles as $value)
                                            <input class="name" name="role[]" type="checkbox" id="{{ $value }}"
                                                   @foreach($generalInformation->user->getRoleNames() as $v)
                                                       @if($v==$value) checked @endif
                                                   @endforeach
                                                   value="{{ $value }}">
                                            <label for="{{ $value }}"> {{ $value }} </label><br/>
                                        @endforeach
                                    @else
                                        <label for="role[]"
                                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                        <select name="role[]" id="role[]"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        >

                                            @foreach($roles as $value)
                                                @if(!$myInfo->hasRole('Super Admin') && ($value == 'Super Admin' or $value == 'Principal' or $value == 'Admissions Officer' or $value == 'Financial Manager' or $value == 'Interviewer'))
                                                    @continue
                                                @endif

                                                <option value="{{ $value }}"
                                                        @if($generalInformation->user->hasRole($value)) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="submit"
                                            class="mt-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Change Rule
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>

                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> General information</h1>
                        </div>
                        <form id="changeUserGeneralInformation">
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="first_name_fa"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                        name (Persian)</label>
                                    <input type="text" id="first_name_fa" name="first_name_fa" value="{{ $user->generalInformationInfo->first_name_fa }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </div>
                                <div>
                                    <label for="last_name_fa"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                        name (Persian)</label>
                                    <input type="text" id="last_name_fa" name="last_name_fa" value="{{ $user->generalInformationInfo->last_name_fa }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </div>
                                <div>
                                    <label for="first_name_en"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                        name (English)</label>
                                    <input type="text" id="first_name_en" name="first_name_en" value="{{ $user->generalInformationInfo->first_name_en }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="John" required>
                                </div>
                                <div>
                                    <label for="last_name_en"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                        name (English)</label>
                                    <input type="text" id="last_name_en" name="last_name_en" value="{{ $user->generalInformationInfo->last_name_en }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Doe" required>
                                </div>
                                <div>
                                    <label for="father-name"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Father
                                        name</label>
                                    <input type="text" id="father-name" name="father_name"
                                           @if($generalInformation->father_name!==null) value=" {{ $generalInformation->father_name }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Max" required>
                                </div>
                                <div>
                                    <label for="gender"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                                    <select required id="gender"
                                            name="gender"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option value="" selected>Choose an option...</option>
                                        <option @if($generalInformation->gender==='Male') selected @endif value="Male">
                                            Male
                                        </option>
                                        <option @if($generalInformation->gender==='Female') selected
                                                @endif  value="Female">Female
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="Birthdate"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Birthdate</label>
                                    <input type="date" id="Birthdate" name="Birthdate"
                                           @if($generalInformation->birthdate!==null) value="{{ $generalInformation->birthdate }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="15/08/1999" required>
                                </div>
                                <div>
                                    <label for="nationality"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nationality</label>
                                    <select required id="nationality"
                                            name="nationality"
                                            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option selected disabled value=""></option>
                                        @foreach($nationalities as $nationality)
                                            <option @if($generalInformation->nationality==$nationality->id) selected
                                                    @endif value="{{ $nationality->id }}">{{$nationality->nationality}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="birthplace"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Birthplace</label>
                                    <select required id="birthplace"
                                            name="birthplace"
                                            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option selected disabled value=""></option>
                                        @foreach($countries as $birthplaces)
                                            <option @if($generalInformation->birthplace==$birthplaces->id) selected
                                                    @endif value="{{ $birthplaces->id }}">{{$birthplaces->en_short_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="passport-number"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Passport
                                        number</label>
                                    <input type="text" id="passport-number" name="PassportNumber"
                                           @if($generalInformation->passport_number!==null) value="{{ $generalInformation->passport_number }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="If not, enter 0" required>
                                </div>
                                <div>
                                    <label for="faragir-code"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Faragir
                                        code</label>
                                    <input type="text" id="faragir-code" name="FaragirCode"
                                           @if($generalInformation->faragir_code!==null) value="{{ $generalInformation->faragir_code }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="If not, enter 0" required>
                                </div>
                                <div>
                                    <label for="Country"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Country</label>
                                    <select required id="Country"
                                            name="Country"
                                            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option selected disabled value=""></option>
                                        @foreach($countries as $country)
                                            <option @if($generalInformation->country==$country->id) selected
                                                    @endif value="{{ $country->id }}">{{$country->en_short_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="city"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State/City</label>
                                    <input type="text" id="city" name="city"
                                           @if($generalInformation->state_city!==null) value="{{ $generalInformation->state_city }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Qom" required>
                                </div>
                                <div>
                                    <label for="email"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                                        address</label>
                                    <input type="email" id="email" name="email" value="{{ $user->email }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="john.doe@company.com" required>
                                </div>
                                <div>
                                    <label for="mobile"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mobile
                                        number</label>
                                    <input type="tel" id="mobile" name="mobile" value="{{ $user->mobile }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Like: +989123456789" required>
                                </div>
                                <div>
                                    <label for="address"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                                    <input type="text" id="address" name="address"
                                           @if($generalInformation->address!==null) value="{{ $generalInformation->address }}"
                                           @endif
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Qom, moallem st, 4th alley, no 433" required>
                                </div>
                                <div>
                                    <label for="phone"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone
                                        number</label>
                                    <input type="tel" id="phone" name="phone"
                                           @if($generalInformation->phone!==null) value="{{ $generalInformation->phone }}"
                                           @endif
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="For example: +982531234567" required>
                                </div>
                                <div>
                                    <label for="zip/postalcode"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">zip/postal
                                        code</label>
                                    <input type="text" id="zip/postalcode" name="postalcode"
                                           @if($generalInformation->postal_code!==null) value="{{ $generalInformation->postal_code }}"
                                           @endif
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           pattern="[0-9]{10}" placeholder="1234567890" required>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $user->id }}" name="user_id">
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save all
                            </button>
                        </form>

                    </div>

                    <div class="Password-information bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> Password information</h1>
                        </div>
                        <form id="changeUserPassword">
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="New_password"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New
                                        password</label>
                                    <input type="password" id="New_password" name="New_password"
                                           autocomplete="new-password"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="••••••••" required>
                                </div>
                                <div>
                                    <label for="Confirm_password"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                                        password</label>
                                    <input type="password" id="Confirm_password" name="confirm-password"
                                           autocomplete="new-password"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="••••••••" required>
                                </div>
                            </div>
                            <div class="info mb-5">
                                <div class="dark:text-white font-medium mb-1">Password requirements:</div>
                                <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that these requirements
                                    are met:
                                </div>
                                <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                    <li>
                                        At least 8 characters (and up to 20 characters)
                                    </li>
                                    <li>
                                        At least one english character
                                    </li>
                                </ul>
                            </div>
                            <input type="hidden" value="{{ $user->id }}" name="user_id">
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save all
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @if($user->hasRole('Student'))
                @can('change-student-information')
                    <div class="Student-information bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> Student Information</h1>
                        </div>
                        <form id="changeStudentInformation">
{{--                            <div class="grid gap-6 mb-6 md:grid-cols-2">--}}
{{--                                <div>--}}
{{--                                    <label for="school"--}}
{{--                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">School</label>--}}
{{--                                    <select id="school" name="school"--}}
{{--                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"--}}
{{--                                            required>--}}
{{--                                        <option value="" disabled selected>Select school...</option>--}}
{{--                                        @foreach($schools as $school)--}}
{{--                                            <option value="{{ $school->id }}"--}}
{{--                                                    @if($user->school_id  == $school->id) selected @endif>{{ $school->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="guardian"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Guardian</label>
                                    <select id="guardian" name="guardian"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option value="" disabled selected>Select guardian...</option>
                                        @foreach($parents as $parent)
                                            @foreach($parent->users as $guardian)
                                                <option
                                                    @if(!empty($studentInformation) and $studentInformation->guardian==$guardian->id) selected
                                                    @endif
                                                    value="{{$guardian->id}}">{{ $guardian->id . " - " . $guardian->generalInformationInfo->first_name_en . " " . $guardian->generalInformationInfo->last_name_en }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="guardian_student_relationship"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Guardian
                                        Student Relationship</label>
                                    <select id="guardian_student_relationship" name="guardian_student_relationship"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option value="" disabled selected>Select guardian student relationship...
                                        </option>
                                        @foreach($guardianStudentRelationships as $guardianStudentRelationship)
                                            <option
                                                @if(!empty($studentInformation) and $studentInformation->guardian_student_relationship==$guardianStudentRelationship->id) selected
                                                @endif
                                                value="{{$guardianStudentRelationship->id}}">{{ $guardianStudentRelationship->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="father"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student
                                        Father</label>
                                    <select id="father" name="father"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option value="" disabled selected>Select father...</option>
                                        @foreach($parents as $parent)
                                            @foreach($parent->users as $father)
                                                <option
                                                    @if(!empty($studentInformation) and $studentInformation->parent_father_id==$father->id) selected
                                                    @endif
                                                    value="{{$father->id}}">{{ $father->id . " - " . $father->generalInformationInfo->first_name_en . " " . $father->generalInformationInfo->last_name_en }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="mother"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student
                                        Mother</label>
                                    <select id="mother" name="mother"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option value="" disabled selected>Select mother...</option>
                                        @foreach($parents as $parent)
                                            @foreach($parent->users as $mother)
                                                <option
                                                    @if(!empty($studentInformation) and $studentInformation->parent_mother_id==$mother->id) selected
                                                    @endif
                                                    value="{{$mother->id}}">{{ $mother->id . " - " . $mother->generalInformationInfo->first_name_en . " " . $mother->generalInformationInfo->last_name_en }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="current_nationality"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current
                                        Nationality</label>
                                    <select id="current_nationality" name="current_nationality"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option value="" disabled selected>Select current nationality...</option>
                                        @foreach($countries as $country)
                                            <option
                                                @if(!empty($studentInformation) and $studentInformation->current_nationality==$country->id) selected
                                                @endif value="{{ $country->id }}">{{$country->nationality}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="current_identification_type"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current
                                        Identification Type</label>
                                    <select id="current_identification_type" name="current_identification_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option value="" disabled selected>Select current identification type...
                                        </option>
                                        @foreach($currentIdentificationTypes as $currentIdentificationType)
                                            <option value="{{ $currentIdentificationType->id }}"
                                                    @if(!empty($studentInformation) and $studentInformation->current_identification_type==$currentIdentificationType->id) selected @endif>{{ $currentIdentificationType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="current_identification_code"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current
                                        Identification Code</label>
                                    <input type="text" id="current_identification_code"
                                           name="current_identification_code"
                                           @if(!empty($studentInformation) and $studentInformation->current_identification_code) value="{{ $studentInformation->current_identification_code }}"
                                           @endif
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="For example: IRC123456789" required>
                                </div>
                                <div>
                                    <label for="status"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                    <select id="status" name="status"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option value="" disabled selected>Select status...</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}"
                                                    @if(!empty($studentInformation) and $studentInformation->status==$status->id) selected @endif>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                                <h1 class="text-xl font-medium"> Extras</h1>
                            </div>
                            <div class="grid gap-6 mb-6">
                                <div>
                                    <table id="student-extra-information-table"
                                           class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-4">
                                                <div class=" items-center text-center">
                                                    Name
                                                </div>
                                            </th>
                                            <th scope="col" class="p-4">
                                                <div class=" items-center text-center">
                                                    Description
                                                </div>
                                            </th>
                                            <th scope="col" class="p-4">
                                                <div class=" items-center text-center">
                                                    Action
                                                </div>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($studentInformation->extraInformations))
                                            @foreach($studentInformation->extraInformations as $extras)
                                                <tr
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="p-4">
                                                        <input type="text" id="title" name="title[]"
                                                               value="{{$extras->name}}"
                                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                               required>
                                                    </td>
                                                    <td class="p-4">
                                                        <input type="text" id="description" name="description[]"
                                                               value="{{$extras->description}}"
                                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                               required>
                                                    </td>
                                                    <td class="p-4 text-center">
                                                        <button type="button"
                                                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-2 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 delete-row">
                                                            <i class="las la-trash" style="font-size: 24px"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="p-4">
                                                    <input type="text" id="title" name="title[]"
                                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                           required>
                                                </td>
                                                <td class="p-4">
                                                    <input type="text" id="description" name="description[]"
                                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                           required>
                                                </td>
                                                <td class="p-4 text-center">
                                                    <button type="button"
                                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-2 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 delete-row">
                                                        <i class="las la-trash" style="font-size: 24px"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <button type="button"
                                                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-2 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 add-row">
                                            <i class="las la-plus-circle" style="font-size: 24px"></i>
                                            Add extras
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $user->id }}" name="user_id">
                            <div class="">
                                <button type="submit"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Save all
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">

                    </div>
                @endcan
            @endif
            @if($user->hasRole('Principal'))
                @can('change-principal-information')
                    <div class="Student-information bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mt-4">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> Principal information</h1>
                        </div>
                        <form id="changePrincipalInformation">
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="school[]"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">School</label>
                                    <select id="school[]" name="school[]" multiple="multiple"
                                            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}"
                                                    @if(isset($user->school_id) && is_array($user->school_id) && in_array($school->id, $user->school_id)) selected @endif>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $user->id }}" name="user_id">
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save all
                            </button>
                        </form>
                    </div>
                @endcan
            @endif
        </div>
    </div>
@endsection
