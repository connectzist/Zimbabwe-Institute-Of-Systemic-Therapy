document.addEventListener('DOMContentLoaded', function() {
    const errorMessage = document.querySelector('.alert-danger');

    if (errorMessage) {
        setTimeout(function() {
            errorMessage.style.display = 'none';
        }, 3000); 
    }
});
