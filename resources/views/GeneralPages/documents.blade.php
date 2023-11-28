@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-8 text-black dark:text-white">
                Dear {name} {family}. Welcome to savior school panel
            </div>
            <div class="grid grid-cols-1 gap-4 text-black dark:text-white">
                <div>
                    <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                            class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="button">
                        New Document
                    </button>
                </div>
            </div>


            <div class="flex items-center justify-center pb-4 md:pb-8 flex-wrap mt-10 md:mt-8">
                <button type="button"
                        class="text-blue-700 hover:text-white border border-blue-600 bg-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-full text-base font-medium px-5 py-2.5 text-center mr-3 mb-3 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:bg-gray-900 dark:focus:ring-blue-800">All
                    categories</button>
                <button type="button"
                        class="text-gray-900 border border-white hover:border-gray-200 dark:border-gray-900 dark:bg-gray-900 dark:hover:border-gray-700 bg-white focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-full text-base font-medium px-5 py-2.5 text-center mr-3 mb-3 dark:text-white dark:focus:ring-gray-800">Shoes</button>
                <button type="button"
                        class="text-gray-900 border border-white hover:border-gray-200 dark:border-gray-900 dark:bg-gray-900 dark:hover:border-gray-700 bg-white focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-full text-base font-medium px-5 py-2.5 text-center mr-3 mb-3 dark:text-white dark:focus:ring-gray-800">Bags</button>
                <button type="button"
                        class="text-gray-900 border border-white hover:border-gray-200 dark:border-gray-900 dark:bg-gray-900 dark:hover:border-gray-700 bg-white focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-full text-base font-medium px-5 py-2.5 text-center mr-3 mb-3 dark:text-white dark:focus:ring-gray-800">Electronics</button>
                <button type="button"
                        class="text-gray-900 border border-white hover:border-gray-200 dark:border-gray-900 dark:bg-gray-900 dark:hover:border-gray-700 bg-white focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-full text-base font-medium px-5 py-2.5 text-center mr-3 mb-3 dark:text-white dark:focus:ring-gray-800">Gaming</button>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 ">
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <button data-modal-target="openImage" data-modal-toggle="openImage"
                            class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center"
                            type="button">
                        <img class="h-auto max-w-full rounded-lg "
                             src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image.jpg" alt="">
                        <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05
                        </p>
                    </button>
                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-1.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-2.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-3.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-4.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-5.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-6.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-7.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-8.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-9.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-10.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
                <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz">
                    <img class="h-auto max-w-full rounded-lg"
                         src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-11.jpg" alt="">
                    <p class="text-center font-normal text-gray-700 dark:text-gray-400">01 - personal Image - 2023/10/05</p>

                </div>
            </div>

            <!-- Main modal -->
            <div id="defaultModal" tabindex="-1" aria-hidden="true"
                 class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                New document
                            </h3>
                            <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="defaultModal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-6">
                            <label for="countries"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an
                                option</label>
                            <select id="countries"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected>Choose a country</option>
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="FR">France</option>
                                <option value="DE">Germany</option>
                            </select>


                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                   for="file_input">Upload file</label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                id="file_input" type="file" onchange="previewImage()">

                            <img id="image_preview" class="w-full h-auto" src="http://placehold.it/180" alt="Preview Image" style="display:none; ">

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button data-modal-hide="defaultModal" type="button"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                save</button>
                            <button data-modal-hide="defaultModal" type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">cancel</button>
                        </div>
                    </div>
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
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="">
                            <img class="h-auto max-w-full rounded-lg"
                                 src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image.jpg" alt="">
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function previewImage() {
            const fileInput = document.getElementById('file_input');
            const imagePreview = document.getElementById('image_preview');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };

                reader.readAsDataURL(fileInput.files[0]);
            }else {
                imagePreview.style.display = 'none';
            }
        }
    </script>
@endsection
