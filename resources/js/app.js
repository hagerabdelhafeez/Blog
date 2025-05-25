import './bootstrap';
import Swal from 'sweetalert2';


// Make it available globally
window.Swal = Swal;

// Optional: Create a shorthand function for common alerts
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});
