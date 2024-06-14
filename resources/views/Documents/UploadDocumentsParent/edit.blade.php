@extends('Layouts.panel')
@php
    $informations=json_decode($studentAppliance->evidences->informations,true);
    $files=json_decode($studentAppliance->evidences->files,true);
@endphp
@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Upload Student's Documents And Informations</h1>
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
                        <p class="font-bold">Seconder description: {{ $studentAppliance->seconder_description }}</p>
                    </div>
                </div>
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
                        <form class="confirm-evidences" method="post" enctype="multipart/form-data"
                              action="{{ route('Document.EditUploadedEvidences.update') }}">
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
                                                @php
                                                    $selected = $informations['blood_group'] == $bloodGroup->id ? 'selected' : '';
                                                @endphp
                                                <option
                                                    value="{{ $bloodGroup->id }}" {{ $selected }}>{{ $bloodGroup->name }}</option>
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
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$informations['other_considerations']}}</textarea>
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
                                                        value="{{ $guardianStudentRelationship->id }}" {{ $informations['relationship'] == $guardianStudentRelationship->id ? 'selected' : '' }}>
                                                        {{ $guardianStudentRelationship->name }}
                                                    </option>
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
                                                @foreach(['Married', 'Divorced'] as $status)
                                                    <option
                                                        value="{{ $status }}" {{ $informations['marital_status'] == $status ? 'selected' : '' }}>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mt-3 mr-2" id="relation-name-div"
                                             @if($informations['relationship']!=3) hidden="hidden" @endif >
                                            <label for="relation_name"
                                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                                Relation name</label>
                                            <input type="text" id="relation_name" name="relation_name"
                                                   value="{{$informations['relation_name']}}"
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
                                                <input type="text" id="father_name" name="father_name"
                                                       value="{{$informations['father_name']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Name">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="father_family"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's family</label>
                                                <input type="text" id="father_family" name="father_family"
                                                       value="{{$informations['father_family']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Family">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="father_mobile"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's mobile</label>
                                                <input type="text" id="father_mobile" name="father_mobile"
                                                       value="{{$informations['father_mobile']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Mobile">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="father_email"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's email (Optional)</label>
                                                <input type="email" id="father_email" name="father_email"
                                                       value="{{$informations['father_email']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       placeholder="Enter Father's Email">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="father_occupation"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's occupation</label>
                                                <input type="text" id="father_occupation" name="father_occupation"
                                                       value="{{$informations['father_occupation']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Occupation">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="father_qualification"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's degree</label>
                                                <input type="text" id="father_qualification" name="father_qualification"
                                                       value="{{$informations['father_qualification']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter Father's Degree">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="father_passport_number"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Father's passport number</label>
                                                <input type="text" id="father_passport_number"
                                                       name="father_passport_number"
                                                       value="{{$informations['father_passport_number']}}"
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
                                                            {{ $informations['father_nationality'] == $nationality->id ? 'selected' : '' }}
                                                            value="{{$nationality->id}}">{{$nationality->nationality}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                       for="father_passport_file">Father's passport Bio-Data page scan
                                                    (file)</label>
                                                <input
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
                                                @if(substr($files['father_passport_file'],-4)=='.pdf')
                                                    <div class="flex justify-center items-center">
                                                        <a target="_blank"
                                                           href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['father_passport_file']) }}">
                                                            <img class="pdf-documents-icons">
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                                    >
                                                        <button data-modal-target="openImage"
                                                                data-modal-toggle="openImage"
                                                                data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['father_passport_file']) }}"
                                                                class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                                type="button">
                                                            <img id="father_passport_file_preview"
                                                                 class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                                 style="width: 400px; height: 200px"
                                                                 src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['father_passport_file']) }}"
                                                                 alt="Document Not Found!">
                                                        </button>
                                                    </div>
                                                @endif
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
                                                    mother's name</label>
                                                <input type="text" id="mother_name" name="mother_name"
                                                       value="{{$informations['mother_name']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter mother's Name">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="mother_family"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    mother's family</label>
                                                <input type="text" id="mother_family" name="mother_family"
                                                       value="{{$informations['mother_family']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter mother's Family">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="mother_mobile"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    mother's mobile</label>
                                                <input type="text" id="mother_mobile" name="mother_mobile"
                                                       value="{{$informations['mother_mobile']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter mother's Mobile">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="mother_email"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    mother's email (Optional)</label>
                                                <input type="email" id="mother_email" name="mother_email"
                                                       value="{{$informations['mother_email']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       placeholder="Enter mother's Email">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="mother_occupation"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    mother's occupation</label>
                                                <input type="text" id="mother_occupation" name="mother_occupation"
                                                       value="{{$informations['mother_occupation']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter mother's Occupation">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="mother_qualification"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    mother's degree</label>
                                                <input type="text" id="mother_qualification" name="mother_qualification"
                                                       value="{{$informations['mother_qualification']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter mother's Degree">
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label for="mother_passport_number"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    mother's passport number</label>
                                                <input type="text" id="mother_passport_number"
                                                       name="mother_passport_number"
                                                       value="{{$informations['mother_passport_number']}}"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       required placeholder="Enter mother's passport number">
                                            </div>
                                            <div class="mt-3 ml-2">
                                                <label for="mother_nationality"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    mother nationality</label>
                                                <select id="mother_nationality" name="mother_nationality"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        title="Select mother nationality" required>
                                                    <option selected disabled value="">Select an option</option>
                                                    @foreach($nationalities as $nationality)
                                                        <option
                                                            {{ $informations['mother_nationality'] == $nationality->id ? 'selected' : '' }}
                                                            value="{{$nationality->id}}">{{$nationality->nationality}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-3 mr-2">
                                                <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                       for="mother_passport_file">Mother's passport Bio-Data page scan
                                                    (file)</label>
                                                <input
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
                                                @if(substr($files['mother_passport_file'],-4)=='.pdf')
                                                    <div class="flex justify-center items-center">
                                                        <a target="_blank"
                                                           href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['mother_passport_file']) }}">
                                                            <img class="pdf-documents-icons">
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                                    >
                                                        <button data-modal-target="openImage"
                                                                data-modal-toggle="openImage"
                                                                data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['mother_passport_file']) }}"
                                                                class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                                type="button">
                                                            <img id="mother_passport_file_preview"
                                                                 class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                                 style="width: 400px; height: 200px"
                                                                 src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['mother_passport_file']) }}"
                                                                 alt="Document Not Found!">
                                                        </button>
                                                    </div>
                                                @endif
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
                                                   value="{{$informations['previous_school_name']}}"
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
                                                        {{ @$informations['previous_school_country'] == $country->id ? 'selected' : '' }}
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
                                                      class="bg-gray-50 border border-gray-300 text-gray-900 h-48 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$informations['student_skills']}}</textarea>
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
                                        <div class="mt-3">
                                            <label for="latest_report_card_preview"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Latest report card preview</label>
                                            @if(substr($files['latest_report_card'],-4)=='.pdf')
                                                <div class="flex justify-center items-center">
                                                    <a target="_blank"
                                                       href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['latest_report_card']) }}">
                                                        <img class="pdf-documents-icons">
                                                    </a>
                                                </div>
                                            @else
                                                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                                >
                                                    <button data-modal-target="openImage" data-modal-toggle="openImage"
                                                            data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['latest_report_card']) }}"
                                                            class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                            type="button">
                                                        <img
                                                            class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                            style="width: 400px; height: 200px"
                                                            id="latest_report_card_preview"
                                                            src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['latest_report_card']) }}"
                                                            alt="Document Not Found!">
                                                    </button>
                                                </div>
                                            @endif
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
                                                      class="bg-gray-50 border border-gray-300 text-gray-900 h-48 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$informations['miscellaneous']}}</textarea>
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
                                            <input required type="text"
                                                   value="{{$informations['student_passport_number']}}"
                                                   placeholder="ID Number for Iranian students"
                                                   name="student_passport_number" id="student_passport_number"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="passport_expiry_date"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Passport expiry date</label>
                                            <input required type="date"
                                                   value="{{$informations['passport_expiry_date']}}"
                                                   name="passport_expiry_date"
                                                   id="passport_expiry_date"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="student_iranian_visa"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Iranian visa/Residence number</label>
                                            <input required type="text"
                                                   value="{{$informations['student_iranian_visa']}}"
                                                   placeholder="Please enter student's iranian visa..."
                                                   name="student_iranian_visa" id="student_passport_number"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="iranian_residence_expiry"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Iranian visa/Residence expiry</label>
                                            <input required type="date"
                                                   value="{{$informations['iranian_residence_expiry']}}"
                                                   name="iranian_residence_expiry"
                                                   id="iranian_residence_expiry"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label for="student_iranian_faragir_code"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Iranian faragir code</label>
                                            <input required type="text"
                                                   value="{{$informations['student_iranian_faragir_code']}}"
                                                   placeholder="Please enter student's iranian faragir code ..."
                                                   name="student_iranian_faragir_code" id="student_iranian_faragir_code"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 ml-2">
                                            <label for="student_iranian_sanad_code"
                                                   class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                Iranian yekta code</label>
                                            <input required type="text"
                                                   value="{{$informations['student_iranian_sanad_code']}}"
                                                   placeholder="Please enter student's iranian yekta code ..."
                                                   name="student_iranian_sanad_code" id="student_iranian_sanad_code"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                   for="student_passport_file">Student's passport Bio-Data page scan
                                                (file)</label>
                                            <input
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
                                            @if(substr($files['student_passport_file'],-4)=='.pdf')
                                                <div class="flex justify-center items-center">
                                                    <a target="_blank"
                                                       href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['student_passport_file']) }}">
                                                        <img class="pdf-documents-icons">
                                                    </a>
                                                </div>
                                            @else
                                                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                                >
                                                    <button data-modal-target="openImage" data-modal-toggle="openImage"
                                                            data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['student_passport_file']) }}"
                                                            class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                            type="button">
                                                        <img
                                                            class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                            style="width: 400px; height: 200px"
                                                            id="student_passport_file_preview"
                                                            src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['student_passport_file']) }}"
                                                            alt="Document Not Found!">
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-3 mr-2">
                                            <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                                   for="residence_document_file">Student's passport Bio-Data page scan
                                                (file)</label>
                                            <input
                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                id="residence_document_file" name="residence_document_file" type="file"
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

                                        @if(isset($files['residence_document_file']))
                                            <div class="mt-3 ml-2">
                                                <label for="residence_document_file_preview"
                                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                                    Residence document file preview</label>
                                                @if(substr($files['residence_document_file'],-4)=='.pdf')
                                                    <div class="flex justify-center items-center">
                                                        <a target="_blank"
                                                           href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['residence_document_file']) }}">
                                                            <img class="pdf-documents-icons">
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                                    >
                                                        <button data-modal-target="openImage"
                                                                data-modal-toggle="openImage"
                                                                data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['residence_document_file']) }}"
                                                                class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                                type="button">
                                                            <img
                                                                class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                                style="width: 400px; height: 200px"
                                                                id="residence_document_file_preview"
                                                                src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $files['residence_document_file']) }}"
                                                                alt="Document Not Found!">
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- openImage Modal -->
                                <div id="openImage" tabindex="-1" aria-hidden="true"
                                     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative  rounded-lg shadow ">
                                            <!-- Modal header -->
                                            <div class="flex items-start justify-between ">

                                                <button type="button"
                                                        class="text-gray-400 bg-transparent  rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center  dark:hover:text-white"
                                                        data-modal-hide="openImage">
                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="text-center">
                                                <img class="h-auto max-w-full rounded-lg " id="image-for-show" src=""
                                                     alt="image">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="flex w-full">
                                    <div class="flex">
                                        @csrf
                                        <input type="hidden" name="student_id"
                                               value="{{ $studentInformation->student_id }}">
                                        <input type="hidden" name="evidence"
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
