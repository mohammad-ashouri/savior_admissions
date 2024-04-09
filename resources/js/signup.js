import './bootstrap';
// import '@fortawesome/fontawesome-free/css/all.css';
import $ from 'jquery';
import Swal from 'sweetalert2';

window.Swal = Swal;

function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, text: text, icon: icon, confirmButtonText: confirmButtonText,
    });
}

function reloadCaptcha() {
    var captchaImg = document.getElementById('captchaImg');
    var captchaUrl = "/captcha";
    captchaImg.src = captchaUrl + '?' + Date.now();
}

$(document).ready(function () {
    var fullPath = window.location.pathname;
    if (fullPath.includes('/create-account')) {
        // Hide the initial two divs
        $('#email').closest('div').hide();
        $('#mobile').closest('div').hide();

        // When the select option changes
        $('#signup-method').change(function () {
            // If Mobile option is selected
            if ($(this).val() === 'Mobile') {
                // Show the mobile div and hide the email div
                $('#mobile').closest('div').show();
                $('#email').closest('div').hide();
            }
            // If Email option is selected
            else if ($(this).val() === 'Email') {
                // Show the email div and hide the mobile div
                $('#email').closest('div').show();
                $('#mobile').closest('div').hide();
            }
            // If no option is selected
            else {
                // Hide both divs
                $('#email').closest('div').hide();
                $('#mobile').closest('div').hide();
            }
        });

        // When the form submitted
        $('#signup').submit(function (e) {
            e.preventDefault();
            switch ($('#signup-method').val()) {
                case null:
                    swalFire('Error', "The signup method is not selected", 'error', 'Try again');
                    reloadCaptcha();
                    break;
                case 'Email':
                    if ($('#email').val() == '') {
                        swalFire('Error', "Email field is empty!", 'error', 'Try again');
                        reloadCaptcha();
                    }
                    break;
                case 'Mobile':
                    if ($('#mobile').val() == '') {
                        swalFire('Error', "Mobile field is empty!", 'error', 'Try again');
                        reloadCaptcha();
                    }
                    break;
            }
            if ($('#captcha').val() == '') {
                swalFire('Error', "Captcha field is empty!", 'error', 'Try again');
                reloadCaptcha();
            }
        });
    }
});

