/**
 * Custom Headroom.js initialization for Enable365 theme
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize desktop header headroom if element exists
  var desktopHeader = document.querySelector(".primary-header.headroom");
  
  if (desktopHeader) {
    console.log('Desktop header found, initializing Headroom');
    var headroom = new Headroom(desktopHeader, {
      offset: 100,
      tolerance: 5,
      classes: {
        initial: "animated",
        pinned: "slideDown",
        unpinned: "slideUp"
      }
    });
    
    headroom.init();
    console.log('Desktop header Headroom initialized');
  } else {
    console.log('Desktop header with .headroom class not found');
    
    // Try to find any primary-header element
    var anyDesktopHeader = document.querySelector(".primary-header");
    if (anyDesktopHeader) {
      console.log('Found primary-header but without .headroom class');
    }
  }
  
  // Initialize mobile header headroom if element exists
  var mobileHeader = document.querySelector(".mobile-header.mobile-headroom");
  
  if (mobileHeader) {
    console.log('Mobile header found, initializing Headroom');
    var mobileHeadroom = new Headroom(mobileHeader, {
      offset: 80,
      tolerance: 5,
      classes: {
        initial: "animated",
        pinned: "slideDown",
        unpinned: "slideUp"
      }
    });
    
    mobileHeadroom.init();
    console.log('Mobile header Headroom initialized');
  } else {
    console.log('Mobile header with .mobile-headroom class not found');
  }
});
