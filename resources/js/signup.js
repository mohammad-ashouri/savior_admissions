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

function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(minutes + ":" + seconds);

        if (--timer < 0) {
            timer = duration;
            location.reload();
        }
    }, 1000);

}


$(document).ready(function () {
    let csrf_token = 'meta[name="csrf-token"]';

    var fullPath = window.location.pathname;
    if (fullPath.includes('/create-account')) {
        // Hide the initial two divs
        $('#signup-method').val('');
        $('#mobile').val('');
        $('#email').val('');
        $('#captcha').val('');
        $('#verification_code').val('');

        $('#captchaImg').click(function () {
            reloadCaptcha();
        });

        $('#email').closest('div').hide();
        $('#mobile').closest('div').hide();
        $('#phone-code').closest('div').hide();

        // When the select option changes
        $('#signup-method').change(function () {
            // If Mobile option is selected
            if ($(this).val() === 'Mobile') {
                // Show the mobile div and hide the email div
                $('#mobile').closest('div').show();
                $('#phone-code').closest('div').show();
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

        //Mobile validator
        $('#mobile').on('input', function () {
            let mobileNumber = $(this).val();
            let phone_code = $('#phone_code').val();

            if (phone_code === 101) {

            }

            // Check if the input starts with '+'
            if (mobileNumber.startsWith('+')) {
                // Remove the leading characters
                mobileNumber = mobileNumber.substring(1);
                $(this).val(mobileNumber);
            }
        });

        // When the form submitted
        $(document).on('submit', '#send-code', function (e) {
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


            var form = $(this);
            var data = form.serialize();
            $.ajax({
                type: 'POST',
                url: '/create-account',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                }, success: function (response) {
                    if (response.error) {
                        swalFire('Error', response.error, 'error', 'Try again');
                        reloadCaptcha();
                    } else if (response.status === 200) {
                        $('#signup-method').prop('readonly', true);
                        $('#phone-code').prop('readonly', true);
                        $('#mobile').prop('readonly', true);
                        $('#email').prop('readonly', true);
                        $('.CaptchaDiv').hide();
                        $('.VerificationCodeDiv').show();

                        $('#send-code').attr({
                            'id': 'authorize',
                            'action': '/authorization'
                        });

                        var twoMinutes = 120, display = $('#timer');
                        startTimer(twoMinutes, display);
                    } else if (response.timer) {
                        $('#signup-method').prop('readonly', true);
                        $('#phone-code').prop('readonly', true);
                        $('#mobile').prop('readonly', true);
                        $('#email').prop('readonly', true);
                        $('.CaptchaDiv').hide();
                        $('.VerificationCodeDiv').show();

                        $('#send-code').attr({
                            'id': 'authorize',
                            'action': '/authorization'
                        });

                        var remindedTime = response.timer, display = $('#timer');

                        startTimer(remindedTime, display);
                        $('.CaptchaDiv').hide();
                        $('.VerificationCodeDiv').show();
                    }

                }, error: function (xhr, textStatus, errorThrown) {
                    swalFire('Error', JSON.parse(xhr.responseText).error, 'error', 'Try again');
                }
            });


            // $('#signup').off('submit').submit();

        });

        $(document).on('submit', '#authorize', function (e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();
            $.ajax({
                type: 'POST',
                url: '/authorization',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $(csrf_token).attr('content'),
                }, success: function (response) {
                    if (response.error) {
                        swalFire('Error', response.error, 'error', 'Try again');
                    } else if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    }
                }, error: function (xhr, textStatus, errorThrown) {
                    swalFire('Error', xhr.responseText.message, 'error', 'Try again');
                }
            });
        });

    } else if (fullPath.includes('new-account')) {
        $('#password').val('');
        $('#repeat-password').val('');
        $('#first_name').val('');
        $('#last_name').val('');
        $('#gender').val('');
        $('#captcha').val('');
    }
});

