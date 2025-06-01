<div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
        <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
            <h1 class="text-2xl font-medium"> Student's Details </h1>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="lg:col-span-2 col-span-2 ">
                <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                    <div class="grid gap-6 mb-6 md:grid-cols-3">
                        <div>
                            @if(!empty($studentInformations->userInfo->personal_image))
                                <img class="h-64 w-64 rounded-full"
                                     src="{{ env('APP_URL').'/'.str_replace('public','storage',$studentInformations->userInfo->personal_image) }}"
                                     alt="User Personal Picture">
                            @else
                                <form method="post" enctype="multipart/form-data" id="student_personal_picture_form"
                                      action="{{ route('UploadPersonalPicture') }}">
                                    @csrf
                                    <label class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-white"
                                           for="document_file">Upload student's personal picture </label>
                                    <div class="flex">
                                        <input
                                            class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                            id="personal_picture" name="personal_picture" type="file"
                                            accept=".png,.jpg,.jpeg,.bmp">
                                        <input type="hidden" name="id"
                                               value="{{$studentInformations->student_id}}">
                                        <button type="submit"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 ml-3 h-10 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            Upload
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>

                    </div>
                    <div class="grid gap-6 mb-6 md:grid-cols-3">
                        <div>
                            <p class="font-bold">ID: </p> {{ $studentInformations->student_id }}
                        </div>
                        <div>
                            <p class="font-bold">First Name
                                (English): </p> {{ $studentInformations->generalInformations->first_name_en }}
                        </div>
                        <div>
                            <p class="font-bold">Last Name
                                (English): </p> {{ $studentInformations->generalInformations->last_name_en }}
                        </div>
                        <div>
                            <p class="font-bold">First Name
                                (Persian): </p> {{ $studentInformations->generalInformations->first_name_fa }}
                        </div>
                        <div>
                            <p class="font-bold">Last Name
                                (Persian): </p> {{ $studentInformations->generalInformations->last_name_fa }}
                        </div>
                        <div>
                            <p class="font-bold">
                                Birthdate: </p> {{ $studentInformations->generalInformations->birthdate }}
                        </div>
                        <div>
                            <p class="font-bold">
                                Gender: </p> {{ $studentInformations->generalInformations->gender }}
                        </div>
                    </div>
                    <a href="{{ route('Students.index') }}">
                        <button type="button"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            Back
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
