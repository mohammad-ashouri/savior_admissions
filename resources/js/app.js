import 'flowbite';
import $ from 'jquery';
import 'ionicons';
import moment from 'moment';
import Alpine from 'alpinejs'

Alpine.start()
// If you want Alpine's instance to be available everywhere.
window.Alpine = Alpine

import {
    checkAge,
    checkEnglishCharacters,
    checkEnglishDigits,
    checkPersianCharacters,
    formatNumber,
    resetAllInputValues,
    resetAllSelectValues,
    resetFields,
    swalFire,
    validateAddressEntry,
    spinner,
} from './MainJsFunctionsAndImports.js';


window.moment = moment;

$(document).ready(function () {

// config dark mode
    let themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    let themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

// Change the icons inside the button based on previous settings
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleDarkIcon.classList.remove('hidden');
        document.documentElement.classList.add('dark');
    } else {
        themeToggleLightIcon.classList.remove('hidden');
        document.documentElement.classList.remove('dark')
    }

    let themeToggleBtn = document.getElementById('theme-toggle');

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


    const toggleButton = document.getElementById('toggleButton');
    const logoSidebar = document.getElementById('logo-sidebar');
    const content = document.getElementById('content');
    const menuListItems = document.querySelectorAll('.menulist');
    const menuListHoverItems = document.querySelectorAll('.menulist-hover');

    function toggleMenu() {
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
    }

    toggleButton.addEventListener('click', function () {
        toggleMenu();
    });

    function handleResize() {
        if (window.innerWidth >= 768) {
            // If the window width is greater than or equal to 768 pixels
            // toggleMenu(); // Execute toggleMenu to open or close the menu
        } else {
            // If the window width is less than 768 pixels
            // Here you can perform other actions, such as displaying a message or performing another action
        }
    }

// Call handleResize for initial page load
    handleResize();

// Add a listener to check for window resize events
    window.addEventListener('resize', handleResize);

    $('#change-my-password-btn').click(async function () {
        Swal.fire({
            title: 'Password Management',
            html:
                '<div class="flex flex-col items-center space-y-2">' +
                '<label for="current-password" class="text-left">Current Password</label>' +
                '<input id="current-password" class="swal2-input w-full md:w-1/2" placeholder="Enter your current password" type="password" minlength="8" maxlength="24" autocomplete="new-password" autocapitalize="off" autocorrect="off">' +
                '<label for="new-password" class="text-left">New Password</label>' +
                '<input id="new-password" class="swal2-input w-full md:w-1/2" placeholder="Enter your new password" type="password" minlength="8" maxlength="24" autocomplete="new-password" autocapitalize="off" autocorrect="off">' +
                '<label for="confirm-password" class="text-left">Confirm Password</label>' +
                '<input id="confirm-password" class="swal2-input w-full md:w-1/2" placeholder="Confirm your new password" type="password" minlength="8" maxlength="24" autocomplete="new-password" autocapitalize="off" autocorrect="off">' +
                '</div>'
            ,
            focusConfirm: false,
            showCancelButton: true,
            preConfirm: () => {
                const currentPassword = document.getElementById('current-password').value;
                const newPassword = document.getElementById('new-password').value;
                const confirmPassword = document.getElementById('confirm-password').value;

                if (!currentPassword || !newPassword || !confirmPassword) {
                    Swal.showValidationMessage('All fields are required.');
                    return false;
                }

                if (newPassword !== confirmPassword) {
                    Swal.showValidationMessage('New password and confirmation do not match.');
                    return false;
                }

                if (newPassword.length < 8 || newPassword.length > 24) {
                    Swal.showValidationMessage('New password must be between 8 and 24 characters.');
                    return false;
                }

                return [currentPassword, newPassword, confirmPassword];
            },
            confirmButtonText: "Change Password",
        }).then((result) => {
            if (result.isConfirmed) {
                spinner();
                const currentPassword = result.value[0];
                const newPassword = result.value[1];
                const confirmPassword = result.value[2];
                $.ajax({
                    type: 'POST',
                    url: '/password/change',
                    data: {
                        Current_password: currentPassword,
                        New_password: newPassword,
                        Confirm_password: confirmPassword,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        swalFire('Done', 'Password changed successfully!', 'success', 'Ok');
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                    }
                });
                spinner();
            }
        });
    });


    let fullPath = window.location.pathname;
    let csrf_token = 'meta[name="csrf-token"]';
    let pageTitle = null;
    if (fullPath.includes('users')) {
        pageTitle = 'User Management';
        if (fullPath.includes('edit')) {
            pageTitle = 'Edit User Profile';
            $('#changeUserGeneralInformation').submit(function (e) {
                e.preventDefault();
                let form = $(this);
                let data = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: '/users/change_user_general_information',
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

            $('#changeUserPassword').submit(function (e) {
                e.preventDefault();
                let form = $(this);
                let data = form.serialize();
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
                let form = $(this);
                let data = form.serialize();
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
                let form = $(this);
                let data = form.serialize();
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
                let form = $(this);
                let data = form.serialize();
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
                let newRow = '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">' +
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
            if (($('#search-user-code').val() == null || $('#search-user-code').val() == '') &&
                ($('#search-first-name').val() == null || $('#search-first-name').val() == '') &&
                ($('#search-last-name').val() == null || $('#search-last-name').val() == '') &&
                ($('#search-mobile').val() == null || $('#search-mobile').val() == '') &&
                ($('#role').val() == null || $('#role').val() == '')) {
                swalFire('Error', 'Fields are empty', 'error', 'Ok');
            } else {
                this.submit();
            }
        });

    } else if (fullPath.includes('DocumentTypes')) {
        pageTitle = 'Document Types Manager';
    } else if (fullPath.includes('Levels')) {
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
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });
    } else if (fullPath.includes('AcademicYears')) {
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
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });
        $('#edit-academic-year').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your academic year will be edited. You may have disorders or deficiencies in other parts!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });
    } else if (fullPath.includes('searchUsers')) {
        pageTitle = 'Search users';
    } else if (fullPath.includes('AcademicYearClasses')) {
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
                    let selectLevel = $('#level');
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
    } else if (fullPath.includes('Documents')) {
        pageTitle = 'Documents';
        const images = []; // Array to store image URLs
        let currentIndex = 0; // Variable to track the current image index
        resetFields();
        $('#create-document').submit(function (e) {
            e.preventDefault();
            if ($('#document_type').val() === null) {
                swalFire('Error', "Select document type!", 'error', 'Try again');
            } else if ($('#document_file').val() == '') {
                swalFire('Error', "Select document file!", 'error', 'Try again');
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Your document will be added permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#defaultModal').hide();
                        $(".page-spinner").show();
                        let form = $(this);
                        let formData = new FormData(form[0]);
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
            }
        });
        $('#create-document-for-user').submit(function (e) {
            e.preventDefault();
            if ($('#document_type').val('')) {
                swalFire('Error', "Select document type!", 'error', 'Try again');
            } else if ($('#document_file').val() == '') {
                swalFire('Error', "Select document file!", 'error', 'Try again');
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'User\'s document will be added permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#defaultModal').hide();
                        $(".page-spinner").show();
                        let form = $(this);
                        let formData = new FormData(form[0]);
                        let currentUrl = window.location.href;
                        let parts = currentUrl.split('/');
                        let urlLastPart = parts[parts.length - 1];
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
            }
        });

        $('.type-filter').click(function () {
            let typeId = $(this).data('type-id');

            $(this).addClass('text-blue-700 hover:text-white border border-blue-600 bg-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300');

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

    } else if (fullPath.includes('EducationYears')) {
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
                    $(this).off('submit');
                    $(this).submit();
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
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });

    } else if (fullPath.includes('EducationTypes')) {
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
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });

    } else if (fullPath.includes('ApplicationTimings')) {
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
                    let selectFirstInterviewer = $('#first_interviewer');
                    selectFirstInterviewer.empty();
                    let selectSecondInterviewer = $('#second_interviewer');
                    selectSecondInterviewer.empty();
                    resetAllInputValues();
                    $.each(response, function (index, Interviewer) {
                        selectFirstInterviewer.append('<option value="' + Interviewer.id + '">' + Interviewer.general_information_info.first_name_en + ' ' + Interviewer.general_information_info.last_name_en + '( ' + Interviewer.general_information_info.first_name_fa + ' ' + Interviewer.general_information_info.last_name_fa + ' )' + '</option>');
                    });
                    $.each(response, function (index, Interviewer) {
                        selectSecondInterviewer.append('<option value="' + Interviewer.id + '">' + Interviewer.general_information_info.first_name_en + ' ' + Interviewer.general_information_info.last_name_en + '( ' + Interviewer.general_information_info.first_name_fa + ' ' + Interviewer.general_information_info.last_name_fa + ' )' + '</option>');
                    });
                }, error: function (xhr, textStatus, errorThrown) {
                    swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                }
            });

            //Set app start and end date min and max attributes
            // $.ajax({
            //     type: 'GET',
            //     url: '/GetAcademicYearStarttimeAndEndtime',
            //     data: {
            //         academic_year: $(this).val()
            //     },
            //     headers: {
            //         'X-CSRF-TOKEN': $(csrf_token).attr('content'),
            //     }, success: function (response) {
            //         $("#start_date, #end_date").prop({
            //             "min": response.start_date,
            //             "max": response.end_date
            //         });
            //     }, error: function (xhr, textStatus, errorThrown) {
            //         swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
            //     }
            // });
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
                    $(this).off('submit');
                    $(this).submit();
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
                    $(this).off('submit');
                    $(this).submit();
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
                    $(this).off('submit');
                    $(this).submit();
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
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });
    } else if (fullPath.includes('PrepareToPayApplication')) {
        pageTitle = 'Pay Application';
        resetAllSelectValues();
        resetAllInputValues();

        let dateTimeString = document.getElementById('deadline').innerText;
        let now = new Date();
        let deadline = new Date(dateTimeString);
        let remainingTime = (deadline - now) / 1000;

        function countdownTimer() {
            let hours = Math.floor(remainingTime / 3600);
            let minutes = Math.floor((remainingTime % 3600) / 60);
            let seconds = Math.floor(remainingTime % 60);

            let timerElement = document.getElementById('timer');
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
                $('#document_file').attr('required', true);
            } else if ($(this).val() === '2') {
                $('#offline_payment_div').hide();
                $('#online_payment_iran_div').show();
                $('#online_payment_paypal_div').hide();
                $('#document_file').attr('required', false);
            } else if ($(this).val() === '3') {
                $('#offline_payment_div').hide();
                $('#online_payment_iran_div').hide();
                $('#online_payment_paypal_div').show();
                $('#document_file').attr('required', false);
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
                    imagePreview.css('height', '300px');
                    imagePreview.css('width', '400px');
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
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });

    } else if (fullPath.includes('Applications')) {
        pageTitle = 'Applications';

        if (fullPath.includes('Applications/create')) {
            resetAllSelectValues();
            $('#student').change(function () {
                $('#level').val('');
            });
            $('#level').change(function () {
                $.ajax({
                    type: 'GET',
                    url: '/GetAcademicYearsByLevel',
                    data: {
                        level: $(this).val(),
                        student: $('#student').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        let selectAcademicYear = $('#academic_year');
                        selectAcademicYear.empty();
                        let selectDateAndTime = $('#date_and_time');
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
                        if (response != null) {
                            let selectDateAndTime = $('#date_and_time');
                            selectDateAndTime.empty();

                            selectDateAndTime.append('<option selected disabled value="">Select date and time</option>');

                            $.each(response, function (index, date_and_time) {
                                selectDateAndTime.append('<option value="' + date_and_time.id + '">' + date_and_time.date + " - " + date_and_time.start_from + " - " + date_and_time.ends_to + '</option>');
                            });
                            $('.AgreementDIV').show();
                        } else {
                            swalFire('Warning', "No capacity was found for this academic year!", 'warning', 'OK');
                        }
                    }, error: function (xhr, textStatus, errorThrown) {
                        $('.AgreementDIV').hide();
                        swalFire('Warning', JSON.parse(xhr.responseText).error, 'warning', 'OK');
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: '/GetEducationalCharter',
                    data: {
                        academic_year: $(this).val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        $('.CharterText').val(response);
                        $('.AgreementDIV').show();
                    }, error: function (xhr, textStatus, errorThrown) {
                        $('.AgreementDIV').hide();
                        swalFire('Error', 'Error on Getting Educational Charter Text', 'error', 'Try again');
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: '/GetFinancialCharterFile',
                    data: {
                        academic_year: $(this).val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                    }, success: function (response) {
                        if (response) {
                            $('#financial_charter_file').attr('href', response);
                        } else {
                            $('.AgreementDIV').hide();
                        }
                    }, error: function (xhr, textStatus, errorThrown) {
                        $('.AgreementDIV').hide();
                        swalFire('Error', 'Error on Getting Financial Charter File', 'error', 'Try again');
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
                        swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
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
                        $(this).off('submit');
                        $(this).submit();
                    }
                });
            });
        }

    } else if (fullPath.includes('roles')) {
        pageTitle = 'Roles';
    } else if (fullPath.includes('Schools')) {
        pageTitle = 'Schools';
    } else if (fullPath.includes('SearchStudentApplianceStatuses')) {
        pageTitle = 'Search In  Student Appliance Statuses';
    } else if (fullPath.includes('Students')) {
        pageTitle = 'Students';

        if (fullPath.includes('Students/create')) {
            $('#first_name_en,#last_name_en').on('keyup', function (event) {
                // Validate input
                if (!checkEnglishCharacters(event)) {
                    event.preventDefault(); // Prevent typing of unauthorized character
                    //Remove all field values
                    $(this).val('');
                    // Display an error message using swalFire
                    swalFire('Error', 'Your entry must contain English characters.', 'error', 'Try again');
                }
            });
            $('#first_name_fa,#last_name_fa').on('keyup', function (event) {
                // Validate input
                if (!checkPersianCharacters(event)) {
                    event.preventDefault(); // Prevent typing of unauthorized character
                    //Remove all field values
                    $(this).val('');
                    // Display an error message using swalFire
                    swalFire('Error', 'Your entry must contain Persian characters.', 'error', 'Try again');
                }
            });

            $('#birthdate').on('change', function (event) {
                // Validate input
                if (!checkAge($(this).val(), 4)) {
                    event.preventDefault(); // Prevent typing of unauthorized character
                    //Remove all field values
                    $(this).val('');
                    // Display an error message using swalFire
                    swalFire('Error', 'Your student must be over four years old.', 'error', 'Try again');
                }
            });

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
                        spinner();
                        $(this).off('submit');
                        $(this).submit();
                    }
                });
            });
        }

        $('#student_personal_picture_form').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You can only choose student's personal photo once",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });
    } else if (fullPath.includes('Interviews')) {
        pageTitle = 'Interviews';
        $(document).ready(function () {
            // Function for converting Persian numbers to English
            function convertToEnglish(input) {
                var value = input.value;
                var newValue = '';

                // Convert each Persian number to its English equivalent
                for (var i = 0; i < value.length; i++) {
                    var charCode = value.charCodeAt(i);
                    if (charCode >= 1776 && charCode <= 1785) {
                        // Convert Persian number to English by subtracting 1728 from the character code
                        newValue += String.fromCharCode(charCode - 1728);
                    } else {
                        // If the character is not a Persian number, it remains unchanged
                        newValue += value[i];
                    }
                }

                // Set the new value in the input field
                input.value = newValue;
            }

            // When the content of an input field changes
            $('input, textarea').on('input', function () {
                // Call the conversion function for the current value
                convertToEnglish(this);
            });
        });
        $('#update-interview').submit(function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Your interview will be submit.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });

            // function getDiscountDetail(id) {
            //     return new Promise(function (resolve, reject) {
            //         $.ajax({
            //             type: 'GET',
            //             url: '/GetDiscountPercentage',
            //             data: {
            //                 discount_id: id,
            //             },
            //             success: function (response) {
            //                 resolve(response);
            //             },
            //             error: function (xhr, textStatus, errorThrown) {
            //                 reject('Unprocessable Entity');
            //             }
            //         });
            //     });
            // }
            //
            // if ($('.discount-checks:checked').length > 0) {
            //     let promises = [];
            //
            //     $('.discount-checks:checked').each(function () {
            //         promises.push(getDiscountDetail($(this).val()));
            //     });
            //
            //     Promise.all(promises).then(function (responses) {
            //         let totalDiscounts = 0;
            //         responses.forEach(function (response) {
            //             totalDiscounts += parseInt(response);
            //         });
            //         if (totalDiscounts > 30) {
            //             swalFire('Error', "The discount amount selected by you is higher than 30%", 'error', 'Try again');
            //         } else {
            //             Swal.fire({
            //                 title: 'Are you sure?',
            //                 text: 'Your interview will be submit.',
            //                 icon: 'warning',
            //                 showCancelButton: true,
            //                 cancelButtonText: 'No',
            //                 confirmButtonText: 'Yes',
            //             }).then((result) => {
            //                 if (result.isConfirmed) {
            //                     $(this).off('submit');
            //                     $(this).submit();
            //                 }
            //             });
            //         }
            //     }).catch(function (error) {
            //         swalFire('Error', error, 'error', 'Try again');
            //     });
            // } else {
            //     Swal.fire({
            //         title: 'Are you sure?',
            //         text: 'Your interview will be submit.',
            //         icon: 'warning',
            //         showCancelButton: true,
            //         cancelButtonText: 'No',
            //         confirmButtonText: 'Yes',
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             $(this).off('submit');
            //             $(this).submit();
            //         }
            //     });
            // }

        });
        $('.submit-absence').click(function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This operation cannot be reversed.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });
    } else if (fullPath.includes('SetInterview')) {
        pageTitle = 'Set Interview';

        $(document).ready(function () {
            // Function for converting Persian numbers to English
            function convertToEnglish(input) {
                var value = input.value;
                var newValue = '';

                // Convert each Persian number to its English equivalent
                for (var i = 0; i < value.length; i++) {
                    var charCode = value.charCodeAt(i);
                    if (charCode >= 1776 && charCode <= 1785) {
                        // Convert Persian number to English by subtracting 1728 from the character code
                        newValue += String.fromCharCode(charCode - 1728);
                    } else {
                        // If the character is not a Persian number, it remains unchanged
                        newValue += value[i];
                    }
                }

                // Set the new value in the input field
                input.value = newValue;
            }

            // When the content of an input field changes
            $('input, textarea').on('input', function () {
                // Call the conversion function for the current value
                convertToEnglish(this);
            });
        });

        $('#set-interview').submit(function (e) {
            e.preventDefault();

            function getDiscountDetail(id) {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        type: 'GET',
                        url: '/GetDiscountPercentage',
                        data: {
                            discount_id: id,
                        },
                        success: function (response) {
                            resolve(response);
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            reject('Unprocessable Entity');
                        }
                    });
                });
            }

            if ($('.discount-checks:checked').length > 0) {
                let promises = [];

                $('.discount-checks:checked').each(function () {
                    promises.push(getDiscountDetail($(this).val()));
                });

                Promise.all(promises).then(function (responses) {
                    let totalDiscounts = 0;
                    responses.forEach(function (response) {
                        totalDiscounts += parseInt(response);
                    });
                    // if (totalDiscounts > 30) {
                    //     swalFire('Error', "The discount amount selected by you is higher than 30%", 'error', 'Try again');
                    // }
                    // else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Your interview will be submit.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).off('submit');
                            $(this).submit();
                        }
                    });
                    // }
                }).catch(function (error) {
                    swalFire('Error', error, 'error', 'Try again');
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Your interview will be submit.',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).off('submit');
                        $(this).submit();
                    }
                });
            }

        });

    } else if (fullPath.includes('ReservationInvoices')) {
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
                        swalFire('Error', xhr.responseText, 'error', 'Try again');
                    }
                });
            }
        });
    } else if (fullPath.includes('ConfirmApplication')) {
        pageTitle = 'Application Confirmation';
        if (fullPath.includes('ConfirmApplication/')) {
            pageTitle = 'Interview Form';

            let sumOfPoints = null;
            switch ($('#form-type').val()) {
                case 'kg':
                    sumOfPoints = parseInt($('#s2_total').text()) +
                        parseInt($('#s3_total').text()) +
                        parseInt($('#s4_total').text()) +
                        parseInt($('#s5_total').text());

                    if ($('#s1_1_s').val() == 'Rejected' || $('#s1_2_s').val() == 'Rejected' || $('#s1_3_s').val() == 'Rejected' || $('#s1_4_s').val() == 'Rejected') {
                        $('#interview-result').text('Rejected');
                    } else {
                        if (sumOfPoints < 60) {
                            $('#interview-result').text('Rejected');
                        } else {
                            $('#interview-result').text('Admitted');
                        }
                    }
                    break;
                case 'l':
                    sumOfPoints = parseInt($('#s2_total').text()) +
                        parseInt($('#s3_total').text()) +
                        parseInt($('#s4_total').text()) +
                        parseInt($('#s5_total').text());

                    if ($('#s1_1_s').val() == 'Inadmissible' || $('#s1_2_s').val() == 'Inadmissible' || $('#s1_3_s').val() == 'Inadmissible') {
                        $('#interview-result').text('Rejected');
                    } else {
                        if ((sumOfPoints / 2) < 60) {
                            $('#interview-result').text('Rejected');
                        } else {
                            $('#interview-result').text('Admitted');
                        }
                    }
                    break;
                default:
                    sumOfPoints = 0;
            }
            $('#sum-of-points').text(sumOfPoints);
        }

        $('.confirm-appliance').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your confirmation will be set permanently.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });

    } else if (fullPath.includes('ConfirmEvidences')) {
        pageTitle = 'Evidence Confirmation';

        const images = []; // Array to store image URLs
        let currentIndex = 0; // Variable to track the current image index

        $('.show-image').click(function () {
            let imageSrc = $(this).data('image-src');
            console.log(imageSrc);
            $('#image-for-show').attr('src', imageSrc);
        });

        // Function to show the image at the given index
        const showImage = index => {
            $('#image-for-show').attr('src', images[index]);
            let imageTitle = $('[data-image-src="' + images[index] + '"]').data('image-title');
            $('.DocumentTitle').text(imageTitle);
            currentIndex = index;
        };

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

        $('.confirm-evidences').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your confirmation will be set permanently.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });

    } else if (fullPath.includes('EditUploadedEvidences')) {
        pageTitle = 'Edit Documents';

        const images = []; // Array to store image URLs
        let currentIndex = 0; // Variable to track the current image index
        //
        $('.show-image').click(function () {
            let imageSrc = $(this).data('image-src');
            $('#image-for-show').attr('src', imageSrc);
        });

        // Function to show the image at the given index
        const showImage = index => {
            $('#image-for-show').attr('src', images[index]);
            let imageTitle = $('[data-image-src="' + images[index] + '"]').data('image-title');
            $('.DocumentTitle').text(imageTitle);
            currentIndex = index;
        };

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
        $('#relationship').change(function () {
            if ($(this).val() == 3) {
                $('#relation-name-div').show();
                $('#relation_name').attr('required', true);
            } else {
                $('#relation-name-div').hide();
                $('#relation_name').attr('required', false);
            }
        });

        $('#father_passport_file').change(function () {
            const fileInput = $('#father_passport_file');
            const imagePreview = $('#father_passport_file_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const file = fileInput[0].files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (file.type === 'application/pdf') {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.attr('alt', 'PDF File Chosen!');
                    } else {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.removeAttr('alt');
                    }
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.css('display', 'none');
                imagePreview.removeAttr('alt');
            }
        });

        $('#mother_passport_file').change(function () {
            const fileInput = $('#mother_passport_file');
            const imagePreview = $('#mother_passport_file_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const file = fileInput[0].files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (file.type === 'application/pdf') {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.attr('alt', 'PDF File Chosen!');
                    } else {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.removeAttr('alt');
                    }
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.css('display', 'none');
                imagePreview.removeAttr('alt');
            }
        });

        $('#student_passport_file').change(function () {
            const fileInput = $('#student_passport_file');
            const imagePreview = $('#student_passport_file_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const file = fileInput[0].files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (file.type === 'application/pdf') {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.attr('alt', 'PDF File Chosen!');
                    } else {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.removeAttr('alt');
                    }
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.css('display', 'none');
                imagePreview.removeAttr('alt');
            }
        });

        $('#residence_document_file').change(function () {
            const fileInput = $('#residence_document_file');
            const imagePreview = $('#residence_document_file_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const file = fileInput[0].files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (file.type === 'application/pdf') {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.attr('alt', 'PDF File Chosen!');
                    } else {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.removeAttr('alt');
                    }
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.css('display', 'none');
                imagePreview.removeAttr('alt');
            }
        });

        $('.confirm-evidences').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your confirmation will be set permanently.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });

    } else if (fullPath.includes('StudentStatuses')) {
        pageTitle = 'Student Statuses';

        $('.extension_document').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your confirmation will be set permanently.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });
    } else if (fullPath.includes('ShowApplianceInvoices')) {
        pageTitle = 'Appliance Tuition Invoices';
    } else if (fullPath.includes('Evidences/show')) {
        pageTitle = 'Uploaded Student Documents';
    } else if (fullPath.includes('Tuition') && !fullPath.includes('TuitionsStatus') && !fullPath.includes('PayTuition') && !fullPath.includes('TuitionInvoices')) {
        pageTitle = 'Tuition Manager';

        //Find all elements on the page
        let allElements = document.getElementsByTagName("*");

        // Loop through each element
        for (let i = 0; i < allElements.length; i++) {
            let element = allElements[i];

            // Check if the current element is of type number
            if (element.tagName === "INPUT" && element.type === "text") {
                // Convert the number to a formatted number with comma separators for every three digits
                // Set the new value to the current element
                element.value = formatNumber(element.value);
            }
        }

        // For each text input, when its value changes, call the formatNumber function
        $('input[type="text"]').on('input', function () {
            $(this).val(formatNumber($(this).val()));
        });


        $('.tuition-details').submit(function (e) {
            e.preventDefault();
            let form = $(this);
            let data = form.serialize();

            $.ajax({
                type: 'POST',
                url: '/ChangeTuitionPrice',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                }, success: function (response) {
                    swalFire('Done', response.message, 'success', 'Ok');
                }, error: function (xhr, textStatus, errorThrown) {
                    // Parse the JSON response to access error data
                    var errorData = JSON.parse(xhr.responseText);

                    // Access the first error message
                    var firstErrorMessage = '';
                    var foundFirstError = false; // Flag to track whether the first error is found

                    for (var key in errorData.errors) {
                        if (errorData.errors.hasOwnProperty(key) && !foundFirstError) {
                            firstErrorMessage = errorData.errors[key];
                            foundFirstError = true; // Set the flag to true to stop the loop
                        }
                    }

                    // Display the first error message
                    swalFire('Error', firstErrorMessage, 'error', 'Try again');
                }
            });
        });


    } else if (fullPath.includes('PayTuition')) {
        pageTitle = 'Prepare To Pay Tuition';

        $('#payment_type,#payment_method').val('');
        $('#document_file_full_payment1,#document_file_full_payment_with_advance1,#document_file_offline_installment1').val('');
        $('#document_file_full_payment2,#document_file_full_payment_with_advance2,#document_file_offline_installment2').val('');
        $('#document_file_full_payment3,#document_file_full_payment_with_advance3,#document_file_offline_installment3').val('');

        $('#payment_type,#payment_method').change(function () {
            $('#full-payment-div, #full-payment-invoice, #offline-full-payment-div, #full-payment-online').hide();
            $('#installment2-div, #installment2-payment-invoice, #installment2-online').hide();
            $('#installment4-div, #installment4-payment-invoice, #installment4-online').hide();
            $('#offline-full-payment-div, #offline-installment-div').hide();
            $('#full-payment-with-advance-div, #full-payment-with-advance-invoice, #offline-full-payment-with-advance-div, #full-payment-with-advance-online').hide();
            $('#accept-div').addClass('hidden');
            $('#payment-button').hide();

        });
        $('.get-invoice').on('click', function () {
            let paymentMethod = $('#payment_method').val();

            $('#full-payment-div, #full-payment-invoice, #offline-full-payment-div, #full-payment-online').hide();
            $('#installment2-div, #installment2-payment-invoice, #installment2-online').hide();
            $('#installment4-div, #installment4-payment-invoice, #installment4-online').hide();
            $('#offline-full-payment-div, #offline-installment-div').hide();
            $('#full-payment-with-advance-div, #full-payment-with-advance-invoice, #offline-full-payment-with-advance-div, #full-payment-with-advance-online').hide();

            $('#accept-div').addClass('hidden');
            $('#payment-button').hide();

            $('#document_file_full_payment1,#document_file_offline_installment1,#document_file_full_payment_with_advance1').attr('required', false);

            let paymentType = $('#payment_type').val();

            // Hide all elements initially
            $('#full-payment-div, #full-payment-invoice, #offline-full-payment-div, #full-payment-online, #installment2-div, #installment2-payment-invoice, #installment2-online, #installment4-div, #installment4-payment-invoice, #installment4-online, #offline-full-payment-div, #offline-installment-div').hide();

            if (paymentType != null && paymentMethod != null) {
                // Show elements based on payment type
                switch (paymentType) {
                    case '1':
                        $('#full-payment-div, #full-payment-invoice').show();
                        break;
                    case '2':
                        $('#installment2-div, #installment2-payment-invoice').show();
                        break;
                    case '3':
                        $('#installment4-div, #installment4-payment-invoice').show();
                        break;
                    case '4':
                        $('#full-payment-with-advance-div, #full-payment-with-advance-invoice').show();
                        break;
                }

                // Show or hide elements based on payment method
                switch (paymentMethod) {
                    case '1':
                        switch (paymentType) {
                            case '1':
                                $('#offline-full-payment-div').show();
                                $('#full-payment-online').hide();
                                $('#document_file_full_payment1').attr('required', true);
                                break;
                            case '2':
                                $('#offline-installment-div').show();
                                $('#installment2-online').hide();
                                $('#document_file_offline_installment1').attr('required', true);
                                break;
                            case '3':
                                $('#offline-installment-div').show();
                                $('#installment4-online').hide();
                                $('#document_file_offline_installment1').attr('required', true);
                                break;
                            case '4':
                                $('#offline-full-payment-with-advance-div').show();
                                $('#full-payment-with-advance-online').hide();
                                $('#document_file_full_payment_with_advance1').attr('required', true);
                                break;
                        }
                        break;
                    case '2':
                        switch (paymentType) {
                            case '1':
                                $('#offline-full-payment-div').hide();
                                $('#full-payment-online').show();
                                break;
                            case '2':
                                $('#offline-installment-div').hide();
                                $('#installment2-online').show();
                                break;
                            case '3':
                                $('#offline-installment-div').hide();
                                $('#installment4-online').show();
                                break;
                            case '4':
                                $('#offline-full-payment-with-advance-div').hide();
                                $('#full-payment-with-advance-online').show();
                                break;
                        }
                        break;
                }

                $('#accept-div').removeClass('hidden');
                $('#payment-button').show();
            } else {
                swalFire('Error', 'Please select both of fields.', 'error', 'Try again');
            }
        });

        $('#document_file_full_payment1').change(function () {
            const fileInput = $('#document_file_full_payment1');
            const imagePreview = $('#image_preview_full_payment1');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                    imagePreview.css('height', '400px');
                    imagePreview.css('width', 'full');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });
        $('#document_file_full_payment2').change(function () {
            const fileInput = $('#document_file_full_payment2');
            const imagePreview = $('#image_preview_full_payment2');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                    imagePreview.css('height', '400px');
                    imagePreview.css('width', 'full');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });
        $('#document_file_full_payment3').change(function () {
            const fileInput = $('#document_file_full_payment3');
            const imagePreview = $('#image_preview_full_payment3');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                    imagePreview.css('height', '400px');
                    imagePreview.css('width', 'full');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });

        $('#document_file_full_payment_with_advance1').change(function () {
            const fileInput = $('#document_file_full_payment_with_advance1');
            const imagePreview = $('#image_preview_full_payment_with_advance_1');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                    imagePreview.css('height', '400px');
                    imagePreview.css('width', 'full');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });
        $('#document_file_full_payment_with_advance2').change(function () {
            const fileInput = $('#document_file_full_payment_with_advance2');
            const imagePreview = $('#image_preview_full_payment_with_advance_2');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                    imagePreview.css('height', '400px');
                    imagePreview.css('width', 'full');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });
        $('#document_file_full_payment_with_advance3').change(function () {
            const fileInput = $('#document_file_full_payment_with_advance3');
            const imagePreview = $('#image_preview_full_payment_with_advance_2');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                    imagePreview.css('height', '400px');
                    imagePreview.css('width', 'full');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });

        $('#document_file_offline_installment1').change(function () {
            const fileInput = $('#document_file_offline_installment1');
            const imagePreview = $('#image_preview_offline_installment1');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                    imagePreview.css('height', '400px');
                    imagePreview.css('width', 'full');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });
        $('#document_file_offline_installment2').change(function () {
            const fileInput = $('#document_file_offline_installment2');
            const imagePreview = $('#image_preview_offline_installment2');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                    imagePreview.css('height', '400px');
                    imagePreview.css('width', 'full');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });
        $('#document_file_offline_installment3').change(function () {
            const fileInput = $('#document_file_offline_installment3');
            const imagePreview = $('#image_preview_offline_installment3');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.css('display', 'block');
                    imagePreview.css('height', '400px');
                    imagePreview.css('width', 'full');
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.css('display', 'none');
            }
        });

        $('#pay-tuition').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'After confirmation, you will be taken to the payment page.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });

    } else if (fullPath.includes('TuitionInvoices')) {
        pageTitle = 'Tuition Invoices';
        if (fullPath.includes('TuitionInvoices/')) {
            pageTitle = 'Tuition Payment Details';

            $('#set-payment-status').click(function (e) {
                e.preventDefault();
                if ($('#payment_status').val() == 0) {
                    swalFire('Error', "You can't change status to this!", 'error', 'Try again');
                } else if ($('#payment_status').val() == 1) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'If you set the status to Approved, installments will be made for parents. This state is irreversible. are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: '/TuitionInvoices/ChangeInvoiceStatus',
                                data: {
                                    invoice_id: $('#invoice_id').val(),
                                    status: $('#payment_status').val()
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                                }, success: function (response) {
                                    swalFire('Done', JSON.parse(response).message, 'success', 'Ok');
                                }, error: function (xhr, textStatus, errorThrown) {
                                    swalFire('Error', JSON.parse(xhr.responseText).message, 'error', 'Try again');
                                }
                            });
                        }
                    });
                } else if ($('#payment_status').val() == 2) {
                    swalFire('Error', "You can't change status to this!", 'error', 'Try again');
                }

            });
        }
    } else if (fullPath.includes('TuitionsStatus')) {
        pageTitle = 'Tuitions Status';

    } else if (fullPath.includes('UploadStudentDocumentByParent')) {
        pageTitle = 'Upload Student\'s Documents';

        $('#relationship').change(function () {
            if ($(this).val() == 3) {
                $('#relation-name-div').show();
                $('#relation_name').attr('required', true);
            } else {
                $('#relation-name-div').hide();
                $('#relation_name').attr('required', false);
            }
        });

        $('#father_passport_file').change(function () {
            const fileInput = $('#father_passport_file');
            const imagePreview = $('#father_passport_file_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const file = fileInput[0].files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (file.type === 'application/pdf') {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.attr('alt', 'PDF File Chosen!');
                    } else {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.removeAttr('alt');
                    }
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.css('display', 'none');
                imagePreview.removeAttr('alt');
            }
        });

        $('#mother_passport_file').change(function () {
            const fileInput = $('#mother_passport_file');
            const imagePreview = $('#mother_passport_file_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const file = fileInput[0].files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (file.type === 'application/pdf') {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.attr('alt', 'PDF File Chosen!');
                    } else {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.removeAttr('alt');
                    }
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.css('display', 'none');
                imagePreview.removeAttr('alt');
            }
        });

        $('#student_passport_file').change(function () {
            const fileInput = $('#student_passport_file');
            const imagePreview = $('#student_passport_file_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const file = fileInput[0].files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (file.type === 'application/pdf') {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.attr('alt', 'PDF File Chosen!');
                    } else {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.removeAttr('alt');
                    }
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.css('display', 'none');
                imagePreview.removeAttr('alt');
            }
        });

        $('#residence_document_file').change(function () {
            const fileInput = $('#residence_document_file');
            const imagePreview = $('#residence_document_file_preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                const file = fileInput[0].files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (file.type === 'application/pdf') {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.attr('alt', 'PDF File Chosen!');
                    } else {
                        imagePreview.attr('src', e.target.result);
                        imagePreview.css('display', 'block');
                        imagePreview.css('height', '200px');
                        imagePreview.css('width', '400px');
                        imagePreview.removeAttr('alt');
                    }
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.css('display', 'none');
                imagePreview.removeAttr('alt');
            }
        });

        $('#upload-student-documents').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your documents will be added permanently.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });

    } else if (fullPath.includes('Discounts')) {
        pageTitle = 'Discounts Manager';

        $('#discounts-table').on('click', '.delete-row', function () {
            $(this).closest('tr').remove();
        });

        $('.add-row').on('click', function () {
            let newRow = '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">' +
                '<td class="p-4">' +
                '<input type="text" name="name[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>' +
                '</td>' +
                '<td class="p-4">' +
                '<div class="flex justify-between">' +
                '<input type="number" min="0" max="100" name="percentage[]" class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price" required>' +
                '<span class = "inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600" > '
                + '%' +
                '</span>' +
                '</div>' +
                '</td>' +
                '<td class="p-4 text-center">' +
                '<select name="display_in_form[]" id="display_in_form" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">' +
                '<option value="1">Display on interview form</option>' +
                '<option value="0">Dont display on interview form</option>' +
                '</select>' +
                '</td>' +
                '<td class="p-4 text-center">' +
                '<button type="button" class="delete-row text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-2 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">' +
                '<i class="las la-trash" style="font-size: 24px"></i>' +
                '</button>' +
                '</td>' +
                '</tr>';

            $('tbody').append(newRow);
        });

        $('#discounts-edit').submit(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Your discounts will be set permanently.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('submit');
                    $(this).submit();
                }
            });
        });
    } else {
        switch (fullPath) {
            case '/dashboard':
                spinner();
                pageTitle = 'Dashboard';
                break;
            case '/Profile':
                pageTitle = 'Profile';

                // Event handler for input event on elements with IDs first_name_fa and last_name_fa
                $('#first_name_fa, #last_name_fa').on('keyup', function (event) {
                    // Validate input using the checkPersianCharacters function
                    if (!checkPersianCharacters(event)) {
                        // If input is not valid, prevent typing of unauthorized character
                        event.preventDefault();
                        //Remove all field values
                        $(this).val('');
                        // Display an error message using swalFire
                        swalFire('Error', 'Your entry must contain Persian characters.', 'error', 'Try again');
                    }
                });

                $('#father-name,#city').on('keyup', function (event) {
                    // Validate input
                    if (!checkEnglishCharacters(event)) {
                        event.preventDefault(); // Prevent typing of unauthorized character
                        //Remove all field values
                        $(this).val('');
                        // Display an error message using swalFire
                        swalFire('Error', 'Your entry must contain English characters.', 'error', 'Try again');
                    }
                });

                $('#address').on('keyup', function (event) {
                    // Validate input
                    if (!validateAddressEntry(event)) {
                        event.preventDefault(); // Prevent typing of unauthorized character
                        //Remove all field values
                        $(this).val('');
                        // Display an error message using swalFire
                        swalFire('Error', 'Your entry must contain English characters and - or _', 'error', 'Try again');
                    }
                });

                // $('#Birthdate').on('change', function (event) {
                //     // Validate input
                //     if (!checkAge($(this).val())) {
                //         event.preventDefault(); // Prevent typing of unauthorized character
                //         //Remove all field values
                //         $(this).val('');
                //         // Display an error message using swalFire
                //         swalFire('Error', 'You must be over fifteen years old.', 'error', 'Try again');
                //     }
                // });

                $("#postal-code,#faragir-code,#passport-number").on('input', function (event) {
                    // Validate input
                    if (!checkEnglishDigits($(this).val())) {
                        event.preventDefault(); // Prevent typing of unauthorized character
                        //Remove all field values
                        $(this).val('');
                        // Display an error message using swalFire
                        swalFire('Error', 'Your entry must contain numbers.', 'error', 'Try again');
                    }
                });

                break;
        }
    }
    $('#page-title').text(pageTitle + ' | Savior Schools');
});
