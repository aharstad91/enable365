/**
 * Menu functionality for Enable365 theme
 * Handles both megamenu and regular dropdowns
 */
document.addEventListener('DOMContentLoaded', function() {
    // Handle megamenu
    const productsMenuItem = document.querySelector('.primary-nav ul li.has-megamenu');
    
    if (productsMenuItem) {
        const megamenu = productsMenuItem.querySelector('.megamenu');
        
        // Handle click events for touch devices
        productsMenuItem.addEventListener('click', function(e) {
            // Only do this for touch devices
            if (window.matchMedia('(max-width: 1024px)').matches) {
                e.preventDefault();
                productsMenuItem.classList.toggle('active');
                
                // Toggle the megamenu display
                if (megamenu) {
                    if (productsMenuItem.classList.contains('active')) {
                        megamenu.style.display = 'block';
                    } else {
                        megamenu.style.display = 'none';
                    }
                }
            }
        });
    }
    
    // Handle regular dropdowns
    const dropdownItems = document.querySelectorAll('.primary-nav ul li.has-dropdown');
    
    dropdownItems.forEach(function(item) {
        const dropdownMenu = item.querySelector('.dropdown-menu');
        const link = item.querySelector('a');
        
        // Handle click events for touch devices
        item.addEventListener('click', function(e) {
            // Only do this for touch devices
            if (window.matchMedia('(max-width: 1024px)').matches) {
                // Check if the click was on the link itself or the dropdown arrow
                if (e.target === link || link.contains(e.target) || e.target.tagName.toLowerCase() === 'svg' || e.target.tagName.toLowerCase() === 'path') {
                    e.preventDefault();
                    item.classList.toggle('active');
                    
                    // Toggle the dropdown display
                    if (dropdownMenu) {
                        if (item.classList.contains('active')) {
                            dropdownMenu.style.display = 'block';
                        } else {
                            dropdownMenu.style.display = 'none';
                        }
                    }
                }
            }
        });
    });
    
    // Also handle sub-dropdowns if they exist
    const subDropdownItems = document.querySelectorAll('.dropdown-menu li.has-sub-dropdown');
    
    subDropdownItems.forEach(function(item) {
        const subDropdownMenu = item.querySelector('.sub-dropdown-menu');
        const link = item.querySelector('a');
        
        // Handle click events
        item.addEventListener('click', function(e) {
            // Check if the click was on the link itself or the dropdown arrow
            if (e.target === link || link.contains(e.target) || e.target.tagName.toLowerCase() === 'svg' || e.target.tagName.toLowerCase() === 'path') {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling
                item.classList.toggle('active');
                
                // Toggle the sub-dropdown display
                if (subDropdownMenu) {
                    if (item.classList.contains('active')) {
                        subDropdownMenu.style.display = 'block';
                    } else {
                        subDropdownMenu.style.display = 'none';
                    }
                }
            }
        });
    });
    
    // Close all menus when clicking outside
    document.addEventListener('click', function(e) {
        // For megamenu
        if (productsMenuItem && !productsMenuItem.contains(e.target)) {
            productsMenuItem.classList.remove('active');
            const megamenu = productsMenuItem.querySelector('.megamenu');
            if (megamenu) {
                megamenu.style.display = '';
            }
        }
        
        // For dropdowns
        dropdownItems.forEach(function(item) {
            if (!item.contains(e.target)) {
                item.classList.remove('active');
                const dropdownMenu = item.querySelector('.dropdown-menu');
                if (dropdownMenu) {
                    dropdownMenu.style.display = '';
                }
            }
        });
    });
    
    // Add additional styles for active state
    const style = document.createElement('style');
    style.textContent = `
        .primary-nav ul li.has-megamenu.active .megamenu {
            display: block;
        }
        .primary-nav ul li.has-dropdown.active .dropdown-menu {
            display: block;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .dropdown-menu li.has-sub-dropdown.active .sub-dropdown-menu {
            display: block;
        }
    `;
    document.head.appendChild(style);
});
