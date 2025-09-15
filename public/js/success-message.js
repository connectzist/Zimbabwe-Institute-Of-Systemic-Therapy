 // Fade out success message
 setTimeout(function() {
    var successMessage = document.getElementById('success-message');
    if (successMessage) {
        successMessage.style.transition = 'opacity 0.5s ease';
        successMessage.style.opacity = 0;
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 500);
    }
}, 2000);