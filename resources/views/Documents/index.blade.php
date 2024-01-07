@php use App\Models\Catalogs\DocumentType; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 text-black dark:text-white">
                <div class="pt-6">
                    <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                            class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="button">
                        New Document
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-center pb-4 md:pb-8 flex-wrap mt-10 md:mt-8">
                <button type="button" data-type-id="all"
                        class="text-blue-700 hover:text-white border border-blue-600 bg-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-full text-base font-medium px-5 py-2.5 text-center mr-3 mb-3 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:bg-gray-900 dark:focus:ring-blue-800 type-filter">
                    All
                    types
                </button>
                @foreach (array_unique($myDocumentTypes) as $typeId)
                    @php
                        $documentType = DocumentType::find($typeId);
                    @endphp

                    @if ($documentType)
                        <button type="button" data-type-id="{{ $typeId }}"
                                class="focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-full text-base font-medium px-5 py-2.5 text-center mr-3 mb-3 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:bg-gray-900 dark:focus:ring-blue-800 type-filter">
                            {{ $documentType->name }}
                        </button>
                    @endif
                @endforeach
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 ">
                @foreach ($myDocuments as $document)
                    @php
                        $document->src = str_replace('public', 'storage', $document->src);
                    @endphp
                    <div data-type-id="{{ $document->document_type_id }}"
                         class="grid grid-cols-1 md:grid-cols-1 gap-4 document-div">
                        <div>
                            <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                            >
                                <button data-modal-target="openImage" data-modal-toggle="openImage"
                                        data-image-src="{{ env('APP_URL')}}/{{ $document->src }}"
                                        data-image-title="{{ $document->id . ' - ' . $document->documentType->name . '- ' . $document->created_at }}"
                                        class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                        type="button">
                                    <img class="h-auto max-w-full rounded-lg"
                                         src="{{ env('APP_URL')}}/{{$document->src }}" alt="Document Not Found!">

                                </button>
                            </div>
                            <p class="text-center font-normal text-gray-700 dark:text-gray-400">
                                {{ $document->id . ' - ' . $document->documentType->name . '- ' . $document->created_at }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Main modal -->
            <form @if(isset($user_id)) id="create-document-for-user" @else id="create-document" @endif>
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
                                              stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 ">
                                <label for="document_type"
                                       class=" block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select a
                                    document type</label>
                                <select id="document_type" name="document_type"
                                        class=" mb-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled selected>Choose a document type</option>
                                    @foreach ($documentTypes as $documentType)
                                        <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                                    @endforeach
                                </select>

                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                       for="document_file">Upload file</label>
                                <input
                                    class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                    id="document_file" name="document_file" type="file"
                                    accept=".png,.jpg,.jpeg,.pdf,.bmp">

                                <img id="image_preview" class="w-full h-auto" src="" alt="Preview Image"
                                     style="display:none; ">
                                <div class="info mb-5">
                                    <div class="dark:text-white font-medium mb-1">File requirements:</div>
                                    <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that these
                                        requirements
                                        are met:
                                    </div>
                                    <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                        <li>
                                            The files must be in this format: png, jpg, jpeg, pdf, bmp
                                        </li>
                                        <li>
                                            Maximum size: 5 MB
                                        </li>
                                    </ul>

                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div
                                class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button data-modal-hide="defaultModal" type="submit"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Save
                                </button>
                                <button data-modal-hide="defaultModal" type="button"
                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- openImage Modal -->
            <div id="openImage" tabindex="-1" aria-hidden="true"
                 class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <button type="button"
                        class="mr-10 previous-button text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:text-white">
                    <!-- Previous icon or text -->
                    <!-- Example using an SVG icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:serif="http://www.serif.com/"
                         xmlns:xlink="http://www.w3.org/1999/xlink" fill="white" width="800px"
                         height="800px" viewBox="0 0 32 32"
                         style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"
                         version="1.1" xml:space="preserve">
                    <path
                        d="M6.507,17.508l20.963,-0.008c0.828,-0 1.5,-0.673 1.5,-1.501c-0.001,-0.827 -0.673,-1.499 -1.501,-1.499l-20.975,0.008c0.088,-0.119 0.188,-0.231 0.298,-0.334c0,0 9.705,-9.079 9.705,-9.079c0.605,-0.565 0.636,-1.515 0.071,-2.12c-0.566,-0.604 -1.516,-0.636 -2.12,-0.07c-0,-0 -5.9,5.519 -9.705,9.078c-1.118,1.045 -1.749,2.509 -1.743,4.038c0.006,1.53 0.649,2.989 1.774,4.025c3.848,3.543 9.829,9.05 9.829,9.05c0.609,0.56 1.559,0.521 2.119,-0.088c0.561,-0.609 0.522,-1.558 -0.087,-2.119c-0,-0 -5.98,-5.507 -9.828,-9.05c-0.111,-0.102 -0.211,-0.213 -0.3,-0.331Z"/>
                        <g id="Icon"/>
                </svg>

                    <span class="sr-only">Previous</span>
                </button>
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
                                          stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="text-center">
                            <img class="h-auto max-w-full rounded-lg " id="image-for-show" src=""
                                 alt="image">
                            <p class="mt-2 text-white DocumentTitle"></p>
                        </div>

                    </div>
                </div>
                <button type="button"
                        class="ml-10 next-button text-white bg-transparent rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:text-white">
                    <!-- Next icon or text -->
                    <!-- Example using an SVG icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:serif="http://www.serif.com/"
                         xmlns:xlink="http://www.w3.org/1999/xlink" fill="white" width="800px" height="800px"
                         viewBox="0 0 32 32"
                         style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"
                         version="1.1" xml:space="preserve"><path
                            d="M25.468,14.508l-20.967,-0.008c-0.828,-0 -1.501,0.672 -1.501,1.499c-0,0.828 0.672,1.501 1.499,1.501l21.125,0.009c-0.107,0.159 -0.234,0.306 -0.377,0.439c-3.787,3.502 -9.68,8.951 -9.68,8.951c-0.608,0.562 -0.645,1.511 -0.083,2.119c0.562,0.608 1.512,0.645 2.12,0.083c-0,0 5.892,-5.448 9.68,-8.95c1.112,-1.029 1.751,-2.47 1.766,-3.985c0.014,-1.515 -0.596,-2.968 -1.688,-4.018l-9.591,-9.221c-0.596,-0.574 -1.547,-0.556 -2.121,0.041c-0.573,0.597 -0.555,1.547 0.042,2.121l9.591,9.221c0.065,0.063 0.127,0.129 0.185,0.198Z"/>
                        <g id="Icon"/></svg>
                    <span class="sr-only">Next</span>
                </button>
            </div>
        </div>
    </div>
@endsection
