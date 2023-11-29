import './bootstrap';
import 'flowbite';
import $ from 'jquery';
import Swal from 'sweetalert2';

window.Swal = Swal;

function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, text: text, icon: icon, confirmButtonText: confirmButtonText,
    });
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
        }
    }
    switch (fullPath) {
        case '/dashboard':
            pageTitle = 'Dashboard';
            break;
        case '/users':
            pageTitle = 'User Management';
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
    $('#page-title').text(pageTitle + ' | Savior Schools');
});
