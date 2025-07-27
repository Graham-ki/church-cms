document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const sidebar = document.querySelector('.sidebar');
    
    if (mobileMenuBtn && sidebar) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Notification dropdown
    const notificationIcon = document.querySelector('.notification-icon');
    if (notificationIcon) {
        notificationIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.createElement('div');
            dropdown.className = 'notification-dropdown';
            dropdown.innerHTML = `
                <div class="notification-header">
                    <h4>Notifications</h4>
                    <small>Mark all as read</small>
                </div>
                <div class="notification-list">
                    <div class="notification-item">
                        <div class="notification-icon"><i class="fas fa-user-check"></i></div>
                        <div class="notification-content">
                            <p>James Okello marked present for Sunday service</p>
                            <small>2 hours ago</small>
                        </div>
                    </div>
                    <!-- More notifications -->
                </div>
            `;
            
            // Position and show dropdown
            const rect = this.getBoundingClientRect();
            dropdown.style.position = 'absolute';
            dropdown.style.right = '0';
            dropdown.style.top = rect.height + 'px';
            dropdown.style.display = 'block';
            
            this.appendChild(dropdown);
            
            // Close when clicking outside
            document.addEventListener('click', function closeDropdown() {
                dropdown.remove();
                document.removeEventListener('click', closeDropdown);
            });
        });
    }
    
    // Logout functionality
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                // In a real app, you would make an API call here
                window.location.href = 'login.html';
            }
        });
    }
    
    // Prevent dropdown close when clicking inside
    document.querySelectorAll('.user-profile, .notification-icon').forEach(el => {
        el.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        const dropdowns = document.querySelectorAll('.user-dropdown, .notification-dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.remove();
        });
    });
});