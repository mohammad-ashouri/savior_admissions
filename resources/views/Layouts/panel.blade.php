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
    <title id="page-title"></title>
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
            if (!window.location.pathname.includes('AllTuitions') && !(window.location.pathname.includes('/Tuition') && window.location.pathname.includes('edit'))) {
                let table = new DataTable('.datatable', {
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
                                doc.styles.footer = {
                                    alignment: 'center',
                                    fontSize: 8,
                                    margin: [0, 10, 0, 0]
                                };

                                doc.footer = function (currentPage, pageCount) {
                                    return {text: currentPage.toString() + ' of ' + pageCount, style: 'footer'};
                                };
                                doc.background = function (currentPage, pageSize) {
                                    return {
                                        image: 'data:image/jpeg;base64,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',
                                        width: 300,
                                        height: 300,
                                        opacity: 0.3,
                                        absolutePosition: {
                                            x: (pageSize.width - 300) / 2,
                                            y: (pageSize.height - 300) / 2
                                        }
                                    };
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
                                doc.styles.footer = {
                                    alignment: 'center',
                                    fontSize: 8,
                                    margin: [0, 10, 0, 0]
                                };

                                doc.footer = function (currentPage, pageCount) {
                                    return {text: currentPage.toString() + ' of ' + pageCount, style: 'footer'};
                                };
                                doc.background = function (currentPage, pageSize) {
                                    return {
                                        image: 'data:image/jpeg;base64,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',
                                        width: 300,
                                        height: 300,
                                        opacity: 0.3,
                                        absolutePosition: {
                                            x: (pageSize.width - 300) / 2,
                                            y: (pageSize.height - 300) / 2
                                        }
                                    };
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

                    // ایجاد سلکت در یک th در ردیف فیلتر
                    let select = $('<th><select><option value="">All</option></select></th>')
                        .appendTo('.datatable thead tr.filter-row')
                        .find('select')
                        .on('change', function () {
                            let val = $.fn.DataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    // اگر هدر ستون دارای کلاس nofilter است، گزینه‌ای اضافه نکنید
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
                        @can('application-confirmation-menu-access')
                            <li>
                                <a href="{{route('Application.ConfirmApplicationList')}}"
                                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <span class="menulist"><i class="nav-icon la la-check"
                                                              style="font-size: 24px"></i>
                                        Confirmation</span>
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
