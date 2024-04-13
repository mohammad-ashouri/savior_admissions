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

function validateIranianMobile(mobile) {
    var iranianMobilePattern = /^(\+98|0)?9\d{9}$/;

    return iranianMobilePattern.test(mobile);
}

$(document).ready(function () {

    var fullPath = window.location.pathname;
    if (fullPath.includes('/create-account')) {
        // Hide the initial two divs
        $('#signup-method').val('');
        $('#mobile').val('');
        $('#email').val('');
        $('#captcha').val('');

        $('#captchaImg').click(function () {
            reloadCaptcha();
        });

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
            // Get the value of signup method
            let signupMethod = $('#signup-method').val();

            // Check the value of signup method
            switch (signupMethod) {
                case 'Email':
                    var email = $('#email').val();
                    if (email === '') {
                        swalFire('Error', "Email field is empty!", 'error', 'Try again');
                        reloadCaptcha();
                        return;
                    }
                    break;
                case 'Mobile':
                    var mobile = $('#mobile').val();
                    if (mobile === '') {
                        swalFire('Error', "Mobile field is empty!", 'error', 'Try again');
                        reloadCaptcha();
                        return;
                    } else if (!validateIranianMobile(mobile)) {
                        swalFire('Error', "Mobile value is not validated!", 'error', 'Try again');
                        return;
                    }
                    break;
                default:
                    swalFire('Error', "The signup method is not selected", 'error', 'Try again');
                    reloadCaptcha();
                    return;
            }

            // Check the value of captcha field
            let captcha = $('#captcha').val();
            if (captcha === '') {
                swalFire('Error', "Captcha field is empty!", 'error', 'Try again');
                reloadCaptcha();
                return;
            }

            $('#signup').off('submit').submit();

        });
    }
    else if (fullPath.includes('new-account')){
        $('#password').val('');
        $('#repeat-password').val('');
        $('#first_name').val('');
        $('#last_name').val('');
        $('#gender').val('');
        $('#captcha').val('');
    }
});

