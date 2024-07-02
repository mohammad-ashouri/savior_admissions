@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Upload Student's Documents And Informations</h1>
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
                    <div class=" bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <form id="upload-student-documents" method="post"
                              action="{{ route('Documents.UploadDocumentsByParent') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="grid gap-6 mb-6">
                                <div>
                                    {{--                                    Medical Informations--}}
                                    <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                                        <h1 class="text-xl font-medium">1- Medical Informations</h1>
                                    </div>
                                    <div>
                                        <label for="blood_group"
                                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                                            Student blood group</label>
                                        <select id="blood_group" name="blood_group"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                title="Select blood group" required>
                                            <option selected disabled value="">Select an option</option>
                                            @foreach($bloodGroups as $bloodGroup)
                                                <option value="{{$bloodGroup->id}}">{{$bloodGroup->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mt-3">
                                        <label for="other_considerations"
                                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                                            Other considerations (Optional)</label>
                                        <textarea id="other_considerations"
                                                  placeholder="example: hospitalisations, surgery, allergies, accidents"
                                                  name="other_considerations"
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    </div>
                                    {{--                                    Family Informations--}}
                                    <div class="grid grid-cols-1 gap-4 mt-6 mb-4 text-black dark:text-white">
                                        <h1 class="text-xl font-medium">2- Family Informations</h1>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <div class="mr-2">
                                            <label for="relationship"
                                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                                Your relationship with the child</label>
                                            <select id="relationship" name="relationship"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    title="Select blood group" required>
                                                <option selected disabled value="">Select an option</option>
                                                @foreach($guardianStudentRelationships as $guardianStudentRelationship)
                                                    <option
                                                        value="{{$guardianStudentRelationship->id}}">{{$guardianStudentRelationship->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="ml-2" id="martial-status-div">
                                            <label for="marital_status"
                                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                                Marital status</label>
                                            <select id="marital_status" name="marital_status"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    title="Select blood group" required>
                                                <option selected disabled value="">Select an option</option>
                                                <option value="Married">Married</option>
                                                <option value="Divorced">Divorced</option>
                                            </select>
                                        </div>
                                        <div class="mt-3 mr-2" id="relation-name-div" hidden="hidden">
                                            <label for="relation_name"
                                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                                Relation name</label>
                                            <input type="text" id="relation_name" name="relation_name" value=""
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="example: Grandfather">
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
                                                <input type="text" id="father_name" name="father_name" value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Name">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="father_family"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's family</label>
                                                <input type="text" id="father_family" name="father_family" value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Family">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="father_mobile"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's mobile</label>
                                                <input type="text" id="father_mobile" name="father_mobile" value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Mobile">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="father_email"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's email (Optional)</label>
                                                <input type="email" id="father_email" name="father_email" value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       placeholder="Enter Father's Email">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="father_occupation"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's occupation</label>
                                                <input type="text" id="father_occupation" name="father_occupation"
                                                       value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Occupation">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="father_qualification"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's degree</label>
                                                <input type="text" id="father_qualification" name="father_qualification"
                                                       value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Degree">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="father_passport_number"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's passport number</label>
                                                <input type="text" id="father_passport_number"
                                                       name="father_passport_number"
                                                       value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter father's passport number">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="father_nationality"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father nationality</label>
                                                <select id="father_nationality" name="father_nationality"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        title="Select father nationality" required>
                                                    <option selected disabled value="">Select an option</option>
                                                    @foreach($nationalities as $nationality)
                                                        <option
                                                            value="{{$nationality->id}}">{{$nationality->nationality}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                       for="father_passport_file">Father's passport Bio-Data page scan
                                                    (file)</label>
                                                <input required
                                                       class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                       id="father_passport_file" name="father_passport_file" type="file"
                                                       accept=".pdf,.jpg,.bpm,.jpeg,.png">
                                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                                    <div class="dark:text-white font-medium mb-1">File requirements
                                                    </div>
                                                    <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that
                                                        these
                                                        requirements
                                                        are met:
                                                    </div>
                                                    <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                                        <li>
                                                            The files must be in this format: png, jpg, jpeg, pdf, bmp
                                                        </li>
                                                        <li>
                                                            Maximum size: 2 MB
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="father_passport_file_preview"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father passport file preview</label>
                                                <img id="father_passport_file_preview">
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
                                                <input type="text" id="mother_name" name="mother_name" value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Mother's Name">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="mother_family"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Mother's family</label>
                                                <input type="text" id="mother_family" name="mother_family" value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Mother's Family">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="mother_mobile"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Mother's mobile</label>
                                                <input type="text" id="mother_mobile" name="mother_mobile" value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Mother's Mobile">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="mother_email"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Mother's email (Optional)</label>
                                                <input type="email" id="mother_email" name="mother_email" value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       placeholder="Enter Mother's Email">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="mother_occupation"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Mother's occupation</label>
                                                <input type="text" id="mother_occupation" name="mother_occupation"
                                                       value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Mother's Occupation">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="mother_qualification"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Mother's degree</label>
                                                <input type="text" id="mother_qualification" name="mother_qualification"
                                                       value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Mother's Degree">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="mother_passport_number"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Mother's passport number</label>
                                                <input type="text" id="mother_passport_number"
                                                       name="mother_passport_number"
                                                       value=""
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Mother's Passport Number">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="mother_nationality"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Mother nationality</label>
                                                <select id="mother_nationality" name="mother_nationality"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        title="Select previous school country" required>
                                                    <option selected disabled value="">Select an option</option>
                                                    @foreach($nationalities as $nationality)
                                                        <option
                                                            value="{{$nationality->id}}">{{$nationality->nationality}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                       for="mother_passport_file">Mother's passport Bio-Data page scan
                                                    (file)</label>
                                                <input required
                                                       class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                       id="mother_passport_file" name="mother_passport_file" type="file"
                                                       accept=".pdf,.jpg,.bpm,.jpeg,.png">
                                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                                    <div class="dark:text-white font-medium mb-1">File requirements
                                                    </div>
                                                    <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that
                                                        these
                                                        requirements
                                                        are met:
                                                    </div>
                                                    <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                                        <li>
                                                            The files must be in this format: png, jpg, jpeg, pdf, bmp
                                                        </li>
                                                        <li>
                                                            Maximum size: 2 MB
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="mother_passport_file_preview"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Mother passport file preview</label>
                                                <img id="mother_passport_file_preview">
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                    Educational Informations--}}
                                    <div class="grid grid-cols-1 gap-4 mt-6 text-black dark:text-white">
                                        <h1 class="text-xl font-medium">3- Educational Informations</h1>
                                    </div>
                                    <div class="grid md:grid-cols-2">
                                        <div class="mt-3 mr-2">
                                            <label for="previous_school_name"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Previous school name (Optional for KG1 and KG2)</label>
                                            <input type="text" id="previous_school_name" name="previous_school_name"
                                                   value=""
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="Enter previous school name">
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="previous_school_country"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Previous school country (Optional for KG1 and KG2)</label>
                                            <select id="previous_school_country" name="previous_school_country"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    title="Select previous school country">
                                                <option selected disabled value="">Select an option</option>
                                                @foreach($countries as $country)
                                                    <option
                                                        value="{{$country->id}}">{{$country->en_short_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="student_skills"
                                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                                Student skills (Optional for KG1 and KG2)</label>
                                            <textarea id="student_skills"
                                                      placeholder="please enter student's skill and abilities..."
                                                      name="student_skills"
                                                      class="bg-gray-50 border border-gray-300 text-gray-900 h-48 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                   for="latest_report_card">Latest report card (Optional for KG1 and
                                                KG2)</label>
                                            <input
                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                id="latest_report_card" name="latest_report_card" type="file"
                                                accept=".pdf,.jpg,.bpm,.jpeg,.png">
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                                <div class="dark:text-white font-medium mb-1">File requirements</div>
                                                <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that
                                                    these
                                                    requirements
                                                    are met:
                                                </div>
                                                <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                                    <li>
                                                        The files must be in this format: png, jpg, jpeg, pdf, bmp
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
                                                      placeholder="Enter miscellaneous informations"
                                                      name="miscellaneous"
                                                      class="bg-gray-50 border border-gray-300 text-gray-900 h-48 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
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
                                                   value="{{ $studentInformation->generalInformations->first_name_en }}"
                                                   disabled
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label
                                                class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Student last name</label>
                                            <input type="text"
                                                   value="{{ $studentInformation->generalInformations->last_name_en }}"
                                                   disabled
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="student_passport_number"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Passport number</label>
                                            <input required type="text" value=""
                                                   placeholder="ID Number for Iranian students"
                                                   name="student_passport_number" id="student_passport_number"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="passport_expiry_date"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Passport expiry date</label>
                                            <input required type="date" value="" name="passport_expiry_date"
                                                   id="passport_expiry_date"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="student_iranian_visa"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Iranian visa/Residence number</label>
                                            <input required type="text" value=""
                                                   placeholder="Please enter student's iranian visa..."
                                                   name="student_iranian_visa" id="student_passport_number"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="iranian_residence_expiry"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Iranian visa/Residence expiry</label>
                                            <input required type="date" value="" name="iranian_residence_expiry"
                                                   id="iranian_residence_expiry"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="student_iranian_faragir_code"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Iranian faragir code</label>
                                            <input required type="text" value=""
                                                   placeholder="Please enter student's iranian faragir code ..."
                                                   name="student_iranian_faragir_code" id="student_iranian_faragir_code"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="student_iranian_sanad_code"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Iranian yekta code</label>
                                            <input required type="text" value=""
                                                   placeholder="Please enter student's iranian yekta code ..."
                                                   name="student_iranian_sanad_code" id="student_iranian_sanad_code"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                   for="student_passport_file">Student's passport Bio-Data page scan
                                                (file)</label>
                                            <input required
                                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                   id="student_passport_file" name="student_passport_file" type="file"
                                                   accept=".pdf,.jpg,.bpm,.jpeg,.png">
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                                <div class="dark:text-white font-medium mb-1">File requirements</div>
                                                <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that
                                                    these
                                                    requirements
                                                    are met:
                                                </div>
                                                <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                                    <li>
                                                        The files must be in this format: png, jpg, jpeg, pdf, bmp
                                                    </li>
                                                    <li>
                                                        Maximum size: 2 MB
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="student_passport_file_preview"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Student passport file preview</label>
                                            <img id="student_passport_file_preview">
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                   for="residence_document_file">Residence document scan (file)</label>
                                            <input required
                                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                   id="residence_document_file" name="residence_document_file"
                                                   type="file"
                                                   accept=".pdf,.jpg,.bpm,.jpeg,.png">
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                                <div class="dark:text-white font-medium mb-1">File requirements</div>
                                                <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that
                                                    these
                                                    requirements
                                                    are met:
                                                </div>
                                                <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                                    <li>
                                                        The files must be in this format: png, jpg, jpeg, pdf, bmp
                                                    </li>
                                                    <li>
                                                        Maximum size: 2 MB
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="residence_document_file_preview"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Residence document file file preview</label>
                                            <img id="residence_document_file_preview">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex">
                                    <input type="hidden" name="student_id"
                                           value="{{ $studentInformation->student_id }}">
                                    <button type="submit"
                                            class="text-white mr-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Upload
                                    </button>
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
@endsection
