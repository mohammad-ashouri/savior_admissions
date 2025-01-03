import $ from 'jquery';
import {swalFire, reloadCaptcha, startTimer, spinner} from './MainJsFunctionsAndImports.js';

$(document).ready(function () {
    document.cookie.split(";").forEach(function(cookie) {
        var name = cookie.split("=")[0];
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/";
    });

    let fullPath = window.location.pathname;
    if (fullPath.includes('/password/reset')) {
        $('#reset-password').submit(function (e) {
            e.preventDefault();
            let form = $(this);
            let data = form.serialize();
            $.ajax({
                type: 'POST', url: '/password/reset', data: data, headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }, success: function (response) {
                    window.location.href = response.redirect;
                }, error: function (xhr, textStatus, errorThrown) {
                    swalFire('Error', JSON.parse(xhr.responseText).error, 'error', 'Try again');
                }
            });
        });
    } else {
        switch (fullPath) {
            case '/login':
            case '/':
                //Contact us
                $('#close-modal,#close-modal-button').click(function () {
                    $('#contact-us-modal').hide();
                });
                $('#contact-us-modal-overlay').click(function () {
                    $('#contact-us-modal').hide();
                });
                $('#show-contact-us').click(function () {
                    $('#contact-us-modal').show();
                });

                // $('#login-method').val('');
                // $('#password').val();
                $('#captcha').val('');

                // $('#email').closest('div').hide();
                // $('#mobileDiv').hide();

                // When the select option changes
                // $('#login-method').change(function () {
                //     // If Mobile option is selected
                //     if ($(this).val() === 'mobile') {
                //         // Show the mobile div and hide the email div
                //         $('#mobileDiv').show();
                //         $('#email').closest('div').hide();
                //         $('#email').val('');
                //         $('#mobile').val('');
                //         $('#password').val('');
                //     }
                //     // If Email option is selected
                //     else if ($(this).val() === 'email') {
                //         // Show the email div and hide the mobile div
                //         $('#email').closest('div').show();
                //         $('#mobileDiv').hide();
                //         $('#email').val('');
                //         $('#mobile').val('');
                //         $('#password').val('');
                //     }
                //     // If no option is selected
                //     else {
                //         // Hide both divs
                //         $('#email').closest('div').hide();
                //         $('#mobileDiv').hide();
                //         $('#email').val('');
                //         $('#mobile').val('');
                //         $('#password').val('');
                //     }
                // });

                // Find the input element
                let inputElement = document.getElementById('mobile');

                // Add an event listener for the input event
                inputElement.addEventListener('input', function (event) {
                    // Get the current value of the input
                    let value = event.target.value;

                    // Check if the value starts with a zero or a plus sign
                    if (value.charAt(0) === '0' || value.charAt(0) === '+') {
                        // Remove the zero or the plus sign
                        value = value.slice(1);

                        // Set the new value for the input
                        event.target.value = value;

                        swalFire('Error', 'Dont use 0 or + or your country code!', 'error', 'Try again'); // Print error message to console, you can change this to display in your HTML
                    }
                });


                $('#login-form').submit(function (e) {
                    e.preventDefault();
                    spinner();
                    // if ($('#login-method').val() == null) {
                    //     swalFire('Error', 'Login method is not selected!', 'error', 'Try again'); // Print error message to console, you can change this to display in your HTML
                    //     spinner();
                    // } else {
                        spinner();
                        $(this).off('submit');
                        $(this).submit();
                        // let form = $(this);
                        // let data = form.serialize();

                        // $.ajax({
                        //     type: 'POST', url: '/login', data: data, headers: {
                        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        //     }, success: function (response) {
                        //         if (response.success) {
                        //             window.location.href = '/dashboard';
                        //         } else {
                        //             if (response.validator_errors) {
                        //                 let errorList = response.validator_errors;
                        //
                        //                 // Iterate through the error list and display each error
                        //                 for (let key in errorList) {
                        //                     if (errorList.hasOwnProperty(key)) {
                        //                         let errorMessage = errorList[key][0]; // Assuming each field has only one error message
                        //                         spinner();
                        //                         swalFire('Error', errorMessage, 'error', 'Try again'); // Print error message to console, you can change this to display in your HTML
                        //                     }
                        //                 }
                        //                 reloadCaptcha();
                        //                 captcha.value = '';
                        //             } else if (response.errors) {
                        //                 if (response.errors.loginError) {
                        //                     spinner();
                        //                     swalFire('Login Error', response.errors.loginError, 'error', 'Try again');
                        //                     reloadCaptcha();
                        //                     captcha.value = '';
                        //                 } else if (response.errors.captcha) {
                        //                     spinner();
                        //                     swalFire('Error', response.errors.captcha, 'error', 'Try again');
                        //                     reloadCaptcha();
                        //                     captcha.value = '';
                        //                 }
                        //             }
                        //         }
                        //     }, error: function (xhr, textStatus, errorThrown) {
                        //         if (xhr.responseJSON && xhr.responseJSON['YouAreLocked']) {
                        //             swalFire('Access is forbidden', 'Your IP has been blocked. Please provide to your admin', 'error', 'Done!');
                        //             const fields = [email, password, captcha];
                        //             fields.forEach(field => {
                        //                 field.disabled = true;
                        //                 field.value = null;
                        //                 field.style.backgroundColor = 'gray';
                        //             });
                        //         } else {
                        //             swalFire('Server Error', 'Server connectivity failed', 'error', 'Try again');
                        //             spinner();
                        //         }
                        //     }
                        // });
                    // }

                });
                $('#captchaImg').click(function () {
                    reloadCaptcha();
                });
                break;
            case '/password/forgot':
                const selectors = document.querySelectorAll('select');
                selectors.forEach(select => select.value = "");

                $('#reset-options').change(function (e) {
                    if (this.value === 'Mobile') {
                        mobileDIV.hidden = false;
                        emailDIV.hidden = true;
                    } else if (this.value === 'Email') {
                        mobileDIV.hidden = true;
                        emailDIV.hidden = false;
                    } else {
                        mobileDIV.hidden = true;
                        emailDIV.hidden = true;
                    }
                });

                $(document).on('submit', '#forget-password', function (e) {

                    e.preventDefault();
                    spinner();
                    let resetOption = document.getElementById('reset-options');
                    if (resetOption.value == null || resetOption.value === '') {
                        spinner();
                        swalFire('Error', 'Please choose an option', 'error', 'Try again');
                    } else {
                        let form = $(this);
                        let data = form.serialize();
                        if (!$('#phone_code').val()) {
                            $('#phone_code').val('98');
                        }
                        $.ajax({
                            type: 'POST', url: '/password/sendToken', data: data, headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            }, success: function (response) {
                                if (response.success) {
                                    spinner();
                                    $('#reset-options').prop('readonly', true);
                                    $('#phone_code').prop('readonly', true);
                                    $('#mobile').prop('readonly', true);
                                    $('#email').prop('readonly', true);
                                    $('.CaptchaDiv').hide();
                                    $('.VerificationCodeDiv').show();
                                    $('#get_code').text('submit');

                                    $('#forget-password').attr({
                                        'id': 'authorize',
                                    });

                                    let twoMinutes = 120, display = $('#timer');
                                    startTimer(twoMinutes, display);
                                } else if (response.timer) {
                                    $('#reset-options').prop('readonly', true);
                                    $('#phone_code').prop('readonly', true);
                                    $('#mobile').prop('readonly', true);
                                    $('#email').prop('readonly', true);
                                    $('.CaptchaDiv').hide();
                                    $('.VerificationCodeDiv').show();
                                    $('#get_code').text('submit');

                                    $('#forget-password').attr({
                                        'id': 'authorize',
                                    });

                                    let remindedTime = response.timer, display = $('#timer');

                                    startTimer(remindedTime, display);
                                    $('.CaptchaDiv').hide();
                                    $('.VerificationCodeDiv').show();
                                    spinner();
                                } else {
                                    if (response.errors) {
                                        spinner();
                                        swalFire('Email Error', response.errors[0], 'error', 'Try again');
                                    }
                                    if (response.error) {
                                        spinner();
                                        swalFire('Error', response.error, 'error', 'Try again');
                                    }
                                }
                            }, error: function (xhr, textStatus, errorThrown) {
                                spinner();
                                swalFire('Server Error', 'Server connectivity failed', 'error', 'Try again');
                            }
                        });
                    }

                });
                $(document).on('submit', '#authorize', function (e) {
                    e.preventDefault();
                    spinner();
                    let form = $(this);
                    let data = form.serialize();

                    $.ajax({
                        type: 'POST', url: '/password/authorize', data: data, headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        }, success: function (response) {
                            if (response.success) {
                                window.location.href = response.redirect_url;
                            } else {
                                if (response.errors) {
                                    spinner();
                                    swalFire('Email Error', response.errors, 'error', 'Try again');
                                }
                                if (response.error) {
                                    spinner();
                                    swalFire('Error', response.error, 'error', 'Try again');
                                }
                            }
                        }, error: function (xhr, textStatus, errorThrown) {
                            spinner();
                            swalFire('Server Error', 'Server connectivity failed', 'error', 'Try again');
                        }
                    });
                });
                break;
        }
    }
});


