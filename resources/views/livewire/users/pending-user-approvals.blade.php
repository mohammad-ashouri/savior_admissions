@php use App\Models\StudentInformation;use App\Models\User;
@endphp
<div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
        <x-flash-messages/>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <h1 class="text-3xl font-semibold text-black dark:text-white ">Pending User Approvals</h1>
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <div class="flex justify-between">
                <div class="relative hidden md:block ">
                    <form wire:submit.prevent="search">
                        <div class="grid grid-cols-5 w-full max-w-full gap-y-2">
                            <div>
                                <input type="text" wire:model="user_id"
                                       class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter user code">
                            </div>
                            <div>
                                <input type="text" wire:model="first_name_en"
                                       class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter first name (en)">
                            </div>
                            <div>
                                <input type="text" wire:model="last_name_en"
                                       class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter last name (en)">
                            </div>
                            <div>
                                <input type="text" wire:model="first_name_fa"
                                       class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter first name (fa)">
                            </div>
                            <div>
                                <input type="text" wire:model="last_name_fa"
                                       class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter last name (fa)">
                            </div>
                            <div>
                                <input type="text" wire:model="mobile"
                                       class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter mobile">
                            </div>
                            <div>
                                <input type="text" wire:model="email"
                                       class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Enter email">
                            </div>
                            <div class="flex justify-center content-center">
                                <button type="submit"
                                        wire:target="search"
                                        wire:loading.remove
                                        class="text-white bg-blue-700 hover:bg-blue-800 w-full h-full focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <i class="fas fa-search mr-2" aria-hidden="true"></i>
                                    Filter
                                </button>
                                <button type="button"
                                        wire:click="resetSearchForm"
                                        class="text-white bg-gray-700 hover:bg-gray-800 w-full h-full focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                    <i class="las la-undo-alt mr-2" aria-hidden="true"></i>
                                    Reset
                                </button>
                                <p class="text-nowrap" wire:loading wire:target="search">Please Wait!</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="flex">
                    <a href="{{ route('users.create') }}">
                        <button type="button"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            Add user
                        </button>
                    </a>

                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                @if(empty($data))
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                         role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path
                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                User with the entered profile was not found
                            </div>
                        </div>
                    </div>
                @else
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">#</th>
                            <th scope="col" class="px-6 py-3 text-center">First Name (En)</th>
                            <th scope="col" class="px-6 py-3 text-center">Last Name (En)</th>
                            <th scope="col" class="px-6 py-3 text-center">First Name (Fa)</th>
                            <th scope="col" class="px-6 py-3 text-center">Last Name (Fa)</th>
                            <th scope="col" class="px-6 py-3 text-center">Mobile</th>
                            <th scope="col" class="px-6 py-3 text-center">Email</th>
                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($data as $user)
                            <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">{{ $user->id }}</td>
                                <td class="px-6 py-1 text-center">{{ $user->generalInformationInfo->first_name_en }}</td>
                                <td class="px-6 py-1 text-center">{{ $user->generalInformationInfo->last_name_en }}</td>
                                <td class="px-6 py-1 text-center">{{ $user->generalInformationInfo->first_name_fa }}</td>
                                <td class="px-6 py-1 text-center">{{ $user->generalInformationInfo->last_name_fa }}</td>
                                <td class="px-6 py-1 text-center">{{ $user->mobile }}</td>
                                <td class="px-6 py-1 text-center">{{ $user->email ?? '-' }}</td>
                                <td class="px-6 py-1 text-center">
                                    <div class="flex justify-center">
                                        <button
                                            wire:target="changeStatus"
                                            wire:loading.remove
                                            wire:click="changeStatus({{ $user->id }})" type="button"
                                            class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                            Change Status
                                        </button>
                                        <p class="text-nowrap" wire:loading wire:target="changeStatus">Please Wait!</p>

                                        <a href="{{ route('users.edit',$user->id) }}" type="button"
                                           class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                            <i class="las la-pen-square" style="font-size: 20px"></i>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
    @if(!empty($data))
        <div class="pagination text-center">
            {{ $data->links() }}
        </div>
    @endif
</div>
