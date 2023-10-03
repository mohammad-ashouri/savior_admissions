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

$('#login-form').submit(function (e) {
    e.preventDefault();

    var form = $(this);
    var data = form.serialize();

    $.ajax({
        type: 'POST', url: '/login', data: data, headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }, success: function (response) {
            if (response.success) {
                window.location.href = response.redirect;
            } else {
                if (response.errors.Email) {
                    swalFire('Email Error', response.errors.Email[0], 'error', 'تلاش مجدد');
                    // reloadCaptcha();
                    // captcha.value = '';
                } else if (response.errors.password) {
                    swalFire('Password', response.errors.password[0], 'error', 'تلاش مجدد');
                    // reloadCaptcha();
                    // captcha.value = '';
                }

                    // if (response.errors.captcha) {
                    //     swalFire('کد امنیتی نامعتبر', response.errors.captcha[0], 'error', 'تلاش مجدد');
                    //     reloadCaptcha();
                    //     captcha.value = '';
                // }
                else if (response.errors.loginError) {
                    swalFire('Wrong email or password', response.errors.loginError[0], 'error', 'تلاش مجدد');
                    // reloadCaptcha();
                    // captcha.value = '';
                }
            }
        }, error: function (xhr, textStatus, errorThrown) {
            if (xhr.responseJSON['YouAreLocked']) {
                swalFire('Access is forbidden', 'Your IP has been blocked. Please provide to your admin', 'error', 'تایید');
                const fields = [email, password, captcha];
                fields.forEach(field => {
                    field.disabled = true;
                    field.value = null;
                    field.style.backgroundColor = 'gray';
                });
            } else {
                swalFire('Server Error', 'Server connectivity failed', 'error', 'تلاش مجدد');
            }

        }
    });
});

