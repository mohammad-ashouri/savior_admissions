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
@if( session()->has('success') )
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
                <p class="font-bold">{{ session()->get('success') }}</p>
            </div>
        </div>
    </div>
@endif
<div class="grid grid-cols-2 gap-4 mb-4">
    <div class="lg:col-span-2 col-span-3 ">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
            <div class=" mb-6 md:grid-cols-2">
                <div class="relative overflow-x-auto">
                    <div class="grid grid-cols-1 gap-4 mb-4">
                        <h1 class="text-xl font-semibold text-black dark:text-white ">Students Status </h1>
                    </div>
                </div>
                <div class="flex">
                    @if($studentNumberStatusByAcademicYear)
                        <div class="mr-4">
                            @if(!empty($data))
                                {!! $studentNumberStatusByAcademicYear->container() !!}
                                <script src="{{ $studentNumberStatusByAcademicYear->cdn() }}"></script>
                                {{ $studentNumberStatusByAcademicYear->script() }}
                            @else
                                There is no data chart to show about: Student numbers by school
                            @endif
                        </div>
                    @endif
                    <div class="flex">
                        @if($allRegisteredStudentsInLastAcademicYear)
                                {!! $allRegisteredStudentsInLastAcademicYear->container() !!}
                                <script src="{{ $allRegisteredStudentsInLastAcademicYear->cdn() }}"></script>
                                {{ $allRegisteredStudentsInLastAcademicYear->script() }}
                        @else
                            There is no data chart to show about: Number of all registered students by academic year
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
