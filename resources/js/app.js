import './bootstrap';
import 'flowbite';
import $ from 'jquery';
import Swal from 'sweetalert2';

window.Swal = Swal;

function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, html: text, icon: icon, confirmButtonText: confirmButtonText,
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

    // در صورتی که 'expanded' در 'logo-sidebar' وجود داشته باشد
    if (logoSidebar.classList.contains('expanded')) {
        // نمایش متن‌ها
        menuListItems.forEach(item => {
            item.style.display = 'inline-block';
        });

        // مخفی کردن مختصرها
        menuListHoverItems.forEach(item => {
            item.style.display = 'none';
        });
    } else {
        // در غیر این صورت مختصرها را نمایش دهید
        menuListItems.forEach(item => {
            item.style.display = 'none';
        });

        // نمایش مختصرها
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
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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
                    url: '/users/change_student_information',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }, success: function (response) {
                        swalFire('Done', response.success, 'success', 'Ok');
                    }, error: function (xhr, textStatus, errorThrown) {
                        swalFire('Error', JSON.parse(xhr.responseText).error, 'error', 'Try again');
                    }
                });
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

    } else if (fullPath.includes('DocumentTypes')) {
        pageTitle = 'Document Types Manager';
    } else if (fullPath.includes('search')) {
        pageTitle = 'Search users';
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
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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
    else if (fullPath.includes('Roles')) {
        pageTitle = 'Roles';
    } else if (fullPath.includes('Schools')) {
        pageTitle = 'Schools';
    } else {
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
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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
