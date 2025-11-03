/**
 * Viewport-Centered Megamenu for Enable365 theme
 * Centers the megamenu in the viewport with improved interactions
 */
document.addEventListener('DOMContentLoaded', function() {
    // Handle megamenu
    const productsMenuItem = document.querySelector('.primary-nav ul li.has-megamenu');
    
    if (productsMenuItem) {
        const megamenu = productsMenuItem.querySelector('.megamenu');
        
        // Function to calculate header height for proper positioning
        const updateMegamenuPosition = function() {
            if (window.matchMedia('(min-width: 1025px)').matches) {
                const header = document.querySelector('.primary-header');
                if (header && megamenu) {
                    const headerHeight = header.offsetHeight;
                    // Always use a consistent 24px gap
                    megamenu.style.top = `${headerHeight + 24}px`;
                }
            }
        };
        
        // Update positioning on load and resize
        updateMegamenuPosition();
        window.addEventListener('resize', updateMegamenuPosition);
        
        // Add mouseover event for desktop - always attach, not just at load time
        productsMenuItem.addEventListener('mouseenter', function() {
            // Only activate for desktop
            if (window.matchMedia('(min-width: 1025px)').matches) {
                // Make sure megamenu is visible
                if (megamenu) {
                    updateMegamenuPosition(); // Recalculate position
                    megamenu.style.opacity = '1';
                    megamenu.style.visibility = 'visible';
                    megamenu.style.pointerEvents = 'auto';
                }
            }
        });
        
        productsMenuItem.addEventListener('mouseleave', function() {
            // Only handle for desktop
            if (window.matchMedia('(min-width: 1025px)').matches) {
                // Give a slight delay to allow cursor to move to megamenu
                setTimeout(function() {
                    if (!megamenu.matches(':hover')) {
                        megamenu.style.opacity = '0';
                        megamenu.style.visibility = 'hidden';
                        megamenu.style.pointerEvents = 'none';
                    }
                }, 100);
            }
        });
        
        // Add separate event for megamenu
        if (megamenu) {
            megamenu.addEventListener('mouseleave', function() {
                // Only handle for desktop
                if (window.matchMedia('(min-width: 1025px)').matches) {
                    megamenu.style.opacity = '0';
                    megamenu.style.visibility = 'hidden';
                    megamenu.style.pointerEvents = 'none';
                }
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
                        megamenu.style.display = 'flex';
                        megamenu.style.opacity = '1';
                        megamenu.style.visibility = 'visible';
                        megamenu.style.pointerEvents = 'auto';
                    } else {
                        megamenu.style.opacity = '0';
                        megamenu.style.visibility = 'hidden';
                        megamenu.style.pointerEvents = 'none';
                        setTimeout(function() {
                            megamenu.style.display = 'none';
                        }, 150); // Match transition duration
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
        
        // Add hover functionality for desktop - always attach, not just at load time
        item.addEventListener('mouseenter', function() {
            // Only activate for desktop
            if (window.matchMedia('(min-width: 1025px)').matches) {
                if (dropdownMenu) {
                    dropdownMenu.style.opacity = '1';
                    dropdownMenu.style.visibility = 'visible';
                    dropdownMenu.style.pointerEvents = 'auto';
                }
            }
        });
        
        item.addEventListener('mouseleave', function() {
            // Only handle for desktop
            if (window.matchMedia('(min-width: 1025px)').matches) {
                setTimeout(function() {
                    if (dropdownMenu && !dropdownMenu.matches(':hover')) {
                        dropdownMenu.style.opacity = '0';
                        dropdownMenu.style.visibility = 'hidden';
                        dropdownMenu.style.pointerEvents = 'none';
                    }
                }, 100);
            }
        });
        
        if (dropdownMenu) {
            dropdownMenu.addEventListener('mouseleave', function() {
                // Only handle for desktop
                if (window.matchMedia('(min-width: 1025px)').matches) {
                    dropdownMenu.style.opacity = '0';
                    dropdownMenu.style.visibility = 'hidden';
                    dropdownMenu.style.pointerEvents = 'none';
                }
            });
        }
        
        // Handle click events for touch devices
        item.addEventListener('click', function(e) {
            if (window.matchMedia('(max-width: 1024px)').matches) {
                if (e.target === link || link.contains(e.target) || e.target.tagName.toLowerCase() === 'svg' || e.target.tagName.toLowerCase() === 'path') {
                    e.preventDefault();
                    item.classList.toggle('active');
                    
                    if (dropdownMenu) {
                        if (item.classList.contains('active')) {
                            dropdownMenu.style.display = 'block';
                            dropdownMenu.style.opacity = '1';
                            dropdownMenu.style.visibility = 'visible';
                            dropdownMenu.style.pointerEvents = 'auto';
                        } else {
                            dropdownMenu.style.opacity = '0';
                            dropdownMenu.style.visibility = 'hidden';
                            dropdownMenu.style.pointerEvents = 'none';
                            setTimeout(function() {
                                dropdownMenu.style.display = 'none';
                            }, 150);
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
        if (productsMenuItem && !productsMenuItem.contains(e.target) && megamenu && !megamenu.contains(e.target)) {
            productsMenuItem.classList.remove('active');
            if (megamenu) {
                megamenu.style.opacity = '0';
                megamenu.style.visibility = 'hidden';
                megamenu.style.pointerEvents = 'none';
            }
        }
        
        // For dropdowns
        dropdownItems.forEach(function(item) {
            const dropdownMenu = item.querySelector('.dropdown-menu');
            if (!item.contains(e.target) && dropdownMenu && !dropdownMenu.contains(e.target)) {
                item.classList.remove('active');
                if (dropdownMenu) {
                    dropdownMenu.style.opacity = '0';
                    dropdownMenu.style.visibility = 'hidden';
                    dropdownMenu.style.pointerEvents = 'none';
                }
            }
        });
    });

    // Adjust for window resize
    window.addEventListener('resize', function() {
        const megamenu = document.querySelector('.megamenu');
        if (megamenu) {
            // Reset styles on resize to ensure proper positioning
            megamenu.style.opacity = '0';
            megamenu.style.visibility = 'hidden';
            megamenu.style.pointerEvents = 'none';
        }
    });
});
