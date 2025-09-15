document.addEventListener('DOMContentLoaded', function() {
    const errorMessage = document.querySelector('.alert-danger');
    const successMessage = document.querySelector('.alert-success');

    if (errorMessage) {
        setTimeout(function() {
            errorMessage.style.display = 'none';
        }, 5000); 
    }

    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 5000);
    }
});
