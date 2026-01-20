/**
 * Custom Headroom.js initialization for Enable365 theme
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize desktop header headroom if element exists
  var desktopHeader = document.querySelector(".primary-header.headroom");

  if (desktopHeader) {
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
  }

  // Initialize mobile header headroom if element exists
  var mobileHeader = document.querySelector(".mobile-header.mobile-headroom");

  if (mobileHeader) {
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
  }
});
