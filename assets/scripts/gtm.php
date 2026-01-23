<!-- Google Tag Manager & LinkedIn Pixel (Optimized User Interaction Loading with Bot Detection) -->
<script>
(function() {
  'use strict';

  // In-app detection - check URL param or cookie
  // Cloudflare redirects before PHP runs, so we must check in JavaScript
  function isInAppContext() {
    // Check URL parameter (works if app passes ?inapp=1)
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('inapp') === '1') {
      // Set cookie for future page loads (session cookie)
      document.cookie = 'e365_inapp=1;path=/;SameSite=Lax';
      console.log('📱 In-app context detected via URL, cookie set');
      return true;
    }
    // Check cookie (set by previous page load or app)
    if (document.cookie.split(';').some(function(c) {
      return c.trim().startsWith('e365_inapp=1');
    })) {
      console.log('📱 In-app context detected via cookie');
      return true;
    }
    return false;
  }

  // Skip ALL tracking if in app context - exit immediately
  if (isInAppContext()) {
    console.log('⛔ In-app context - skipping GTM & tracking');
    return; // Exit the IIFE - no tracking code runs
  }

  // Bot detection function
  function isBot() {
    var botPatterns = /bot|crawler|spider|scraper|curl|wget|python|java(?!script)|perl|ruby|go-http-client|request|axios|httpclient|okhttp|node|phantomjs|headless|selenium|puppeteer|playwright|webdriver|ghost|applebot|googlebot|bingbot|slurp|duckduckbot|baiduspider|yandexbot|sogoubot|exabot|facebookexternalhit|twitterbot|linkedinbot|whatsapp|telegram|skype|viber|iphone os 5|nokia|blackberry|windows phone|windows ce|palm|avantgo|blazer|elaine|hiptop|kindle|midp|mmp|netfront|opwv|pda|plucker|pocket|psp|treo|up\.browser|up\.link|vodafone|wap|opera mini/i;

    if (botPatterns.test(navigator.userAgent)) {
      console.log('⛔ Bot detected, skipping tracking');
      return true;
    }

    if (typeof window === 'undefined') {
      return true;
    }

    if (!window.chrome && !window.navigator.vendor) {
      return true;
    }

    return false;
  }

  // Initialize dataLayer and LinkedIn config
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({'gtm.start': new Date().getTime(), 'event':'gtm.js'});

  window._linkedin_partner_id = "2539348";
  window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
  window._linkedin_data_partner_ids.push(window._linkedin_partner_id);

  var trackingLoaded = false;

  function loadTracking() {
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

    window.dataLayer.push({'event': 'tracking.loaded'});
    console.log('✅ GTM & LinkedIn Pixel loaded successfully!');
  }

  // Load tracking on first user interaction
  if (!isBot()) {
    ['scroll', 'click', 'mousemove', 'touchstart', 'keydown'].forEach(function(event) {
      window.addEventListener(event, loadTracking, { once: true, passive: true });
    });

    // Fallback: Load after 10 seconds if no interaction
    setTimeout(loadTracking, 10000);
  } else {
    console.log('⛔ Bot detected on pageload, tracking disabled');
  }
})();
</script>

<!-- LinkedIn noscript fallback -->
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=2539348&fmt=gif" />
</noscript>
<!-- End Google Tag Manager & LinkedIn Pixel -->
