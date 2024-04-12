import Swal from 'sweetalert2';

function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, html: text, icon: icon, confirmButtonText: confirmButtonText,
    });
}

swalFire('Failed!', 'Error on sending email! Try again later.', 'error', 'Ok');
