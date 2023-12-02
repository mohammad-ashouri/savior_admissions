@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Profile of {{ $user->name }} {{ $user->family }}</h1>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-1 col-span-3 bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg">
                    <div>
                        <div class="lg:flex grid lg:items-center items-start lg:space-x-4">
                            <img class="w-28 h-28 rounded-lg"
                                 src="https://flowbite.com/application-ui/demo/images/users/jese-leos-2x.png"
                                 alt="">
                            <div class="font-bold dark:text-white">
                                <div class="text-l">{{ $user->name }} {{ $user->family }}</div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <form id="change-rules">
                                <label for="Role"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role(s)</label>
                                @foreach($roles as $value)
                                    <input class="name" name="roles[]" type="checkbox" id="{{ $value }}"
                                           @foreach($generalInformation->user->getRoleNames() as $v)
                                               @if($v==$value) checked @endif
                                           @endforeach
                                           value="{{ $value }}">
                                    <label for="{{ $value }}"> {{ $value }} </label><br/>
                                @endforeach
                                <input type="hidden" value="{{ $user->id }}" name="user_id">
                                <button type="submit"
                                        class="mt-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Change rules
                                </button>
                            </form>
                        </div>
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
                                    <label for="first_name"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                        name</label>
                                    <input type="text" id="first_name" name="first_name" value="{{ $user->name }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="John" required>
                                </div>
                                <div>
                                    <label for="last_name"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                        name</label>
                                    <input type="text" id="last_name" name="last_name" value="{{ $user->family }}"
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
                                        <option @if($generalInformation->gender==='male') selected @endif value="male">
                                            Male
                                        </option>
                                        <option @if($generalInformation->gender==='female') selected
                                                @endif  value="female">Female
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
                                    <input type="text" id="nationality" name="Nationality"
                                           @if($generalInformation->nationality!==null) value=" {{ $generalInformation->nationality }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Iran" required>
                                </div>
                                <div>
                                    <label for="birthplace"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Birthplace</label>
                                    <input type="text" id="birthplace" name="birthplace"
                                           @if($generalInformation->birthplace!==null) value=" {{ $generalInformation->birthplace }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Irani" required>
                                </div>
                                <div>
                                    <label for="passport-number"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Passport number</label>
                                    <input type="text" id="passport-number" name="PassportNumber"
                                           @if($generalInformation->passport_number!==null) value=" {{ $generalInformation->passport_number }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="If not, enter 0" required>
                                </div>
                                <div>
                                    <label for="faragir-code"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Faragir code</label>
                                    <input type="text" id="faragir-code" name="FaragirCode"
                                           @if($generalInformation->faragir_code!==null) value=" {{ $generalInformation->faragir_code }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="If not, enter 0" required>
                                </div>
                                <div>
                                    <label for="Country"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Country</label>
                                    <input type="text" id="Country" name="Country"
                                           @if($generalInformation->country!==null) value=" {{ $generalInformation->country }}"
                                           @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Iran" required>
                                </div>
                                <div>
                                    <label for="city"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State/City</label>
                                    <input type="text" id="city" name="city"
                                           @if($generalInformation->state_city!==null) value=" {{ $generalInformation->state_city }}"
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
                                           @if($generalInformation->address!==null) value=" {{ $generalInformation->address }}"
                                           @endif
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Qom, moallem st, 4th alley, no 433" required>
                                </div>
                                <div>
                                    <label for="phone"
                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone
                                        number</label>
                                    <input type="tel" id="phone" name="phone"
                                           @if($generalInformation->phone!==null) value=" {{ $generalInformation->phone }}"
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

        </div>
    </div>
@endsection
