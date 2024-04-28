<!DOCTYPE html>
@php
    use App\Models\User;
    $me=User::with('generalInformationInfo')->find(session('id'));
@endphp
<html class="" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title id="page-title"></title>
    <script src="/build/plugins/jquery/dist/jquery.js"></script>
    <link href="/build/plugins/select2/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="/build/plugins/select2/dist/js/select2.min.js"></script>
    <script>
        function swalFire(title = null, text, icon, confirmButtonText) {
            Swal.fire({
                title: title, html: text, icon: icon, confirmButtonText: confirmButtonText,
            });
        }

        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Choose an option',
                theme: "classic"
            });
        });
    </script>
</head>

<body class=" bg-light-theme-color-base dark:bg-gray-800 ">

<nav
    class="fixed top-0 z-50 w-full bg-light-theme-color-nav-base border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start space-x-0 sm:space-x-5">
                <button class="pl-1 sm:inline-block hidden " id="toggleButton">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px"
                         viewBox="0 0 28 28" fill="none">
                        <path
                            d="M4 7C4 6.44771 4.44772 6 5 6H24C24.5523 6 25 6.44771 25 7C25 7.55229 24.5523 8 24 8H5C4.44772 8 4 7.55229 4 7Z"
                            fill="#9CA3AF"/>
                        <path
                            d="M4 13.9998C4 13.4475 4.44772 12.9997 5 12.9997L16 13C16.5523 13 17 13.4477 17 14C17 14.5523 16.5523 15 16 15L5 14.9998C4.44772 14.9998 4 14.552 4 13.9998Z"
                            fill="#9CA3AF"/>
                        <path
                            d="M5 19.9998C4.44772 19.9998 4 20.4475 4 20.9998C4 21.552 4.44772 21.9997 5 21.9997H22C22.5523 21.9997 23 21.552 23 20.9998C23 20.4475 22.5523 19.9998 22 19.9998H5Z"
                            fill="#9CA3AF"/>
                    </svg>
                </button>

                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="pr-5 inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                              d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>

                <a href="" class="flex ml-2 md:mr-24">
                    <div class="rounded-full bg-white mr-3 text-center p-1">
                        <div class="h-8 w-14 md:h-14 md:w-24 mainLogo"></div>
                    </div>
                    <span
                        class=" hidden md:inline-block self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">Savior School</span>
                </a>
            </div>
            <div class="flex items-center">

                <div class="flex items-center">
                    <button data-tooltip-target="tooltip-bottom" data-tooltip-placement="bottom" type="button"
                            id="theme-toggle"
                            class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                        <div id="tooltip-bottom" role="tooltip"
                             class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Dark mode
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor"
                             viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                             viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                </div>
                <div class="flex items-center ml-3">
                    <div>
                        <button type="button"
                                class="flex text-sm dark:bg-gray-800 bg-white rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <div class="w-8 h-8 mr-3 defaultUserIcon"></div>
                        </button>
                    </div>
                    <div
                        class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 dark:text-white" role="none">
                                {{ $me->generalInformationInfo->first_name_en }} {{ $me->generalInformationInfo->last_name_en }}
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                {{ $me->email }}
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="/"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                   role="menuitem">Dashboard</a>
                            </li>
                            <li>
                                <a href="/Profile"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                   role="menuitem">Settings</a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                   role="menuitem">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</nav>

<aside id="logo-sidebar"
       class="fixed top-0 left-0 z-40 md:w-[3.6rem] transition-width transition-all duration-300  h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
       aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-light-theme-color-nav-base dark:bg-gray-800 overflow-hidden">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="/"
                   class="flex items-center p-2 mt-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="las la-home" style="font-size: 24px"></i>
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>
            @can('students-menu-access')
                <li>
                    <a href="/Students"
                       class="flex items-center p-2 mt-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="las la-users" style="font-size: 24px"></i>
                        <span class="ml-4">Students</span>
                    </a>
                </li>
            @endcan
            @can('users-menu-access')
                <li>
                    <a href="/users"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="las la-users" style="font-size: 24px"></i>
                        <span class="ml-4">Users</span>
                    </a>
                </li>
            @endcan
            @can('branch-info-menu-access')
                <li>
                    <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-branch" data-collapse-toggle="dropdown-branch">
                        <i class="nav-icon la la-landmark" style="font-size: 24px"></i>
                        <span class="flex-1 ml-4 text-left whitespace-nowrap">Branch Info</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="dropdown-branch" class="hidden py-2 space-y-2">
                        @can('academic-year-classes-menu-access')
                            <li>
                                <a href="/AcademicYearClasses"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-chalkboard-teacher"
                                                              style="font-size: 24px"></i>
                                        Classes</span>
                                </a>
                            </li>
                        @endcan
                        @can('application-timings-menu-access')
                            <li>
                                <a href="/ApplicationTimings"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-paste" style="font-size: 24px"></i>
                                        Application Timings</span>
                                </a>
                            </li>
                        @endcan
                        @can('applications-menu-access')
                            <li>
                                <a href="/Applications"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la-calendar-plus" style="font-size: 24px"></i>
                                        Applications</span>
                                </a>
                            </li>
                        @endcan
                        @can('interviews-menu-access')
                            <li>
                                <a href="/Interviews"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-book-reader"
                                                              style="font-size: 24px"></i>
                                        Interviews</span>
                                </a>
                            </li>
                        @endcan
                        @can('application-confirmation-menu-access')
                            <li>
                                <a href="{{route('Application.ConfirmApplicationList')}}"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-book-reader"
                                                              style="font-size: 24px"></i>
                                        Application Confirmation</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('finance-menu-access')
                <li>
                    <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-finance" data-collapse-toggle="dropdown-finance">
                        <i class="las la-coins" style="font-size: 24px"></i>
                        <span class="flex-1 ml-4 text-left whitespace-nowrap">Finance</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="dropdown-finance" class="hidden py-2 space-y-2">
                        @can('reservation-invoice-list')
                            <li>
                                <a href="/ReservationInvoices"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la la-money" style="font-size: 24px"></i>
                                        Reservations</span>
                                </a>
                            </li>
                        @endcan
                        @can('tuition-list')
                            <li>
                                <a href="/Tuition"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la la-file-invoice"
                                                              style="font-size: 24px"></i>
                                        Tuition</span>
                                </a>
                            </li>
                        @endcan
                        @can('discounts-list')
                            <li>
                                <a href="/Discounts"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la la-percent" style="font-size: 24px"></i>
                                        Discounts</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('catalogs-menu-access')
                <li>
                    <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-catalog" data-collapse-toggle="dropdown-catalog">
                        <i class="las la-toolbox" style="font-size: 24px"></i>
                        <span class="flex-1 ml-4 text-left whitespace-nowrap">Catalogs</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="dropdown-catalog" class="hidden py-2 space-y-2">
                        @can('role-list')
                            <li>
                                <a href="/roles"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la-user-tag" style="font-size: 24px"></i>
                                        Roles</span>
                                </a>
                            </li>
                        @endcan
                        @can('school-list')
                            <li>
                                <a href="/Schools"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la-university" style="font-size: 24px"></i>
                                        Schools</span>
                                </a>
                            </li>
                        @endcan
                        @can('document-type-list')
                            <li>
                                <a href="/DocumentTypes"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la-folder-minus" style="font-size: 24px"></i>
                                        Document types</span>
                                </a>
                            </li>
                        @endcan
                        @can('education-type-list')
                            <li>
                                <a href="/EducationTypes"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la-school" style="font-size: 24px"></i>
                                        Education types</span>
                                </a>
                            </li>
                        @endcan
                        @can('level-list')
                            <li>
                                <a href="/Levels"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist">
                                        <i class="nav-icon la la-sort-amount-up" style="font-size: 24px"></i>
                                        Levels</span>
                                </a>
                            </li>
                        @endcan
                        @can('academic-year-list')
                            <li>
                                <a href="/AcademicYears"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist">
                                        <i class="lar la-calendar" style="font-size: 24px"></i>
                                        Academic Years</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('document-list')
                <li>
                    <a href="/Documents"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="las la-image" style="font-size: 24px"></i>
                        <span class="ml-4">Documents</span>
                    </a>
                </li>
            @endcan
            <li>
                <a href="/Profile"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="las la-id-card" style="font-size: 24px"></i>
                    <span class="ml-4">Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="las la-sign-out-alt" style="font-size: 24px"></i>
                    <span class="ml-4 ">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

@yield('content')

</body>
</html>
