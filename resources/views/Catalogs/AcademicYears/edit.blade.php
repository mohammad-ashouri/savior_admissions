@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium">Edit Academic Year</h1>
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
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> Academic Year information</h1>
                        </div>
                        {!! Form::model($catalog, ['method' => 'PATCH','route' => ['AcademicYears.update', $catalog->id]]) !!}
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <div>
                                <label for="name"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Name</label>
                                <input type="text" id="name" value="{{$catalog->name}}" name="name"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter name" required>
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
                                <label for="finish_date"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Ends at</label>
                                <input type="date" id="finish_date" value="{{$catalog->finish_date}}" name="finish_date"
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
                                        <option @if(old('levels')==$level->id or in_array($level->id,json_decode($catalog->levels,true))) selected @endif value="{{ $level->id }}">{{ $level->name }}</option>
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
                                    @foreach($users as $user)
                                        @if(!$user->hasRole('Principal')) @continue @endif
                                        <option @if(old('Principal')==$user->id or in_array([$user->id],json_decode($catalog->employees,true)['Principal'])) selected @endif value="{{ $user->id }}">{{ $user->name }} {{ $user->family }} - {{ $user->email }} - {{ $user->mobile }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="Admissions_Officer[]"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Admissions Officer(s)</label>
                                <select id="Admissions_Officer[]" name="Admissions_Officer[]" multiple="multiple"
                                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                    @foreach($users as $user)
                                        @if(!$user->hasRole('Admissions Officer')) @continue @endif
                                        <option @if(old('Admissions_Officer')==$user->id or in_array([$user->id],json_decode($catalog->employees,true)['Admissions_Officer'])) selected @endif value="{{ $user->id }}">{{ $user->name }} {{ $user->family }} - {{ $user->email }} - {{ $user->mobile }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="Financial_Manager[]"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Financial Manager(s)</label>
                                <select id="Financial_Manager[]" name="Financial_Manager[]" multiple="multiple"
                                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                    @foreach($users as $user)
                                        @if(!$user->hasRole('Financial Manager')) @continue @endif
                                            <option @if(old('Financial_Manager')==$user->id or in_array([$user->id],json_decode($catalog->employees,true)['Financial_Manager'])) selected @endif value="{{ $user->id }}">{{ $user->name }} {{ $user->family }} - {{ $user->email }} - {{ $user->mobile }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="Interviewer[]"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">Interviewer(s)</label>
                                <select id="Interviewer[]" name="Interviewer[]" multiple="multiple"
                                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                    @foreach($users as $user)
                                        @if(!$user->hasRole('Interviewer')) @continue @endif
                                        <option @if(old('Interviewer')==$user->id or in_array([$user->id],json_decode($catalog->employees,true)['Interviewer'])) selected @endif value="{{ $user->id }}">{{ $user->name }} {{ $user->family }} - {{ $user->email }} - {{ $user->mobile }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Save
                        </button>
                        <a href="/AcademicYears">
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
