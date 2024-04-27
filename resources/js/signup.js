import $ from 'jquery';
import {
    swalFire,
    reloadCaptcha,
    validateIranianMobile,
    validatePasswordEntry,
    checkAge,
    startTimer, checkEnglishCharacters
} from './MainJsFunctionsAndImports.js';

$(document).ready(function () {
    let csrf_token = 'meta[name="csrf-token"]';

    let fullPath = window.location.pathname;
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
                    let email = $('#email').val();
                    if (email === '') {
                        swalFire('Error', "Email field is empty!", 'error', 'Try again');
                        reloadCaptcha();
                        return;
                    }
                    break;
                case 'Mobile':
                    let mobile = $('#mobile').val();
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


            let form = $(this);
            let data = form.serialize();
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
                        $('#get-code').text('submit');

                        $('#send-code').attr({
                            'id': 'authorize',
                            'action': '/authorization'
                        });

                        let twoMinutes = 120, display = $('#timer');
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

                        let remindedTime = response.timer, display = $('#timer');

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
            let form = $(this);
            let data = form.serialize();
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
        $('#password,#repeat-password,#first_name,#last_name,#gender,#birthdate').val('');

        $(document).on('submit', '#signup-form', function (e) {
            e.preventDefault();

            // Initialize error flag
            let hasError = false;

            // Validate first name and last name
            let firstNameValid = checkEnglishCharacters($("first_name").val());
            let lastNameValid = checkEnglishCharacters($("last_name").val());

            // Validate password and repeat password
            let validatePasswordInput = validatePasswordEntry('password');
            let validateRepeatPasswordInput = validatePasswordEntry('repeat-password');

            // Validate age if user age is under 15
            let birthDate = $("#birthdate").val();
            let isUnderFifteen = checkAge(birthDate);

            // Check validation results
            if (!firstNameValid || !lastNameValid || !validatePasswordInput || !validateRepeatPasswordInput || !isUnderFifteen) {
                // Set error flag
                hasError = true;

                // Display only one error message
                if (!validatePasswordInput) {
                    swalFire('Error', 'Please use only English letters, numbers, and allowed symbols in password.', 'error', 'Try again');
                } else if (!validateRepeatPasswordInput) {
                    swalFire('Error', 'Please use only English letters, numbers, and allowed symbols in repeat password.', 'error', 'Try again');
                } else if (!firstNameValid) {
                    swalFire('Error', 'Please enter english characters in first name.', 'error', 'Try again');
                    $("first_name").val("");
                } else if (!lastNameValid) {
                    swalFire('Error', 'Please enter english characters in last name.', 'error', 'Try again');
                    $("last_name").val("");
                } else if (!isUnderFifteen) {
                    swalFire('Error', 'You must be over fifteen years old.', 'error', 'Try again');
                }
            }

            if ($('#password').val() !== $('#repeat-password').val()) {
                // Set error flag
                hasError = true;

                swalFire('Error', 'Passwords are not equal.', 'error', 'Try again');
            }

            // If no error, display confirmation message
            if (!hasError) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Your information will be added permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#signup-form')[0].submit();
                    }
                });
            }
        });
    }
});

