<!-- Google Tag Manager (Optimized User Interaction Loading) -->
<script>
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({'gtm.start': new Date().getTime(), 'event':'gtm.js'});

let gtmLoaded = false;

function loadGTM() {
  if (gtmLoaded) return;
  gtmLoaded = true;
  
  console.log('🚀 GTM Loading triggered!');
  
  var script = document.createElement('script');
  script.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-M3TGPXJK';
  script.async = true;
  document.body.appendChild(script);
  
  // Push event to dataLayer when GTM loads
  window.dataLayer.push({'event': 'gtm.loaded'});
  console.log('✅ GTM Loaded successfully!');
}

// Load GTM on first user interaction (scroll, click, touch, or mouse movement)
['scroll', 'click', 'mousemove', 'touchstart', 'keydown'].forEach(function(event) {
  window.addEventListener(event, loadGTM, { once: true, passive: true });
});

// Fallback: Load after 10 seconds if no interaction (for bounce rate tracking)
setTimeout(loadGTM, 10000);
</script>
<!-- End Google Tag Manager -->
