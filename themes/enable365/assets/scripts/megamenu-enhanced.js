/**
 * Enhanced Menu functionality for Enable365 theme
 * Handles both megamenu and regular dropdowns with improved viewport centering
 */
document.addEventListener('DOMContentLoaded', function() {
    // Handle megamenu
    const productsMenuItem = document.querySelector('.primary-nav ul li.has-megamenu');
    
    if (productsMenuItem) {
        const megamenu = productsMenuItem.querySelector('.megamenu');
        
        // Add mouseover event for desktop
        if (window.matchMedia('(min-width: 1025px)').matches) {
            productsMenuItem.addEventListener('mouseover', function() {
                // Make sure megamenu is visible
                if (megamenu) {
                    megamenu.style.opacity = '1';
                    megamenu.style.visibility = 'visible';
                    megamenu.style.pointerEvents = 'auto';
                }
            });
            
            productsMenuItem.addEventListener('mouseleave', function() {
                // Give a slight delay to allow cursor to move to megamenu
                setTimeout(function() {
                    if (!megamenu.matches(':hover')) {
                        megamenu.style.opacity = '0';
                        megamenu.style.visibility = 'hidden';
                        megamenu.style.pointerEvents = 'none';
                    }
                }, 100);
            });
            
            // Add separate event for megamenu
            megamenu.addEventListener('mouseleave', function() {
                megamenu.style.opacity = '0';
                megamenu.style.visibility = 'hidden';
                megamenu.style.pointerEvents = 'none';
            });
        }
        
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
                        megamenu.style.opacity = '1';
                        megamenu.style.visibility = 'visible';
                        megamenu.style.pointerEvents = 'auto';
                    } else {
                        megamenu.style.display = 'none';
                        megamenu.style.opacity = '0';
                        megamenu.style.visibility = 'hidden';
                        megamenu.style.pointerEvents = 'none';
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
    
    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
        const megamenu = document.querySelector('.megamenu');
        const productsMenuItem = document.querySelector('.primary-nav ul li.has-megamenu');
        
        // For megamenu
        if (megamenu && productsMenuItem && !productsMenuItem.contains(e.target) && !megamenu.contains(e.target)) {
            productsMenuItem.classList.remove('active');
            megamenu.style.opacity = '0';
            megamenu.style.visibility = 'hidden';
            megamenu.style.pointerEvents = 'none';
        }
        
        // For dropdowns
        dropdownItems.forEach(function(item) {
            const dropdownMenu = item.querySelector('.dropdown-menu');
            if (!item.contains(e.target) && dropdownMenu) {
                item.classList.remove('active');
                dropdownMenu.style.display = '';
            }
        });
    });
});
