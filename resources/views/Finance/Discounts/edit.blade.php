@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium">Edit Discounts For Academic
                    Year: {{ $discounts->academicYearInfo->name }}</h1>
            </div>
            @include('GeneralPages.errors.session.error')
            @include('GeneralPages.errors.session.success')

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-3 col-span-3 ">
                    <div class=" bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> All discounts</h1>
                        </div>
                        <form id="discounts-edit" method="post" action="{{ route('Discounts.store') }}">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                <div class="grid gap-6 mb-6">
                                    <div>
                                        <table id="discounts-table"
                                               class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                                            <thead
                                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="p-4">
                                                    <div class=" items-center text-center">
                                                        Name
                                                    </div>
                                                </th>
                                                <th scope="col" class="p-4">
                                                    <div class=" items-center text-center">
                                                        Percentage
                                                    </div>
                                                </th>
                                                <th scope="col" class="p-4">
                                                    <div class=" items-center text-center">
                                                        Display on the interview form
                                                    </div>
                                                </th>
                                                <th scope="col" class="p-4">
                                                    <div class=" items-center text-center">
                                                        Status
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(!empty($discounts->allDiscounts))
                                                @foreach($discounts->allDiscounts as $discount)
                                                    <tr
                                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                        <td class="p-4">
                                                            <input type="hidden" value="{{ $discount->id }}"
                                                                   name="id[]">
                                                            <input type="text" id="name" name="name[]"
                                                                   value="{{$discount->name}}"
                                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                   required>
                                                        </td>
                                                        <td class="p-4">
                                                            <div class="flex justify-between">
                                                                <input type="number" min="0" max="100" id="percentage"
                                                                       name="percentage[]"
                                                                       value="{{$discount->percentage}}"
                                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                       required>
                                                                <span
                                                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                                %
                                                            </span>
                                                            </div>
                                                        </td>
                                                        <td class="p-4">
                                                            <div class="flex justify-between">
                                                                <select name="display_in_form[]" id="display_in_form"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                        required>
                                                                    <option
                                                                        @if($discount->interviewer_permission==1) selected
                                                                        @endif value="1">Display on interview form
                                                                    </option>
                                                                    <option
                                                                        @if($discount->interviewer_permission==0) selected
                                                                        @endif value="0">Dont display on interview form
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td class="p-4 text-center">
                                                            <select name="status[]" id="status"
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            >
                                                                <option @if($discount->status==0) selected
                                                                        @endif value="0">Inactive
                                                                </option>
                                                                <option @if($discount->status==1) selected
                                                                        @endif value="1">Active
                                                                </option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="p-4">
                                                        <input type="text" id="name" name="name[]"
                                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                               required>
                                                    </td>
                                                    <td class="p-4">
                                                        <div class="flex justify-between">
                                                            <input type="number" min="0" max="100" id="percentage"
                                                                   name="percentage[]"
                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                   required>
                                                            <span
                                                                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                                %
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="p-4">
                                                        <div class="flex justify-between">
                                                            <select name="display_in_form[]" id="display_in_form"
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                    required>
                                                                <option value="1">Display on interview form</option>
                                                                <option value="0">Dont display on interview form
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="p-4 text-center">
                                                        <button type="button"
                                                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-2 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 delete-row">
                                                            <i class="las la-trash" style="font-size: 24px"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                        <div class="text-center mt-3">
                                            <button type="button"
                                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-2 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 add-row">
                                                <i class="las la-plus-circle" style="font-size: 24px"></i>
                                                Add discount
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $discounts->id }}" name="discount_id">
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save all
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
