<div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
        <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
            <h1 class="text-2xl font-medium"> Upload Student's Documents And Information</h1>
        </div>
        <div class="bg-yellow-100 border-t-4 border-red-500 rounded-b text-yellow-900 px-4 py-3 mb-3 shadow-md"
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
                    <p class="font-bold">Seconder description: {{ $student_appliance_status->seconder_description }}</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div class="lg:col-span-2 col-span-3 ">
                <div class=" bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                    <form wire:submit="update">
                        @csrf
                        <div class="grid gap-6 mb-6">
                            <div>
                                {{--                                    Medical Informations--}}
                                <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                                    <h1 class="text-xl font-medium">1- Medical Information</h1>
                                </div>
                                <div>
                                    <label for="blood_group"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Student blood group</label>
                                    <select id="blood_group" wire:model="form.blood_group"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select blood group">
                                        <option @selected(!$form->blood_group)>Select an option</option>
                                        @foreach($bloodGroups as $bloodGroup)
                                            <option value="{{$bloodGroup->id}}">{{$bloodGroup->name}}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('form.blood_group')"/>
                                </div>
                                <div class="mt-3">
                                    <label for="other_considerations"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Other considerations (Optional)</label>
                                    <textarea id="other_considerations"
                                              placeholder="example: hospitalisations, surgery, allergies, accidents"
                                              name="other_considerations"
                                              wire:model="form.other_considerations"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('form.other_considerations')"/>
                                </div>
                                {{--                                    Family Informations--}}
                                <div class="grid grid-cols-1 gap-4 mt-6 mb-4 text-black dark:text-white">
                                    <h1 class="text-xl font-medium">2- Family Information</h1>
                                </div>
                                <div class="grid grid-cols-2" x-data="{ relationship: {{$form->relationship}} }">
                                    <div class="mr-2">
                                        <label for="relationship"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Your relationship with the child</label>
                                        <select id="relationship" name="relationship"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                x-model="relationship"
                                                wire:model="form.relationship"
                                                title="Select relationship">
                                            <option @selected(!$form->relationship)>Select an option</option>
                                            @foreach($guardianStudentRelationships as $guardianStudentRelationship)
                                                <option
                                                    value="{{$guardianStudentRelationship->id}}">{{$guardianStudentRelationship->name}}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('form.relationship')"/>
                                    </div>
                                    <div class="ml-2" id="martial-status-div">
                                        <label for="marital_status"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Marital status</label>
                                        <select id="marital_status" name="marital_status"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                wire:model="form.marital_status"
                                                title="Select blood group">
                                            <option @selected(!$form->marital_status)>Select an option</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('form.marital_status')"/>
                                    </div>
                                    <div class="mt-3 mr-2" x-transition x-show="relationship == '3'">
                                        <label for="relation_name"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Relation name</label>
                                        <input type="text" id="relation_name" name="relation_name"
                                               wire:model="form.relation_name"
                                               x-bind="relationship == '3'"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                               placeholder="example: Grandfather">
                                        <x-input-error class="mt-2" :messages="$errors->get('form.relation_name')"/>
                                    </div>
                                </div>
                                <div id="father-div">
                                    <div class="grid grid-cols-1 gap-4 mt-6 text-black dark:text-white">
                                        <h1 class="text-xl font-medium">Father Information</h1>
                                    </div>
                                    <div class="grid md:grid-cols-2">
                                        <div class="mt-3 mr-2">
                                            <label for="father_name"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Father's name</label>
                                            <input type="text" id="father_name" name="father_name"
                                                   wire:model="form.father_name"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Father's Name">
                                            <x-input-error class="mt-2" :messages="$errors->get('form.father_name')"/>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="father_family"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Father's family</label>
                                            <input type="text" id="father_family" name="father_family"
                                                   wire:model="form.father_family"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Father's Family">
                                            <x-input-error class="mt-2" :messages="$errors->get('form.father_family')"/>
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="father_mobile"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Father's Phone Number</label>
                                            <input type="text" id="father_mobile" name="father_mobile"
                                                   wire:model="form.father_mobile"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Father's Mobile">
                                            <x-input-error class="mt-2" :messages="$errors->get('form.father_mobile')"/>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="father_email"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Father's email (Optional)</label>
                                            <input type="email" id="father_email" name="father_email"
                                                   wire:model="form.father_email"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Father's Email">
                                            <x-input-error class="mt-2" :messages="$errors->get('form.father_email')"/>
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="father_occupation"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Father's occupation</label>
                                            <input type="text" id="father_occupation" name="father_occupation"
                                                   wire:model="form.father_occupation"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Father's Occupation">
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.father_occupation')"/>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="father_qualification"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Father's degree</label>
                                            <input type="text" id="father_qualification" name="father_qualification"
                                                   wire:model="form.father_qualification"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Father's Degree">
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.father_qualification')"/>
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="father_passport_number"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Father's passport number</label>
                                            <input type="text" id="father_passport_number"
                                                   name="father_passport_number"
                                                   wire:model="form.father_passport_number"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter father's passport number">
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.father_passport_number')"/>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="father_nationality"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Father nationality</label>
                                            <select id="father_nationality" name="father_nationality"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    wire:model="form.father_nationality"
                                                    title="Select father nationality">
                                                <option @selected(!$form->father_nationality)>Select an option</option>
                                                @foreach($nationalities as $nationality)
                                                    <option
                                                        value="{{$nationality->id}}">{{$nationality->nationality}}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.father_nationality')"/>
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                   for="father_passport_file">Father's passport Bio-Data page scan
                                                (file)</label>
                                            <x-filepond::upload wire:model="form.father_passport_file"
                                                                :allowMultiple="false"
                                                                :instantUpload="true"
                                                                server-headers='@json(["X-CSRF-TOKEN" => csrf_token()])'
                                                                :chunkSize="2000000"
                                                                :accept="'application/pdf,image/jpg,image/bmp,image/jpeg,image/png'"/>
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.father_passport_file')"/>
                                            @if(isset($form->father_passport_file) and substr($form->father_passport_file,-4)=='.pdf')
                                                <div class="mt-3">
                                                    <label for="father_passport_file_preview"
                                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                        Father's passport file preview</label>
                                                    <div class="flex justify-center items-center">
                                                        <a target="_blank"
                                                           href="{{ $form->father_passport_file }}">
                                                            <img class="pdf-documents-icons">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                                <div class="dark:text-white font-medium mb-1">File requirements
                                                </div>
                                                <div class="dark:text-gray-400 font-normal text-sm pb-1">Please
                                                    ensure
                                                    that
                                                    these
                                                    requirements
                                                    are met:
                                                </div>
                                                <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                                    <li>
                                                        Acceptable Formats: png, jpg, jpeg, pdf, bmp
                                                    </li>
                                                    <li>
                                                        Maximum size: 2 MB
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="mother-div" class="">
                                    <div class="grid grid-cols-1 gap-4 mt-6 text-black dark:text-white">
                                        <h1 class="text-xl font-medium">Mother Information</h1>
                                    </div>
                                    <div class="grid md:grid-cols-2">
                                        <div class="mt-3 mr-2">
                                            <label for="mother_name"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Mother's name</label>
                                            <input type="text" id="mother_name" name="mother_name"
                                                   wire:model="form.mother_name"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Mother's Name">
                                            <x-input-error class="mt-2" :messages="$errors->get('form.mother_name')"/>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="mother_family"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Mother's family</label>
                                            <input type="text" id="mother_family" name="mother_family"
                                                   wire:model="form.mother_family"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Mother's Family">
                                            <x-input-error class="mt-2" :messages="$errors->get('form.mother_family')"/>
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="mother_mobile"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Mother's Phone Number</label>
                                            <input type="text" id="mother_mobile" name="mother_mobile"
                                                   wire:model="form.mother_mobile"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Mother's Mobile">
                                            <x-input-error class="mt-2" :messages="$errors->get('form.mother_mobile')"/>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="mother_email"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Mother's email (Optional)</label>
                                            <input type="email" id="mother_email" name="mother_email"
                                                   wire:model="form.mother_email"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Mother's Email">
                                            <x-input-error class="mt-2" :messages="$errors->get('form.mother_email')"/>
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="mother_occupation"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Mother's occupation</label>
                                            <input type="text" id="mother_occupation" name="mother_occupation"
                                                   wire:model="form.mother_occupation"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Mother's Occupation">
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.mother_occupation')"/>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="mother_qualification"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Mother's degree</label>
                                            <input type="text" id="mother_qualification" name="mother_qualification"
                                                   wire:model="form.mother_qualification"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Mother's Degree">
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.mother_qualification')"/>
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="mother_passport_number"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Mother's passport number</label>
                                            <input type="text" id="mother_passport_number"
                                                   name="mother_passport_number"
                                                   wire:model="form.mother_passport_number"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter Mother's Passport Number">
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.mother_passport_number')"/>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="mother_nationality"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Mother nationality</label>
                                            <select id="mother_nationality" name="mother_nationality"
                                                    wire:model="form.mother_nationality"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    title="Select previous school country">
                                                <option @selected(!$form->mother_nationality)>Select an option</option>
                                                @foreach($nationalities as $nationality)
                                                    <option
                                                        value="{{$nationality->id}}">{{$nationality->nationality}}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.mother_nationality')"/>
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                   for="mother_passport_file">Mother's passport Bio-Data page scan
                                                (file)</label>
                                            <x-filepond::upload wire:model="form.mother_passport_file"
                                                                :allowMultiple="false"
                                                                :instantUpload="true"
                                                                server-headers='@json(["X-CSRF-TOKEN" => csrf_token()])'
                                                                :chunkSize="2000000"
                                                                :accept="'application/pdf,image/jpg,image/bmp,image/jpeg,image/png'"/>
                                            <x-input-error class="mt-2"
                                                           :messages="$errors->get('form.mother_passport_file')"/>
                                            @if(isset($form->mother_passport_file) and substr($form->mother_passport_file,-4)=='.pdf')
                                                <div class="mt-3">
                                                    <label for="mother_passport_file_preview"
                                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                        Mother's passport file preview</label>
                                                    <div class="flex justify-center items-center">
                                                        <a target="_blank"
                                                           href="{{ $form->mother_passport_file }}">
                                                            <img class="pdf-documents-icons">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{--                                    Educational Informations--}}
                                <div class="grid grid-cols-1 gap-4 mt-6 text-black dark:text-white">
                                    <h1 class="text-xl font-medium">3- Educational Informations</h1>
                                </div>
                                <div class="grid md:grid-cols-2 gap-2">
                                    <div class="mt-3">
                                        <label for="previous_school_name"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Previous school name (Optional for KG1 and KG2)</label>
                                        <input type="text" id="previous_school_name" name="previous_school_name"
                                               wire:model="form.previous_school_name"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                               placeholder="Enter previous school name">
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.previous_school_name')"/>
                                    </div>
                                    <div class="mt-3">
                                        <label for="previous_school_country"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Previous school country (Optional for KG1 and KG2)</label>
                                        <select id="previous_school_country" name="previous_school_country"
                                                wire:model="form.previous_school_country"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                title="Select previous school country">
                                            <option @selected(!$form->previous_school_country)>Select an option</option>
                                            @foreach($countries as $country)
                                                <option
                                                    value="{{$country->id}}">{{$country->en_short_name}}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.previous_school_country')"/>
                                    </div>
                                    <div class="mt-3">
                                        <label for="student_skills"
                                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                                            Student skills (Optional for KG1 and KG2)</label>
                                        <textarea id="student_skills"
                                                  wire:model="form.student_skills"
                                                  placeholder="please enter student's skill and abilities..."
                                                  name="student_skills"
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 h-48 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                        <x-input-error class="mt-2" :messages="$errors->get('form.student_skills')"/>
                                    </div>
                                    <div class="mt-3">
                                        <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                               for="latest_report_card">Latest report card (Optional for KG1 and
                                            KG2)</label>
                                        <x-filepond::upload wire:model="form.latest_report_card"
                                                            :allowMultiple="false"
                                                            :instantUpload="true"
                                                            server-headers='@json(["X-CSRF-TOKEN" => csrf_token()])'
                                                            :chunkSize="2000000"
                                                            :accept="'application/pdf,image/jpg,image/bmp,image/jpeg,image/png'"/>
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.latest_report_card')"/>
                                        @if(isset($form->latest_report_card) and substr($form->latest_report_card,-4)=='.pdf')
                                            <div class="mt-3">
                                                <label for="latest_report_card_preview"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Latest report card file preview</label>
                                                <div class="flex justify-center items-center">
                                                    <a target="_blank"
                                                       href="{{ $form->latest_report_card }}">
                                                        <img class="pdf-documents-icons">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                            <div class="dark:text-white font-medium mb-1">File requirements</div>
                                            <div class="dark:text-gray-400 font-normal text-sm pb-1">Please ensure that
                                                these
                                                requirements
                                                are met:
                                            </div>
                                            <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                                <li>
                                                    Acceptable Formats: png, jpg, jpeg, pdf, bmp
                                                </li>
                                                <li>
                                                    Maximum size: 2 MB
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                {{--                                    Miscellaneous Informations--}}
                                <div class="grid grid-cols-1 gap-4 mt-6 text-black dark:text-white">
                                    <h1 class="text-xl font-medium">4- Miscellaneous Informations</h1>
                                </div>
                                <div class="grid md:grid-cols-1">
                                    <div class="mt-3 mr-2">
                                        <label for="miscellaneous"
                                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                                            Miscellaneous (Optional)</label>
                                        <textarea id="miscellaneous"
                                                  wire:model="form.miscellaneous"
                                                  placeholder="Enter miscellaneous informations"
                                                  name="miscellaneous"
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 h-48 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                        <x-input-error class="mt-2" :messages="$errors->get('form.miscellaneous')"/>
                                    </div>
                                </div>
                                {{--                                    Branch Specific--}}
                                <div class="grid grid-cols-1 gap-4 mt-6 text-black dark:text-white">
                                    <h1 class="text-xl font-medium">5- Student Informations</h1>
                                </div>
                                <div class="grid md:grid-cols-2">
                                    <div class="mt-3 mr-2">
                                        <label
                                            class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Student first name</label>
                                        <input type="text"
                                               value="{{ $student_information->generalInformations->first_name_en }}"
                                               disabled
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </div>
                                    <div class="mt-3 ml-2">
                                        <label
                                            class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Student last name</label>
                                        <input type="text"
                                               value="{{ $student_information->generalInformations->last_name_en }}"
                                               disabled
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </div>
                                    <div class="mt-3 mr-2">
                                        <label for="student_passport_number"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Passport number</label>
                                        <input type="text"
                                               wire:model="form.student_passport_number"
                                               placeholder="ID Number for Iranian students"
                                               name="student_passport_number" id="student_passport_number"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.student_passport_number')"/>
                                    </div>
                                    <div class="mt-3 ml-2">
                                        <label for="passport_expiry_date"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Passport expiry date</label>
                                        <input type="date" name="passport_expiry_date"
                                               wire:model="form.passport_expiry_date"
                                               id="passport_expiry_date"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.passport_expiry_date')"/>
                                    </div>
                                    <div class="mt-3 mr-2">
                                        <label for="student_iranian_visa"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Iranian visa/Residence number</label>
                                        <input type="text"
                                               wire:model="form.student_iranian_visa"
                                               placeholder="Please enter student's iranian visa..."
                                               name="student_iranian_visa" id="student_iranian_visa"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.student_iranian_visa')"/>
                                    </div>
                                    <div class="mt-3 ml-2">
                                        <label for="iranian_residence_expiry"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Iranian visa/Residence expiry</label>
                                        <input type="date" name="iranian_residence_expiry"
                                               wire:model="form.iranian_residence_expiry"
                                               id="iranian_residence_expiry"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.iranian_residence_expiry')"/>
                                    </div>
                                    <div class="mt-3 mr-2">
                                        <label for="student_iranian_faragir_code"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Iranian faragir code</label>
                                        <input type="text"
                                               placeholder="Please enter student's iranian faragir code ..."
                                               wire:model="form.student_iranian_faragir_code"
                                               name="student_iranian_faragir_code" id="student_iranian_faragir_code"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.student_iranian_faragir_code')"/>
                                    </div>
                                    <div class="mt-3 ml-2">
                                        <label for="student_iranian_sanad_code"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Iranian yekta code</label>
                                        <input type="text"
                                               placeholder="Please enter student's iranian yekta code ..."
                                               wire:model="form.student_iranian_sanad_code"
                                               name="student_iranian_sanad_code" id="student_iranian_sanad_code"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.student_iranian_sanad_code')"/>
                                    </div>
                                    <div class="mt-3 mr-2">
                                        <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                               for="student_passport_file">Student's passport Bio-Data page scan
                                            (file)</label>
                                        <x-filepond::upload wire:model="form.student_passport_file"
                                                            :allowMultiple="false"
                                                            :instantUpload="true"
                                                            server-headers='@json(["X-CSRF-TOKEN" => csrf_token()])'
                                                            :chunkSize="2000000"
                                                            :accept="'application/pdf,image/jpg,image/bmp,image/jpeg,image/png'"/>
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.student_passport_file')"/>
                                        @if(isset($form->student_passport_file) and substr($form->student_passport_file,-4)=='.pdf')
                                            <div class="mt-3">
                                                <label for="student_passport_file_preview"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Student's passport file preview</label>
                                                <div class="flex justify-center items-center">
                                                    <a target="_blank"
                                                       href="{{ $form->student_passport_file }}">
                                                        <img class="pdf-documents-icons">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                            <div class="dark:text-white font-medium mb-1">File requirements</div>
                                            <div class="dark:text-gray-400 font-normal text-sm pb-1">Please ensure that
                                                these
                                                requirements
                                                are met:
                                            </div>
                                            <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                                <li>
                                                    Acceptable Formats: png, jpg, jpeg, pdf, bmp
                                                </li>
                                                <li>
                                                    Maximum size: 2 MB
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="mt-3 mr-2">
                                        <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                               for="student_passport_file">Student's Passport photo
                                            (file)</label>
                                        <x-filepond::upload wire:model="form.student_passport_photo_file"
                                                            :allowMultiple="false"
                                                            :instantUpload="true"
                                                            server-headers='@json(["X-CSRF-TOKEN" => csrf_token()])'
                                                            :chunkSize="2000000"
                                                            :accept="'application/pdf,image/jpg,image/bmp,image/jpeg,image/png'"/>
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('form.student_passport_photo_file')"/>
                                        @if(isset($form->student_passport_photo_file) and substr($form->student_passport_photo_file,-4)=='.pdf')
                                            <div class="mt-3">
                                                <label for="student_passport_photo_file_preview"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Student's passport photo file preview</label>
                                                <div class="flex justify-center items-center">
                                                    <a target="_blank"
                                                       href="{{ $form->student_passport_photo_file }}">
                                                        <img class="pdf-documents-icons">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                            <div class="dark:text-white font-medium mb-1">File requirements</div>
                                            <div class="dark:text-gray-400 font-normal text-sm pb-1">Please ensure that
                                                these
                                                requirements
                                                are met:
                                            </div>
                                            <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                                <li>
                                                    Acceptable Formats: png, jpg, jpeg, pdf, bmp
                                                </li>
                                                <li>
                                                    Maximum size: 2 MB
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex">
                                <input type="hidden" name="student_id"
                                       value="{{ $student_information->student_id }}">
                                <button type="submit"
                                        wire:loading.remove
                                        class="text-white mr-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Upload
                                </button>
                                <span wire:loading class="text-sm">Uploading...</span>
                                <a href="{{ url()->previous() }}">
                                    <button type="button"
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        Back
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
