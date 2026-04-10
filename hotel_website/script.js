
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide messages after 5 seconds
    const messages = document.querySelectorAll('.message');
    messages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            setTimeout(() => {
                msg.style.display = 'none';
            }, 500);
        }, 5000);
    });
    
    
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            window.location.href = 'order.php';
        });
    });
    
    
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            const date = document.getElementById('order_date').value;
            const today = new Date().toISOString().split('T')[0];
            if (date < today) {
                e.preventDefault();
                alert('Please select today or a future date');
            }
        });
    }
});