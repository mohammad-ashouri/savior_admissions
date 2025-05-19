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


