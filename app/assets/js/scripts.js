
/**
 * Main JavaScript file for the application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle file input change to show preview of selected image
    const profilePictureInput = document.getElementById('profile_picture');
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const profileImage = document.querySelector('.profile-image');
                    if (profileImage) {
                        profileImage.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form validation for password change
    const passwordForm = document.querySelector('form:nth-of-type(3)');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password');
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (newPassword.value && newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Les nouveaux mots de passe ne correspondent pas');
                return false;
            }
            
            if ((newPassword.value || confirmPassword.value) && !currentPassword.value) {
                e.preventDefault();
                alert('Veuillez entrer votre mot de passe actuel');
                return false;
            }
        });
    }

    // Automatically hide alert messages after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 1s ease';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 1000);
            }, 5000);
        });
    }
});