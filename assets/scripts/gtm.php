<!-- Google Tag Manager (Optimized Idle Loading) -->
<script>
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({'gtm.start': new Date().getTime(), 'event':'gtm.js'});

// Load GTM only when browser is idle (after LCP)
function loadGTM() {
  var script = document.createElement('script');
  script.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-M3TGPXJK';
  script.async = true;
  document.body.appendChild(script);
}

if ('requestIdleCallback' in window) {
  requestIdleCallback(loadGTM, {timeout: 2000});
} else {
  // Fallback for older browsers
  setTimeout(loadGTM, 2000);
}
</script>
<!-- End Google Tag Manager -->
