@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium">Edit Academic Year</h1>
            </div>
            @include('GeneralPages.errors.session.error')

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> Academic Year information</h1>
                        </div>
                        {!! Form::model($catalog, ['method' => 'PATCH','enctype'=>"multipart/form-data",'id'=>'edit-academic-year','route' => ['AcademicYears.update', $catalog->id]]) !!}
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <div>
                                <label for="name"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Name (English)</label>
                                <input type="text" id="name" value="{{$catalog->name}}" name="name"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter name" required>
                            </div>
                            <div>
                                <label for="persian_name"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Name (Persian)</label>
                                <input type="text" id="persian_name" value="{{$catalog->persian_name}}" name="persian_name"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="نام فارسی را وارد کنید" required>
                            </div>
                            <div>
                                <label for="school"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    School</label>
                                <select id="school" name="school"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        title="Select school" required>
                                    <option selected disabled value="">Select school</option>
                                    @foreach($schools as $school)
                                        <option @if($catalog->school_id===$school->id) selected
                                                @endif value="{{$school->id}}">{{$school->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="start_date"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Starts at</label>
                                <input type="date" id="start_date" value="{{$catalog->start_date}}" name="start_date"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       required>
                            </div>
                            <div>
                                <label for="end_date"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Ends at</label>
                                <input type="date" id="end_date" value="{{$catalog->end_date}}" name="end_date"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       required>
                            </div>
                            <div>
                                <label for="levels[]"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Levels</label>
                                <select id="levels[]" name="levels[]" multiple="multiple"
                                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                    @foreach($levels as $level)
                                        <option
                                            @if(is_array(old('levels')) and in_array($level->id, old('levels')) or in_array($level->id,json_decode($catalog->levels,true))) selected
                                            @endif value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status"
                                       class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Status</label>
                                <select required
                                        id="status" name="status"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                >
                                    <option value="" selected disabled>Choose an option...</option>
                                    <option @if($catalog->status===1) selected
                                            @endif value="1">Active
                                    </option>
                                    <option @if($catalog->status===0) selected
                                            @endif value="0">Deactive
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                       for="financial_file">Tuition Fee Financial Charter of Students (file)</label>
                                <input required
                                       class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                       id="financial_file" name="financial_file" type="file"
                                       accept=".pdf">
                            </div>
                            <div>
                                <label class="block mb-2  font-bold text-gray-900 dark:text-white"
                                       for="financial_file">Preview uploaded file</label>
                                <a href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $catalog->financial_roles) }}">Click to download</a>
                            </div>
                        </div>
                        <div class="grid gap-6 mb-6 md:grid-cols-1">
                            <div class="col-span-1 gap-4  text-black dark:text-white">
                                <h1 class="text-2xl font-medium">Employees Information</h1>
                            </div>
                            <div>
                                <label for="Principal[]"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Principal(s)</label>
                                <select id="Principal[]" name="Principal[]" multiple="multiple"
                                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                    @foreach($principals as $principal)
                                        <option
                                            @if(old('Principal')==$principal->id or (old('Principal') !== null and in_array($principal->id,old('Principal'))) or in_array($principal->id,json_decode($catalog->employees,true)['Principal'][0])) selected
                                            @endif value="{{ $principal->id }}">{{ $principal->generalInformationInfo->first_name_en }} {{ $principal->generalInformationInfo->last_name_en }}
                                            - {{ $principal->email }} - {{ $principal->mobile }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="Admissions_Officer[]"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Admissions
                                    Officer(s)</label>
                                <select id="Admissions_Officer[]" name="Admissions_Officer[]" multiple="multiple"
                                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                    @foreach($admissionOfficers as $admissionOfficer)
                                        <option
                                            @if(old('Admissions_Officer')==$admissionOfficer->id or (old('Admissions_Officer') !== null and in_array($admissionOfficer->id,old('Admissions_Officer'))) or in_array($admissionOfficer->id,json_decode($catalog->employees,true)['Admissions_Officer'][0])) selected
                                            @endif value="{{ $admissionOfficer->id }}">{{ $admissionOfficer->generalInformationInfo->first_name_en }} {{ $admissionOfficer->generalInformationInfo->last_name_en }}
                                            - {{ $admissionOfficer->email }} - {{ $admissionOfficer->mobile }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="Financial_Manager[]"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Financial
                                    Manager(s)</label>
                                <select id="Financial_Manager[]" name="Financial_Manager[]" multiple="multiple"
                                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                    @foreach($financialManagers as $financialManager)
                                        <option
                                            @if(old('Financial_Manager')==$financialManager->id or (old('Financial_Manager') !== null and in_array($financialManager->id,old('Financial_Manager'))) or in_array($financialManager->id,json_decode($catalog->employees,true)['Financial_Manager'][0])) selected
                                            @endif value="{{ $financialManager->id }}">{{ $financialManager->generalInformationInfo->first_name_en }} {{ $financialManager->generalInformationInfo->last_name_en }}
                                            - {{ $financialManager->email }} - {{ $financialManager->mobile }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="Interviewer[]"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Interviewer(s)</label>
                                <select id="Interviewer[]" name="Interviewer[]" multiple="multiple"
                                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                    @foreach($interviewers as $interviewer)
                                        <option
                                            @if(old('Interviewer')==$interviewer->id or (old('Interviewer') !== null and in_array($interviewer->id,old('Interviewer'))) or in_array($interviewer->id,json_decode($catalog->employees,true)['Interviewer'][0])) selected
                                            @endif value="{{ $interviewer->id }}">{{ $interviewer->generalInformationInfo->first_name_en }} {{ $interviewer->generalInformationInfo->last_name_en }}
                                            - {{ $interviewer->email }} - {{ $interviewer->mobile }}</option>
                                    @endforeach
                                </select>
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
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
