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
    if (fullPath.includes('/password/reset')) {
        $('#reset-password').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();
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
                $('#login-form').submit(function (e) {
                    e.preventDefault();

                    var form = $(this);
                    var data = form.serialize();

                    $.ajax({
                        type: 'POST',
                        url: '/login',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (response) {
                            if (response.success) {
                                window.location.href = response.redirect;
                            } else {
                                if (response.errors.email) {
                                    swalFire('Email Error', response.errors.email[0], 'error', 'Try again');
                                    reloadCaptcha();
                                    captcha.value = '';
                                } else if (response.errors.password) {
                                    swalFire('Password', response.errors.password[0], 'error', 'Try again');
                                    reloadCaptcha();
                                    captcha.value = '';
                                } else if (response.errors.loginError) {
                                    swalFire('Wrong email or password', response.errors.loginError[0], 'error', 'Try again');
                                    reloadCaptcha();
                                    captcha.value = '';
                                } else if (response.errors.captcha) {
                                    swalFire('Wrong captcha', response.errors.captcha[0], 'error', 'Try again');
                                    reloadCaptcha();
                                    captcha.value = '';
                                }
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            if (xhr.responseJSON && xhr.responseJSON['YouAreLocked']) {
                                swalFire('Access is forbidden', 'Your IP has been blocked. Please provide to your admin', 'error', 'Done!');
                                const fields = [email, password, captcha];
                                fields.forEach(field => {
                                    field.disabled = true;
                                    field.value = null;
                                    field.style.backgroundColor = 'gray';
                                });
                            } else {
                                swalFire('Server Error', 'Server connectivity failed', 'error', 'Try again');
                            }
                        }
                    });
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

                $('#forget-password').submit(function (e) {
                    e.preventDefault();
                    let resetOption = document.getElementById('reset-options');
                    if (resetOption.value == null || resetOption.value === '') {
                        swalFire('Error', 'Please choose an option', 'error', 'Try again');
                    } else {
                        var form = $(this);
                        var data = form.serialize();

                        $.ajax({
                            type: 'POST', url: '/password/sendToken', data: data, headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            }, success: function (response) {
                                if (response.success) {
                                    // window.location.href = response.redirect;
                                } else {
                                    if (response.errors.Email) {
                                        swalFire('Email Error', response.errors.Email[0], 'error', 'Try again');
                                    } else if (response.errors.Mobile) {
                                        swalFire('Mobile Error', response.errors.Mobile[0], 'error', 'Try again');
                                    } else if (response.errors.WrongMobile) {
                                        swalFire('Mobile Error', response.errors.WrongMobile[0], 'error', 'Try again');
                                    } else if (response.errors.WrongEmail) {
                                        swalFire('Email Error', response.errors.WrongEmail[0], 'error', 'Try again');
                                    }
                                }
                            }, error: function (xhr, textStatus, errorThrown) {
                                swalFire('Server Error', 'Server connectivity failed', 'error', 'Try again');
                            }
                        });
                    }

                });
                break;
        }
    }
});

