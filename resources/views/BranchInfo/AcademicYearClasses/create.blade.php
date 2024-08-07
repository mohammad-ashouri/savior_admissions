@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> New Academic Year Class</h1>
            </div>
            @include('GeneralPages.errors.session.error')

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <form id="new-academic-year" method="post" action="{{route('AcademicYearClasses.store')}}">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="name"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Name</label>
                                    <input type="text" id="name" value="{{ old('name') }}" name="name"
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
                                            <option @if(old('academic_year')==$academicYear->id) selected
                                                    @endif value="{{$academicYear->id}}">{{$academicYear->name}}</option>
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
                                    </select>
                                </div>
                                <div>
                                    <label for="education_type"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">Education
                                        Type</label>
                                    <select id="education_type" name="education_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option selected disabled value="">Select education type</option>
                                        @foreach($educationTypes as $educationType)
                                            <option @if(old('education_type')==$educationType->id) selected
                                                    @endif value="{{ $educationType->id }}">{{ $educationType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="capacity"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">Capacity</label>
                                    <input type="number" id="capacity" value="{{ old('capacity') }}" name="capacity"
                                           max="60" min="1"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter capacity" required>
                                </div>
                                <div>
                                    <label for="education_gender"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">Education
                                        Gender</label>
                                    <select id="education_gender" name="education_gender"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                        <option selected disabled value="">Select education gender</option>
                                        @foreach($educationGenders as $educationGender)
                                            <option @if(old('education_gender')==$educationGender->id) selected
                                                    @endif value="{{ $educationGender->id }}">{{ $educationGender->name }}</option>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
