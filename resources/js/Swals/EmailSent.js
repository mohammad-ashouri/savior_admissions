import Swal from 'sweetalert2';

function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, html: text, icon: icon, confirmButtonText: confirmButtonText,
    });
}

swalFire('Done', 'Check your email inbox for a registration email.', 'success', 'Ok');
