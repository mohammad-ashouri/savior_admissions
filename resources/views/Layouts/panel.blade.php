<!DOCTYPE html>
@php
    use App\Models\User;
@endphp
<html class="light" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title @if(!isset($title)) id="page-title" @endif>{{ $title ?? '' }}</title>
    <script src="/build/plugins/jquery/dist/jquery.js"></script>
    <link href="/build/plugins/select2/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="/build/plugins/select2/dist/js/select2.min.js"></script>

    <script src="/build/plugins/persian-date/dist/persian-date.js"></script>
    <script src="/build/plugins/persian-datepicker/dist/js/persian-datepicker.js"></script>
    <link rel="stylesheet" href="/build/plugins/persian-datepicker/dist/css/persian-datepicker.css"/>

    <link href="/build/plugins/DataTables/datatables.min.css" rel="stylesheet">
    <script src="/build/plugins/DataTables/datatables.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/build/plugins/Buttons-3.1.2/css/buttons.dataTables.min.css"/>
    <script src="/build/plugins/Buttons-3.1.2/js/dataTables.buttons.min.js"></script>
    <script src="/build/plugins/Buttons-3.1.2/js/buttons.dataTables.min.js"></script>
    <script src="/build/plugins/Buttons-3.1.2/js/buttons.html5.min.js"></script>
    <script src="/build/plugins/Buttons-3.1.2/js/buttons.print.min.js"></script>

    <script src="/build/plugins/ColReorder-2.0.4/js/dataTables.colReorder.min.js"></script>
    <script src="/build/plugins/ColReorder-2.0.4/js/colReorder.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/build/plugins/ColReorder-2.0.4/css/colReorder.dataTables.min.css"/>

    <link rel="stylesheet" type="text/css" href="/build/plugins/Select-2.1.0/css/select.dataTables.css"/>
    <script src="/build/plugins/Select-2.1.0/js/dataTables.select.js"></script>
    <script src="/build/plugins/Select-2.1.0/js/select.dataTables.js"></script>

    <script src="/build/plugins/jszip/dist/jszip.min.js"></script>
    <script src="/build/plugins/pdfmake/build/pdfmake.min.js"></script>
    <script src="/build/plugins/pdfmake/build/vfs_fonts.js"></script>

    <script src="/build/plugins/ChartJs-4.4.0/chart.js"></script>

    <script src="/build/plugins/Cleave/Cleave.js"></script>
    <script>
        function swalFire(title = null, text, icon, confirmButtonText) {
            Swal.fire({
                title: title, html: text, icon: icon, confirmButtonText: confirmButtonText,
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (!window.location.pathname.includes('AllTuitions') && !(window.location.pathname.includes('/Tuition') && window.location.pathname.includes('edit'))) {
                let table;

                function initializeDataTable() {
                    if (table) {
                        table.destroy();
                    }

                    table = new DataTable('.datatable', {
                        "ordering": true,
                        "searching": true,
                        "paging": true,
                        "info": true,
                        "pageLength": 25,
                        "lengthChange": true,
                        select: false,
                        colReorder: true,
                        responsive: true,
                        "language": {
                            "paginate": {
                                "first": "&laquo;&laquo;",
                                "last": "&raquo;&raquo;",
                                "previous": "&laquo;",
                                "next": "&raquo;"
                            }
                        },
                        dom: '<"top"lfB>rt<"bottom"ip><"clear">',
                        buttons: [
                            'copy',
                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                title: document.title,
                                filename: function () {
                                    let date = new Date();
                                    let formattedDate = date.getFullYear() + '-' +
                                        (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                                        date.getDate().toString().padStart(2, '0') + '_' +
                                        date.getHours().toString().padStart(2, '0') + '-' +
                                        date.getMinutes().toString().padStart(2, '0');
                                    return document.title + '_' + formattedDate;
                                }, exportOptions: {
                                    columns: ':not(.action)'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF (Portrait)',
                                orientation: 'portrait',
                                pageSize: 'A4',
                                title: 'Report (Portrait)',
                                filename: function () {
                                    let date = new Date();
                                    let formattedDate = date.getFullYear() + '-' +
                                        (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                                        date.getDate().toString().padStart(2, '0') + '_' +
                                        date.getHours().toString().padStart(2, '0') + '-' +
                                        date.getMinutes().toString().padStart(2, '0');
                                    return document.title + '_' + formattedDate;
                                },
                                customize: function (doc) {
                                    const pageSize = doc.pageSize;
                                    doc.watermark = {
                                        text: 'Savior',
                                        color: 'grey',
                                        fontSize: 60,
                                        width: 300,
                                        height: 300,
                                        opacity: 0.3,
                                        absolutePosition: {
                                            x: (pageSize.width - 300) / 2,
                                            y: (pageSize.height - 300) / 2
                                        }
                                    };
                                }, exportOptions: {
                                    columns: ':not(.action)'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF (Landscape)',
                                orientation: 'landscape',
                                pageSize: 'A4',
                                title: 'Report (Landscape)',
                                filename: function () {
                                    let date = new Date();
                                    let formattedDate = date.getFullYear() + '-' +
                                        (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                                        date.getDate().toString().padStart(2, '0') + '_' +
                                        date.getHours().toString().padStart(2, '0') + '-' +
                                        date.getMinutes().toString().padStart(2, '0');
                                    return document.title + '_' + formattedDate;
                                },
                                customize: function (doc) {
                                    const pageSize = doc.pageSize;
                                    doc.watermark = {
                                        text: 'Savior',
                                        color: 'grey',
                                        fontSize: 60,
                                        width: 300,
                                        height: 300,
                                        opacity: 0.3,
                                        absolutePosition: {
                                            x: (pageSize.width - 300) / 2,
                                            y: (pageSize.height - 300) / 2
                                        }
                                    };
                                }, exportOptions: {
                                    columns: ':not(.action)'
                                }
                            },
                            'print',
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: ':not(.action)'
                                }
                            }
                        ]
                    });

                    $('.datatable thead').prepend('<tr class="filter-row"></tr>');

                    table.columns().every(function () {
                        let column = this;
                        let header = $(column.header());

                        let select = $('<th><select><option value="">All</option></select></th>')
                            .appendTo('.datatable thead tr.filter-row')
                            .find('select')
                            .on('change', function () {
                                let val = $.fn.DataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        if (header.hasClass('nofilter')) {
                            return;
                        }

                        let uniqueData = [];
                        column.data().unique().each(function (d, j) {
                            if (d) {
                                let cleanData = $('<div>').html(d).text().trim();
                                if (cleanData && !uniqueData.includes(cleanData)) {
                                    uniqueData.push(cleanData);
                                }
                            }
                        });

                        uniqueData.sort(function (a, b) {
                            let numA = parseInt(a.match(/\d+/)) || 0;
                            let numB = parseInt(b.match(/\d+/)) || 0;
                            if (numA === numB) {
                                return a.localeCompare(b);
                            }
                            return numA - numB;
                        });

                        uniqueData.forEach(function (cleanData) {
                            select.append('<option value="' + cleanData + '">' + cleanData + '</option>');
                        });
                    });
                }

                initializeDataTable();

                document.addEventListener("DOMContentLoaded", () => {
                    Livewire.on('dispatched-event', function () {
                        setTimeout(initializeDataTable, 100);
                    })
                });
            }
        });

        $(document).ready(function () {
            $(".persian_date_with_clock").pDatepicker(
                {
                    "format": "YYYY/MM/DD H:m:ss",
                    "viewMode": "day",
                    "initialValue": false,
                    "minDate": null,
                    "maxDate": null,
                    "autoClose": false,
                    "position": "auto",
                    "altFormat": "lll",
                    "altField": "#altfieldExample",
                    "onlyTimePicker": false,
                    "onlySelectOnDate": true,
                    "calendarType": "persian",
                    "inputDelay": 800,
                    "observer": false,
                    "calendar": {
                        "persian": {
                            "locale": "fa",
                            "showHint": true,
                            "leapYearMode": "algorithmic"
                        },
                        "gregorian": {
                            "locale": "en",
                            "showHint": false
                        }
                    },
                    "navigator": {
                        "enabled": true,
                        "scroll": {
                            "enabled": true
                        },
                        "text": {
                            "btnNextText": "<",
                            "btnPrevText": ">"
                        }
                    },
                    "toolbox": {
                        "enabled": true,
                        "calendarSwitch": {
                            "enabled": false,
                            "format": "MMMM"
                        },
                        "todayButton": {
                            "enabled": true,
                            "text": {
                                "fa": "امروز",
                                "en": "Today"
                            }
                        },
                        "submitButton": {
                            "enabled": false,
                            "text": {
                                "fa": "تایید",
                                "en": "Submit"
                            }
                        },
                        "text": {
                            "btnToday": "امروز"
                        }
                    },
                    timePicker: {
                        enabled: true,
                        step: 1,
                        hour: {
                            enabled: true,
                            step: 1
                        },
                        minute: {
                            enabled: true,
                            step: 1
                        },
                        second: {
                            enabled: true,
                            step: 1
                        },
                        meridian: {
                            enabled: false
                        }
                    },
                    "dayPicker": {
                        "enabled": true,
                        "titleFormat": "YYYY MMMM"
                    },
                    "monthPicker": {
                        "enabled": true,
                        "titleFormat": "YYYY"
                    },
                    "yearPicker": {
                        "enabled": true,
                        "titleFormat": "YYYY"
                    },
                    "responsive": true,
                    "onHide": function () {
                        let inputValue = $('#date_of_payment').val();

                        $.ajax({
                            type: 'POST',
                            url: '/TuitionInvoices/changeTuitionInvoiceDetails',
                            data: {
                                tuition_invoice_id: $('#tuition_invoice_id').val(),
                                data: inputValue,
                                job: 'change_payment_date'
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                swalFire('Done', 'Payment date changed!', 'success', 'Ok');
                            },
                            error: function (xhr, textStatus, errorThrown) {
                                swalFire('Error', xhr.responseJSON?.message || 'An error occurred', 'error', 'Try again');
                            }
                        });
                    }
                }
            );


            $('.select2').select2({
                placeholder: 'Choose an option',
                theme: "classic",
                width: '100%'
            });
        });
    </script>
    @livewireStyles
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
                        class=" hidden md:inline-block self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">Savior International Schools</span>
                </a>
            </div>
            <div class="flex items-center">

                <div class="flex items-center">
                    <button data-tooltip-target="tooltip-bottom" data-tooltip-placement="bottom" type="button"
                            id="theme-toggle"
                            class="text-white dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                        <div id="tooltip-bottom" role="tooltip"
                             class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Change Mode
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
                                {{ auth()->user()->generalInformationInfo->first_name_en }} {{ auth()->user()->generalInformationInfo->last_name_en }}
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="/"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                   role="menuitem">Dashboard</a>
                            </li>
                            <li>
                                <button type="button" id="change-my-password-btn"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Change Password
                                </button>
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
                    <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-users" data-collapse-toggle="dropdown-users">
                        <i class="nav-icon la la-landmark" style="font-size: 24px"></i>
                        <span class="flex-1 ml-4 text-left whitespace-nowrap">Users</span>
                        <i class="las la-angle-right mr-1" style="font-size: 20px"></i>
                    </button>
                    <ul id="dropdown-users" class="hidden py-2 space-y-2">
                        @can('list-users')
                            <li>
                                <a href="/users"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-chalkboard-teacher"
                                                              style="font-size: 24px"></i>
                                        All Users</span>
                                </a>
                            </li>
                        @endcan
                        @can('pending-user-approvals.view')
                            <li>
                                <a href="{{ route('pending-user-approvals') }}"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-chalkboard-teacher"
                                                              style="font-size: 24px"></i>
                                        Pending User Approvals</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

            @endcan
            @can('branch-info-menu-access')
                <li>
                    <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-branch" data-collapse-toggle="dropdown-branch">
                        <i class="nav-icon la la-landmark" style="font-size: 24px"></i>
                        <span class="flex-1 ml-4 text-left whitespace-nowrap">Branch Info</span>
                        <i class="las la-angle-right mr-1" style="font-size: 20px"></i>
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
                            @can('confirm-interview')
                                <li>
                                    <a href="{{route('Application.ConfirmInterview')}}"
                                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-book-reader"
                                                              style="font-size: 24px"></i>
                                        Interview Confirmation</span>
                                    </a>
                                </li>
                            @endcan
                        @can('application-confirmation-menu-access')
                            <li>
                                <a href="{{route('Application.ConfirmApplicationList')}}"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-check"
                                                              style="font-size: 24px"></i>
                                        Application Confirmation</span>
                                </a>
                            </li>
                        @endcan
                        @can('evidences-confirmation')
                            <li>
                                <a href="{{route('Evidences')}}"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist text-nowrap"><i class="nav-icon la la-id-card"
                                                                          style="font-size: 24px"></i>
                                        Uploaded Documents</span>
                                </a>
                            </li>
                        @endcan
                        @can('student-statuses-menu-access')
                            <li>
                                <a href="{{route('StudentStatus')}}"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-hourglass-half"
                                                              style="font-size: 24px"></i>
                                        Students Status</span>
                                </a>
                            </li>
                        @endcan
                        @can('student-statistics-report-menu-access')
                            <li>
                                <a href="{{route('StudentStatisticsReport')}}"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-hourglass-half"
                                                              style="font-size: 24px"></i>
                                        Student Statistics Report</span>
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
                        <i class="las la-angle-right mr-1" style="font-size: 20px"></i>
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
                        <li>
                            <a href="/TuitionInvoices"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la la-money" style="font-size: 24px"></i>
                                        Tuition Invoices</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tuitionsStatus') }}"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la la-money" style="font-size: 24px"></i>
                                        Tuitions Status</span>
                            </a>
                        </li>
                        <li>
                            <a href="/InvoicesDetails"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la la-money" style="font-size: 24px"></i>
                                        Invoices Details</span>
                            </a>
                        </li>
                        @can('all-tuitions-index')
                            <li>
                                <a href="{{ route('allTuitions') }}"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="las la la-money" style="font-size: 24px"></i>
                                        All Tuitions</span>
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
                        <i class="las la-angle-right mr-1" style="font-size: 20px"></i>
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
            @if(auth()->user()->hasRole('Super Admin'))
                <li>
                    <a href="/telescope"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="las la-crosshairs" style="font-size: 24px"></i>
                        <span class="ml-4">Telescope</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('logout') }}"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="las la-sign-out-alt" style="font-size: 24px"></i>
                    <span class="ml-4 ">Logout</span>
                </a>
            </li>
            @impersonating($guard=null)
            <li>
                <a href="{{ route('impersonate.leave') }}"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="las la-sign-out-alt" style="font-size: 24px"></i>
                    <span class="ml-4 ">Leave Impersonation</span>
                </a>
            </li>
            @endImpersonating
        </ul>
    </div>
</aside>

@yield('content')
{{ $slot ?? '' }}
@livewireScripts
@filepondScripts
</body>
</html>
