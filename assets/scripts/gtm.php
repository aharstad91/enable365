<!-- Google Tag Manager & LinkedIn Pixel (Optimized User Interaction Loading) -->
<script>
// Initialize dataLayer and LinkedIn config
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({'gtm.start': new Date().getTime(), 'event':'gtm.js'});

_linkedin_partner_id = "2539348";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);

let trackingLoaded = false;

function loadTracking() {
  if (trackingLoaded) return;
  trackingLoaded = true;
  
  console.log('🚀 Loading tracking scripts...');
  
  // Load GTM
  var gtmScript = document.createElement('script');
  gtmScript.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-M3TGPXJK';
  gtmScript.async = true;
  document.body.appendChild(gtmScript);
  
  // Initialize LinkedIn tracking
  if (!window.lintrk) {
    window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
    window.lintrk.q = [];
  }
  
  // Load LinkedIn Pixel
  var linkedinScript = document.createElement('script');
  linkedinScript.type = 'text/javascript';
  linkedinScript.async = true;
  linkedinScript.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';
  document.body.appendChild(linkedinScript);
  
  // Push event to dataLayer when everything loads
  window.dataLayer.push({'event': 'tracking.loaded'});
  console.log('✅ GTM & LinkedIn Pixel loaded successfully!');
}

// Load tracking on first user interaction (scroll, click, touch, or mouse movement)
['scroll', 'click', 'mousemove', 'touchstart', 'keydown'].forEach(function(event) {
  window.addEventListener(event, loadTracking, { once: true, passive: true });
});

// Fallback: Load after 10 seconds if no interaction (for bounce rate tracking)
setTimeout(loadTracking, 10000);
</script>

<!-- LinkedIn noscript fallback -->
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=2539348&fmt=gif" />
</noscript>
<!-- End Google Tag Manager & LinkedIn Pixel -->
