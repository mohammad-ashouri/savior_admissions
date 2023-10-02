import './bootstrap';
import 'flowbite';

// open toggled sidebar

const toggleButton = document.getElementById('toggleButton');
const logoSidebar = document.getElementById('logo-sidebar');
const content = document.getElementById('content');
const menuListItems = document.querySelectorAll('.menulist');
const menuListHoverItems = document.querySelectorAll('.menulist-hover');

toggleButton.addEventListener('click', function() {
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

// در ابتدا، وضعیت اولیه را بررسی کنید و بر اساس آن مختصرها را نمایش یا مخفی کنید
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

themeToggleBtn.addEventListener('click', function() {

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
