<div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
        <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
            <h1 class="text-2xl font-medium"> New Student</h1>
        </div>
        <x-flash-messages/>
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div class="lg:col-span-2 col-span-3 ">
                <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                    <form wire:submit.prevent="store">
                        @csrf
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <div>
                                <label for="first_name_en"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    First Name (English)</label>
                                <input type="text" wire:model="first_name_en"
                                       name="first_name_en"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter first name of your student">
                                <x-input-error :messages="$errors->get('first_name_en')"/>
                            </div>
                            <div>
                                <label for="last_name_en"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Last Name (English)</label>
                                <input type="text" wire:model="last_name_en"
                                       name="last_name_en"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter last name of your student">
                                <x-input-error :messages="$errors->get('last_name_en')"/>
                            </div>
                            <div>
                                <label for="first_name_fa"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    First Name (Persian)</label>
                                <input type="text" wire:model="first_name_fa"
                                       name="first_name_fa"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="نام فرزند خود را به فارسی وارد نمایید">
                                <x-input-error :messages="$errors->get('first_name_fa')"/>
                            </div>
                            <div>
                                <label for="last_name_fa"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Last Name (Persian)</label>
                                <input type="text" wire:model="last_name_fa"
                                       name="last_name_fa"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="نام خانوادگی فرزند خود را به فارسی وارد نمایید">
                                <x-input-error :messages="$errors->get('last_name_fa')"/>
                            </div>
                            <div>
                                <label for="birthdate"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Birthdate</label>
                                <input type="date" wire:model="birthdate" name="birthdate"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       title="Select birthdate of your student">
                                <x-input-error :messages="$errors->get('birthdate')"/>
                            </div>
                            <div>
                                <label for="gender"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Gender</label>
                                <select wire:model="gender" name="gender"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        title="Select student birthplace">
                                    <option selected disabled value="">Select student gender</option>
                                    <option value="Male">Male
                                    </option>
                                    <option value="Female">Female
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')"/>
                            </div>
                            <div>
                                <label for="birthplace"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Birthplace</label>
                                <select wire:model="birthplace" name="birthplace"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        title="Select student birthplace">
                                    <option selected disabled value="">Select student birthplace</option>
                                    @foreach($birthplaces as $birthplace)
                                        <option value="{{$birthplace->id}}">{{$birthplace->en_short_name}}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('birthplace')"/>
                            </div>
                            <div>
                                <label for="nationality"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Nationality</label>
                                <select wire:model="nationality" name="nationality"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        title="Select student nationality">
                                    <option selected disabled value="">Select student nationality</option>
                                    @foreach($nationalities as $nationality)
                                        <option value="{{$nationality->id}}">{{$nationality->nationality}}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('nationality')"/>
                            </div>
                            {{--                                <div>--}}
                            {{--                                    <label for="current_identification_type"--}}
                            {{--                                           class="block mb-2  font-bold text-gray-900 dark:text-white">--}}
                            {{--                                        Current Identification Type</label>--}}
                            {{--                                    <select id="current_identification_type" name="current_identification_type"--}}
                            {{--                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"--}}
                            {{--                                            title="Select student identification type" >--}}
                            {{--                                        <option selected disabled value="">Select student identification type</option>--}}
                            {{--                                        @foreach($identificationTypes as $identificationType)--}}
                            {{--                                            <option--}}
                            {{--                                                @if(old('current_identification_type')==$identificationType->id) selected--}}
                            {{--                                                @endif value="{{$identificationType->id}}">{{$identificationType->name}}--}}
                            {{--                                            </option>--}}
                            {{--                                        @endforeach--}}
                            {{--                                    </select>--}}
                            {{--                                </div>--}}
                            <div>
                                <label for="faragir_code"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Faragir Code</label>
                                <input type="text" wire:model="faragir_code"
                                       name="faragir_code"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter current faragir code of your student">
                                <x-input-error :messages="$errors->get('faragir_code')"/>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" wire:loading.remove
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save
                            </button>
                            <div class="text-center">
                                <p class="text-blue-600" wire:loading>Saving student...</p>
                            </div>
                            <a href="{{ route('Students.index') }}">
                                <button type="button"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    Back
                                </button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
