
/**
 * Main JavaScript file for Restaurant App
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Restaurant App JS initialized');
    
    // Add any JavaScript functionality here
    
    // For example, handle message alerts auto-dismiss
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            }, 3000);
        });
    }
});