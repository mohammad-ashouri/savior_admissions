// swal.js
import Swal from 'sweetalert2';

export default function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, html: text, icon: icon, confirmButtonText: confirmButtonText,
    });
}
