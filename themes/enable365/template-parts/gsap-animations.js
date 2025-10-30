/**
 * GSAP ScrollTrigger animations for the front page
 */

// GSAP ScrollTrigger image switcher med fade/slide animasjon
document.addEventListener('DOMContentLoaded', function() {
  gsap.registerPlugin(ScrollTrigger);

  // Function to check if we're on desktop (lg breakpoint)
  function isDesktop() {
    return window.innerWidth >= 1024; // Tailwind's lg breakpoint is 1024px
  }

  // Initialize only on desktop
  function initScrollTriggers() {
    // Only run on desktop
    if (!isDesktop()) return;

    const steps = document.querySelectorAll('.step');
    const image = document.getElementById('sticky-image');
    
    // Exit if elements don't exist
    if (!steps.length || !image) return;
    
    let currentImageIndex = 0;
    let currentTween = null;

    // Hent alle bilde-URLer fra data-image
    const images = Array.from(steps).map(step => step.getAttribute('data-image'));

    // Sett første bilde ved start
    if (images.length > 0) {
      image.src = images[0];
      image.style.opacity = 1;
      image.style.transform = 'translateX(0)';
      currentImageIndex = 0;
    }

    // Fjern eksisterende triggers hvis hot reload
    if (window._enable365ScrollTriggers) {
      window._enable365ScrollTriggers.forEach(t => t.kill());
    }
    window._enable365ScrollTriggers = [];

    steps.forEach((step, idx) => {
      const trigger = ScrollTrigger.create({
        trigger: step,
        start: 'top+=50 center', // 50px inn i seksjonen
        end: 'bottom center',
        onEnter: () => switchImage(idx),
        onEnterBack: () => switchImage(idx),
        // markers: true, // Debug
      });
      window._enable365ScrollTriggers.push(trigger);
    });
  }

  function switchImage(idx) {
    const image = document.getElementById('sticky-image');
    if (!image) return;
    
    const steps = document.querySelectorAll('.step');
    const images = Array.from(steps).map(step => step.getAttribute('data-image'));
    
    if (idx === window.currentImageIndex) return;
    const newSrc = images[idx];
    image.src = newSrc;
    window.currentImageIndex = idx;
  }

  // Initialize on page load
  initScrollTriggers();

  // Reinitialize on resize
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      // Kill existing triggers first
      if (window._enable365ScrollTriggers) {
        window._enable365ScrollTriggers.forEach(t => t.kill());
        window._enable365ScrollTriggers = [];
      }
      // Reinitialize if we're on desktop
      initScrollTriggers();
    }, 250); // Debounce resize events
  });
});
