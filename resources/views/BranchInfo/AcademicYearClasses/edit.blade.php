@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Edit Academic Year Class</h1>
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
                            @error('name')
                            <p class="font-bold">{{ $message }}</p>
                            @enderror
                            @error('school')
                            <p class="font-bold">{{ $message }}</p>
                            @enderror
                            @error('start_date')
                            <p class="font-bold">{{ $message }}</p>
                            @enderror
                            @error('finish_date')
                            <p class="font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        {!! Form::model($academicYearClass, ['method' => 'PATCH','route' => ['AcademicYearClasses.update', $academicYearClass->id]]) !!}
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="name"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Name</label>
                                    <input type="text" id="name" value="{{ old('name') }}{{ $academicYearClass->name }}" name="name"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter name" required>
                                </div>
                                <div>
                                    <label for="academic_year"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Academic Year</label>
                                    <select id="academic_year" name="academic_year"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select academic year" required>
                                        <option selected disabled value="">Select academic year</option>
                                        @foreach($academicYears as $academicYear)
                                            <option @if(old('academic_year')==$academicYear->id or $academicYearClass->academic_year==$academicYear->id) selected @endif value="{{$academicYear->id}}">{{$academicYear->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="level"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">Level</label>
                                    <select id="level" name="level"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option selected disabled value="">Select level</option>
                                        @foreach($levels as $id => $level)
                                            <option @if(old('level')==$id or $academicYearClass->level == $id) selected @endif value="{{ $id }}">{{ $level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="education_type"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">Education Type</label>
                                    <select id="education_type" name="education_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option selected disabled value="">Select education type</option>
                                        @foreach($educationTypes as $educationType)
                                            <option @if(old('education_type')==$educationType->id or $academicYearClass->education_type == $educationType->id) selected @endif value="{{ $educationType->id }}">{{ $educationType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="capacity"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">Capacity</label>
                                    <input type="number" id="capacity" value="{{ old('capacity') }}{{ $academicYearClass->capacity }}" name="capacity" max="60" min="1"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter capacity" required>
                                </div>
                                <div>
                                    <label for="education_gender"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">Education Gender</label>
                                    <select id="education_gender" name="education_gender"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option selected disabled value="">Select education gender</option>
                                        @foreach($educationGenders as $educationGender)
                                            <option @if(old('education_gender')==$educationGender->id or $academicYearClass->education_gender == $educationGender->id) selected @endif value="{{ $educationGender->id }}">{{ $educationGender->name }}</option>
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
                                        <option @if($academicYearClass->status===1) selected
                                                @endif value="1">Active
                                        </option>
                                        <option @if($academicYearClass->status===0) selected
                                                @endif value="0">Deactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save
                            </button>
                            <a href="/AcademicYearClasses">
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
