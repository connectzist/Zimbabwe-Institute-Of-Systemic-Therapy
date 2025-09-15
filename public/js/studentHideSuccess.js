document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.querySelector('.alert-success');

    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 3000);
    }
});
