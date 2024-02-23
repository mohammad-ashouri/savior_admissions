import './bootstrap';
import 'flowbite';
import $ from 'jquery';
import Swal from 'sweetalert2';
import 'ionicons';
import moment from 'moment';

window.moment = moment;
window.Swal = Swal;

function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, html: text, icon: icon, confirmButtonText: confirmButtonText,
    });
}

function resetAllInputValues() {
    $('input:not([name="_token"]):not([name="id"])').each(function () {
        $(this).val(null);
    });
}

function resetAllSelectValues() {
    $('select').each(function () {
        $(this).val(null);
    });
}

function resetFields() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => input.value = "");
    const selectors = document.querySelectorAll('select');
    selectors.forEach(select => select.value = "");
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => textarea.value = "");

    // const radios = document.querySelectorAll('input');
    // radios.forEach(input => input.selected = "");
    // const checkboxes = document.querySelectorAll("input");
    // checkboxes.forEach(input => input.selected = "");
}

// open toggled sidebar
const toggleButton = document.getElementById('toggleButton');
const logoSidebar = document.getElementById('logo-sidebar');
const content = document.getElementById('content');
const menuListItems = document.querySelectorAll('.menulist');
const menuListHoverItems = document.querySelectorAll('.menulist-hover');

toggleButton.addEventListener('click', function () {
    logoSidebar.classList.toggle('expanded');
    content.classList.toggle('ex-ml');
    if (logoSidebar.classList.contains('expanded')) {
        menuListItems.forEach(item => {
            item.style.display = 'inline-block';
        });
        menuListHoverItems.forEach(item => {
            item.style.display = 'none';
        });
    } else {
        menuListItems.forEach(item => {
            item.style.display = 'none';
        });

        menuListHoverItems.forEach(item => {
            item.style.display = 'inline-block';
        });
    }
});

if (logoSidebar.classList.contains('expanded')) {
    menuListItems.forEach(item => {
        item.style.display = 'inline-block';
    });

    menuListHoverItems.forEach(item => {
        item.style.display = 'none';
    });
} else {
    menuListItems.forEach(item => {
        item.style.display = 'none';
    });
    menuListHoverItems.forEach(item => {
        item.style.display = 'inline-block';
    });
}

// config dark mode
var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

// Change the icons inside the button based on previous settings
if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    themeToggleLightIcon.classList.remove('hidden');
} else {
    themeToggleDarkIcon.classList.remove('hidden');
}

var themeToggleBtn = document.getElementById('theme-toggle');

themeToggleBtn.addEventListener('click', function () {

    // toggle icons inside button
    themeToggleDarkIcon.classList.toggle('hidden');
    themeToggleLightIcon.classList.toggle('hidden');

    // if set via local storage previously
    if (localStorage.getItem('color-theme')) {
        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }

        // if NOT set via local storage previously
    } else {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        }
    }

});

$(document).ready(function () {
    let fullPath = window.location.pathname;
    let csrf_token = 'meta[name="csrf-token"]';
    let pageTitle = null;
    if (fullPath.includes('users')) {
        pageTitle = 'User Management';
        if (fullPath.includes('edit')) {
            pageTitle = 'Edit User Profile';
            $('#changeUserGeneralInformation').submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: '/users/change_user_general_information',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        swalFire('Done', response.success, 'success', 'Ok');
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).error, 'error', 'Try again');
                    }
                });
            });

            $('#changeUserPassword').submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: '/users/change_password',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        New_password.value = '';
                        Confirm_password.value = '';
                        swalFire('Done', 'Password changed successfully!', 'success', 'Ok');
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                    }
                });
            });

            $('#change-rules').submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: '/users/change_rules',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        swalFire('Done', response.success, 'success', 'Ok');
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).error, 'error', 'Try again');
                    }
                });
            });

            $('#changeStudentInformation').submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: '/student/change_information',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        swalFire('Done', response.success, 'success', 'Ok');
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).error, 'error', 'Try again');
                    }
                });
            });

            $('#changePrincipalInformation').submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: '/users/change_school_admin_information',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        swalFire('Done', response.success, 'success', 'Ok');
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                    }
                });
            });

            $('#student-extra-information-table').on('click', '.delete-row', function () {
                $(this).closest('tr').remove();
            });

            $('.add-row').on('click', function () {
                var newRow = '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">' +
                    '<td class="p-4">' +
                    '<input type="text" name="title[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>' +
                    '</td>' +
                    '<td class="p-4">' +
                    '<input type="text" name="description[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>' +
                    '</td>' +
                    '<td class="p-4 text-center">' +
                    '<button type="button" class="delete-row text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-2 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">' +
                    '<i class="las la-trash" style="font-size: 24px"></i>' +
                    '</button>' +
                    '</td>' +
                    '</tr>';

                $('tbody').append(newRow);
            });
        }

        $('#search-user').submit(function (e) {
            e.preventDefault();
            if (($('#search-edu-code').val() == null || $('#search-edu-code').val() == '') &&
                ($('#search-first-name').val() == null || $('#search-first-name').val() == '') &&
                ($('#search-last-name').val() == null || $('#search-last-name').val() == '')) {
                swalFire('Error', 'Fields are empty', 'error', 'Ok');
            } else {
                this.submit();
            }
        });

    }
    else if (fullPath.includes('DocumentTypes')) {
        pageTitle = 'Document Types Manager';
    }
    else if (fullPath.includes('Levels')) {
        pageTitle = 'Levels Manager';
        $('#new-level').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your level will be added permanently!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#new-level').off('submit').submit();
                }
            });
        });
    }
    else if (fullPath.includes('AcademicYears')) {
        pageTitle = 'Academic Years Manager';
        $('#new-academic-year').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your academic year will be added permanently!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#new-academic-year').off('submit').submit();
                }
            });
        });
    }
    else if (fullPath.includes('searchUsers')) {
        pageTitle = 'Search users';
    }
    else if (fullPath.includes('AcademicYearClasses')) {
        pageTitle = 'Academic Year Classes';
        $('#academic_year').change(function (e) {
            $.ajax({
                type: 'GET',
                url: '/GetLevelsForAcademicYearClass',
                data: {
                    academic_year: $(this).val()
                },
                headers: {
                    'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                }, success: function (response) {
                    var selectLevel = $('#level');
                    selectLevel.empty();

                    selectLevel.append('<option selected disabled value="">Select level</option>');

                    $.each(response, function (index, level) {
                        selectLevel.append('<option value="' + level.id + '">' + level.name + '</option>');
                    });
                }, error: function (xhr, textStatus, errorThrown) {
                    swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                }
            });
        });
    }
    else if (fullPath.includes('Documents')) {
        pageTitle = 'Documents';
        const images = []; // Array to store image URLs
        let currentIndex = 0; // Variable to track the current image index
        resetFields();
        $('#create-document').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your document will be added permanently!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = $(this);
                    var formData = new FormData(form[0]);
                    $.ajax({
                        type: 'POST',
                        url: '/Documents/Create',
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                        }, success: function (response) {
                            location.reload();
                        }, error: function (xhr, textStatus, errorThrown) {
                            swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                        }
                    });
                }
            });
        });
        $('#create-document-for-user').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'User\'s document will be added permanently!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = $(this);
                    var formData = new FormData(form[0]);
                    var currentUrl = window.location.href;
                    var parts = currentUrl.split('/');
                    var urlLastPart = parts[parts.length - 1];
                    $.ajax({
                        type: 'POST',
                        url: '/Documents/Create/' + urlLastPart,
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                        }, success: function (response) {
                            location.reload();
                        }, error: function (xhr, textStatus, errorThrown) {
                            swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                        }
                    });
                }
            });
        });

        $('.type-filter').click(function () {
            var typeId = $(this).data('type-id');

            // اضافه کردن کلاس‌های فعال به دکمه کلیک شده
            $(this).addClass('text-blue-700 hover:text-white border border-blue-600 bg-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300');

            // حذف کلاس‌های فعال از دکمه‌های دیگر
            $('.type-filter').not(this).removeClass('text-blue-700 hover:text-white border border-blue-600 bg-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300');

            if (typeId === 'all') {
                $('.document-div').show();
            } else {
                $('.document-div').hide();
                $('.document-div[data-type-id="' + typeId + '"]').show();
            }
        });
        $('.show-image').click(function () {
            let imageSrc = $(this).data('image-src');
            let imageTitle = $(this).data('image-title');
            $('#image-for-show').attr('src', imageSrc);
            $('.DocumentTitle').text(imageTitle);
        });
        $('#document_file').change(function () {
            const fileInput = $('#document_file');
            const imagePreview = $('#image_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });

        // Function to show the image at the given index
        const showImage = index => {
            $('#image-for-show').attr('src', images[index]);
            let imageTitle = $('[data-image-src="' + images[index] + '"]').data('image-title');
            $('.DocumentTitle').text(imageTitle);
            currentIndex = index;
        };

        $('.next-button').on('click', function () {
            if (currentIndex < images.length - 1) {
                showImage(currentIndex + 1);
            }
        });

        $('.previous-button').on('click', function () {
            if (currentIndex > 0) {
                showImage(currentIndex - 1);
            }
        });

        $('[data-modal-toggle="openImage"]').on('click', function () {
            const imageUrl = $(this).data('image-src');
            $('#image-for-show').attr('src', imageUrl);

            images.length = 0;
            $('[data-modal-toggle="openImage"]').each(function () {
                images.push($(this).data('image-src'));
            });

            currentIndex = images.indexOf(imageUrl);

            $('#openImage').removeClass('hidden');

            let initialImageTitle = $('[data-image-src="' + imageUrl + '"]').data('image-title');
            $('.DocumentTitle').text(initialImageTitle);
        });

        $('[data-modal-hide="openImage"]').on('click', function () {
            $('#openImage').addClass('hidden');
        });

    }
    else if (fullPath.includes('EducationYears')) {
        pageTitle = 'Education Years';
        $('#new-education-year').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your education year will be added permanently!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#new-education-year').off('submit').submit();
                }
            });
        });
        $('#finish-education-year').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your education year will be finished permanently!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit').submit();
                }
            });
        });

    }
    else if (fullPath.includes('EducationTypes')) {
        pageTitle = 'Education Types';
        $('#new-education-type').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your education type will be added permanently!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#new-education-type').off('submit').submit();
                }
            });
        });

    }
    else if (fullPath.includes('ApplicationTimings')) {
        pageTitle = 'Application Timings Manager';
        // resetAllInputValues();
        resetAllSelectValues();

        //Set interviewers selection
        $('#academic_year').change(function () {
            $.ajax({
                type: 'GET',
                url: '/GetInterviewersForApplications',
                data: {
                    academic_year: $(this).val()
                },
                headers: {
                    'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                }, success: function (response) {
                    var selectInterviewer = $('#interviewers');
                    selectInterviewer.empty();
                    resetAllInputValues();
                    $.each(response, function (index, Interviewer) {
                        selectInterviewer.append('<option value="' + Interviewer.id + '">' + Interviewer.general_information_info.first_name_en + ' ' + Interviewer.general_information_info.last_name_en + '( ' + Interviewer.general_information_info.first_name_fa + ' ' + Interviewer.general_information_info.last_name_fa + ' )' + '</option>');
                    });
                }, error: function (xhr, textStatus, errorThrown) {
                    swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                }
            });

            //Set app start and end date min and max attributes
            $.ajax({
                type: 'GET',
                url: '/GetAcademicYearStarttimeAndEndtime',
                data: {
                    academic_year: $(this).val()
                },
                headers: {
                    'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                }, success: function (response) {
                    $("#start_date, #end_date").prop({
                        "min": response.start_date,
                        "max": response.end_date
                    });
                }, error: function (xhr, textStatus, errorThrown) {
                    swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                }
            });
        });

        $('#new-application-timing').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Application timing created by you can no longer be deleted or edited!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#new-application-timing').off('submit').submit();
                }
            });
        });

        $('.RemoveApplicationReservation').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'The application selected by you will be unreserved and its payment information will be deleted forever!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit').submit();
                }
            });
        });

        $('.ChangeApplicationStatus').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your application status will change!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit').submit();
                }
            });
        });

        $('.RemoveApplication').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Application selected by you can no longer be deleted!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit').submit();
                }
            });
        });
    }
    else if (fullPath.includes('PrepareToPayApplication')) {
        pageTitle = 'Pay Application';
        resetAllSelectValues();
        resetAllInputValues();

        var dateTimeString = document.getElementById('deadline').innerText;
        var now = new Date();
        var deadline = new Date(dateTimeString);
        var remainingTime = (deadline - now) / 1000;

        function countdownTimer() {
            var hours = Math.floor(remainingTime / 3600);
            var minutes = Math.floor((remainingTime % 3600) / 60);
            var seconds = Math.floor(remainingTime % 60);

            var timerElement = document.getElementById('timer');
            timerElement.innerText = hours.toString().padStart(2, '0') + ":" + minutes.toString().padStart(2, '0') + ":" + seconds.toString().padStart(2, '0');

            remainingTime--;

            if (remainingTime < 0) {
                clearInterval(timerInterval);
                window.location.href = '/Applications';
            }
        }

        let timerInterval = setInterval(countdownTimer, 1000);

        $('#payment_method').change(function () {
            if ($(this).val() === '1') {
                $('#offline_payment_div').show();
                $('#online_payment_iran_div').hide();
                $('#online_payment_paypal_div').hide();
            } else if ($(this).val() === '2') {
                $('#offline_payment_div').hide();
                $('#online_payment_iran_div').show();
                $('#online_payment_paypal_div').hide();
            } else if ($(this).val() === '3') {
                $('#offline_payment_div').hide();
                $('#online_payment_iran_div').hide();
                $('#online_payment_paypal_div').show();
            }
        });

        $('#document_file').change(function () {
            const fileInput = $('#document_file');
            const imagePreview = $('#image_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });

        $('#application-payment').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your payment will be registered in the system. Are you sure to continue?',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#application-payment').off('submit').submit();
                }
            });
        });

    }
    else if (fullPath.includes('Applications')) {
        pageTitle = 'Applications';

        if (fullPath.includes('Applications/create')) {
            resetAllSelectValues();
            $('#level').change(function () {
                $.ajax({
                    type: 'GET',
                    url: '/GetAcademicYearsByLevel',
                    data: {
                        level: $(this).val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        var selectAcademicYear = $('#academic_year');
                        selectAcademicYear.empty();
                        var selectDateAndTime = $('#date_and_time');
                        selectDateAndTime.empty();

                        selectAcademicYear.append('<option selected disabled value="">Select academic year</option>');
                        selectDateAndTime.append('<option selected disabled value="">Select date and time</option>');

                        $.each(response, function (index, academic_year) {
                            selectAcademicYear.append('<option value="' + academic_year.id + '">' + academic_year.name + '</option>');
                        });
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).error, 'error', 'Try again');
                    }
                });
            });

            $('#academic_year').change(function () {
                $.ajax({
                    type: 'GET',
                    url: '/GetApplicationsByAcademicYear',
                    data: {
                        academic_year: $(this).val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        var selectDateAndTime = $('#date_and_time');
                        selectDateAndTime.empty();

                        selectDateAndTime.append('<option selected disabled value="">Select date and time</option>');

                        $.each(response, function (index, date_and_time) {
                            selectDateAndTime.append('<option value="' + date_and_time.id + '">' + date_and_time.date + " - " + date_and_time.start_from + " - " + date_and_time.ends_to + '</option>');
                        });
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).error, 'error', 'Try again');
                    }
                });
            });

            $('#date_and_time').change(function () {
                $.ajax({
                    type: 'GET',
                    url: '/CheckDateAndTimeToBeFreeApplication',
                    data: {
                        application: $(this).val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).error, 'error', 'Try again');
                    }
                });
            });

            $('#interview_type').change(function () {
                if ($(this).val() === 'On-Sight') {
                    $('#onsight-alert').show();
                } else {
                    $('#onsight-alert').hide();
                }
            });

            $('#application-payment').submit(function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Your application will go to the payment stage. Are you sure about the entered information?',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#application-payment').off('submit').submit();
                    }
                });
            });
        }

    }
    else if (fullPath.includes('roles')) {
        pageTitle = 'Roles';
    }
    else if (fullPath.includes('Schools')) {
        pageTitle = 'Schools';
    }
    else if (fullPath.includes('Students')) {
        pageTitle = 'Students';
        $('#new-student').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your student will be added permanently.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#new-student').off('submit').submit();
                }
            });
        });

    }
    else if (fullPath.includes('Interviews')) {
        pageTitle = 'Interviews';
    }
    else if (fullPath.includes('SetInterview')) {
        pageTitle = 'Set Interview';


    }
    else if (fullPath.includes('ReservationInvoices')) {
        pageTitle = 'Reservation Invoices';

        $('#payment_status').change(function () {
            let status = $(this).val();
            if (status != null) {
                $.ajax({
                    type: 'POST',
                    url: '/ChangeApplicationPaymentStatus',
                    data: {
                        application_id: $(this).data('reservation-id'),
                        status: status,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        swalFire('Done', response.message, 'success', 'Ok');
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                    }
                });
            }
        });
    }
    else if (fullPath.includes('Tuition')) {
        pageTitle = 'Tuition Manager';

        $('#payment_status').change(function () {
            let status = $(this).val();
            if (status != null) {
                $.ajax({
                    type: 'POST',
                    url: '/ChangeApplicationPaymentStatus',
                    data: {
                        application_id: $(this).data('reservation-id'),
                        status: status,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        swalFire('Done', response.message, 'success', 'Ok');
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                    }
                });
            }
        });
    }
    else {
        switch (fullPath) {
            case '/dashboard':
                pageTitle = 'Dashboard';
                break;
            case '/Profile':
                pageTitle = 'Profile';
                $('#reset-password').submit(function (e) {
                    e.preventDefault();
                    var form = $(this);
                    var data = form.serialize();
                    $.ajax({
                        type: 'POST',
                        url: '/password/change',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                        }, success: function (response) {
                            Current_password.value = '';
                            New_password.value = '';
                            Confirm_password.value = '';
                            swalFire('Done', 'Password changed successfully!', 'success', 'Ok');
                        }, error: function (xhr, textStatus, errorThrown) {
                            swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                        }
                    });
                });
                break;
        }
    }
    $('#page-title').text(pageTitle + ' | Savior Schools');
});
