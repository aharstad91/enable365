<?php
/**
 * GTM & LinkedIn Pixel - Skip loading in app context
 * When ?inapp=1 is present, don't load any tracking scripts (including CookieScript banner via GTM)
 * Used for in-app views like "What's New" dialogs and onboarding guides
 */
if (isset($_GET['inapp']) && $_GET['inapp'] === '1') {
    return; // Exit early - no tracking in app context
}
?>
<!-- Google Tag Manager & LinkedIn Pixel (Optimized User Interaction Loading with Bot Detection) -->
<script>
// Bot detection function
function isBot() {
  // Check user agent for common bot patterns
  var botPatterns = /bot|crawler|spider|scraper|curl|wget|python|java(?!script)|perl|ruby|go-http-client|request|axios|httpclient|okhttp|node|phantomjs|headless|selenium|puppeteer|playwright|webdriver|ghost|applebot|googlebot|bingbot|slurp|duckduckbot|baiduspider|yandexbot|sogoubot|exabot|facebookexternalhit|twitterbot|linkedinbot|whatsapp|telegram|skype|viber|iphone os 5|nokia|blackberry|windows phone|windows ce|palm|avantgo|blazer|elaine|hiptop|kindle|midp|mmp|netfront|opwv|pda|plucker|pocket|psp|treo|up\.browser|up\.link|vodafone|wap|opera mini/i;
  
  if (botPatterns.test(navigator.userAgent)) {
    console.log('⛔ Bot detected, skipping tracking');
    return true;
  }
  
  // Check if JavaScript is disabled (common in bots)
  if (typeof window === 'undefined') {
    return true;
  }
  
  // Check for headless browser indicators
  if (!window.chrome && !window.navigator.vendor) {
    return true;
  }
  
  return false;
}

// Initialize dataLayer and LinkedIn config only if not a bot
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({'gtm.start': new Date().getTime(), 'event':'gtm.js'});

_linkedin_partner_id = "2539348";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);

let trackingLoaded = false;

function loadTracking() {
  // Skip if already loaded or if bot detected
  if (trackingLoaded || isBot()) return;
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
// Only attach listeners if not a bot
if (!isBot()) {
  ['scroll', 'click', 'mousemove', 'touchstart', 'keydown'].forEach(function(event) {
    window.addEventListener(event, loadTracking, { once: true, passive: true });
  });
  
  // Fallback: Load after 10 seconds if no interaction (for bounce rate tracking)
  setTimeout(loadTracking, 10000);
} else {
  console.log('⛔ Bot detected on pageload, tracking disabled');
}
</script>

<!-- LinkedIn noscript fallback -->
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=2539348&fmt=gif" />
</noscript>
<!-- End Google Tag Manager & LinkedIn Pixel -->
