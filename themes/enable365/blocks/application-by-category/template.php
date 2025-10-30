<?php
/**
 * Apps tabs section for the front page
 */

// Insert script to prevent ScrollReveal from hiding our cards
if (!is_admin()) {
  // Selectively disable ScrollReveal only for our tab components
  add_action('wp_footer', function() {
    ?>
    <script>
      // More selective approach - only prevent ScrollReveal on our tab cards
      (function() {
        // Check if ScrollReveal exists
        if (window.ScrollReveal) {
          console.log('Selectively excluding tab cards from ScrollReveal effects');
          
          try {
            // Store the original ScrollReveal reveal method
            if (!window._originalScrollRevealReveal && ScrollReveal().reveal) {
              window._originalScrollRevealReveal = ScrollReveal().reveal;
              
              // Override reveal to filter out our components
              ScrollReveal().reveal = function(target, options) {
                // Check if the target includes our selectors
                if (typeof target === 'string' && 
                    (target.includes('sr-exclude-cards') || 
                     target.includes('app-links') ||
                     target.includes('data-tab-content'))) {
                  console.log('Prevented ScrollReveal on:', target);
                  return this;
                }
                
                // For everything else, use the original method
                return window._originalScrollRevealReveal.call(this, target, options);
              };
            }
            
            // Only force our specific cards to be visible
            document.querySelectorAll('.sr-exclude-cards > div').forEach(el => {
              el.style.visibility = 'visible';
              el.style.opacity = '1';
              el.style.transform = 'none';
            });
            
            // Use a targeted MutationObserver only for our cards
            const observer = new MutationObserver(function(mutations) {
              mutations.forEach(function(mutation) {
                if (mutation.target.classList.contains('sr-exclude-cards') || 
                    mutation.target.closest('.sr-exclude-cards')) {
                  mutation.target.style.visibility = 'visible';
                  mutation.target.style.opacity = '1';
                }
              });
            });
            
            // Only observe the app-links section
            const appLinks = document.querySelector('.app-links');
            if (appLinks) {
              observer.observe(appLinks, { 
                attributes: true, 
                attributeFilter: ['style', 'class'],
                childList: true,
                subtree: true
              });
            }
          } catch (e) {
            console.warn('Error configuring ScrollReveal override:', e);
          }
        }
      })();
    </script>
    <?php
  }, 999); // High priority to run late
  
  // More targeted approach with wp_head action
  add_action('wp_head', function() {
    ?>
    <script>
      // Targeted approach: Only prevent ScrollReveal from affecting our card components
      document.addEventListener('DOMContentLoaded', function() {
        if (window.ScrollReveal) {
          try {
            // Store original reveal method so we can intercept it
            const originalReveal = ScrollReveal().reveal;
            
            // Protect all our tab components
            const protectedSelectors = [
              '.sr-exclude-cards > div',
              '[data-tab-content="software"] .sr-exclude-cards',
              '[data-tab-content="product"] .sr-exclude-cards',
              '[data-tab-indicator]',
              '.ani-clean',
              '.max-w-[1280px].bg-white.rounded-xl'
            ];
            
            // Override the reveal method to exclude only our card elements
            ScrollReveal().reveal = function(selector, options) {
              if (typeof selector === 'string') {
                // Check if the selector matches our protected elements
                for (const protectedSelector of protectedSelectors) {
                  if (selector.includes(protectedSelector)) {
                    console.log('Protected tab card from ScrollReveal:', selector);
                    return this; // Return early without applying reveal effect
                  }
                }
              }
              
              // For everything else, call the original method
              return originalReveal.call(this, selector, options);
            };
            
            // Only clean our specific card elements
            protectedSelectors.forEach(sel => {
              try {
                ScrollReveal().clean(sel);
              } catch(e) {
                console.warn('Error cleaning element:', sel, e);
              }
            });
          } catch(e) {
            console.warn('Failed to override ScrollReveal:', e);
          }
        }
      });
    </script>
    <?php
  });
}

// Check if we're in admin - if so, we'll display a simplified version
$is_admin = is_admin();

// --- Apps data ---
$apps = [
  'software' => [
    [
      'title' => 'PlanIt',
      'desc' => 'Annual planning',
      'url' => home_url('/apps/planit/'),
      'icon' => 'https://enable365.ai/wp-content/uploads/2025/07/planit.png',
      'image' => 'https://enable365.ai/wp-content/uploads/2025/07/PlanIt_intro.svg',
    ],
    [
      'title' => 'Agenda',
      'desc' => 'Meeting management',
      'url' => home_url('/apps/agenda/'),
      'icon' => 'https://enable365.ai/wp-content/uploads/2025/07/agenda.png',
      'image' => 'https://enable365.ai/wp-content/uploads/2025/07/agenda-forslag.svg', // Ny bilde-URL
    ],
    [
      'title' => 'Guidance',
      'desc' => 'Quality assurance',
      'url' => home_url('/apps/guidance/'),
      'icon' => 'https://enable365.ai/wp-content/uploads/2025/07/guidence.png',
      'image' => 'https://enable365.ai/wp-content/uploads/2025/07/Guidance.svg',
    ],
    [
      'title' => 'Presence',
      'desc' => 'Workforce management',
      'url' => home_url('/apps/presence/'),
      'icon' => 'https://enable365.ai/wp-content/uploads/2025/07/presence.png',
      'image' => 'https://enable365.ai/wp-content/uploads/2025/07/Presence_statisk.svg',
    ],

  ],
  'product' => [
    [
      'title' => 'Templates',
      'desc' => 'Templates library',
      'url' => home_url('/apps/templates/'),
      'icon' => 'https://enable365.ai/wp-content/uploads/2025/07/templates.png',
      'image' => 'https://enable365.ai/wp-content/uploads/2025/07/Templates.svg',
    ],
    [
      'title' => 'Employer (coming soon)',
      'desc' => 'Personalized service delivery at scale.',
      'url' => '',
      'icon' => 'https://enable365.ai/wp-content/uploads/2025/07/employer.png',
      'image' => 'https://enable365.ai/wp-content/uploads/2025/07/Employer-preview.svg',
    ],
    [
      'title' => 'Sites (coming soon)',
      'desc' => 'Smart, scalable Teams management with built-in governance.',
      'url' => '',
      'icon' => 'https://enable365.ai/wp-content/uploads/2025/07/sites-icon.svg',
      'image' => 'https://enable365.ai/wp-content/uploads/2025/07/Sites-preview.svg',
    ],
        [
      'title' => 'Central (coming soon)',
      'desc' => 'Secure and easy access to your organization’s documents.',
      'url' => '',
      'icon' => 'https://enable365.ai/wp-content/uploads/2025/07/central-icon.svg',
      'image' => 'https://enable365.ai/wp-content/uploads/2025/07/Central-preview.svg',
    ],
  ],
];

$tabs = [
  [
    'key' => 'software',
    'label' => 'Productivity & management',
    'apps' => $apps['software'],
    'content' => function($apps) {
      ?>
      <div x-show="activeTab === 'software'" data-tab-content="software" class="ani-clean">
        <style>
          .app-card a.learn-more {
            color: #AA1010;
          }
          /* Only force cards in this specific tab to be visible */
          [data-tab-content="software"] .sr-exclude-cards > div {
            visibility: visible !important;
            opacity: 1 !important;
            transform: none !important;
          }
        </style>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sr-exclude-cards ani-clean" style="visibility: visible !important; opacity: 1 !important;">
          <?php foreach($apps as $app): ?>
            <?php if(isset($app['url']) && !empty($app['url'])): ?>
            <a href="<?php echo $app['url']; ?>" class="block bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition group" style="visibility: visible !important; opacity: 1 !important; transform: none !important;">
            <?php else: ?>
            <div class="block bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between" style="visibility: visible !important; opacity: 1 !important; transform: none !important;">
            <?php endif; ?>
              <!-- Card image at the top -->
              <div class="w-full h-[290px] overflow-hidden bg-gray-100">
                <?php if(isset($app['image']) && !empty($app['image'])): ?>
                  <img src="<?php echo $app['image']; ?>" alt="<?php echo $app['title']; ?> screenshot" class="w-full h-full object-cover">
                <?php else: ?>
                  <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">No image</div>
                <?php endif; ?>
              </div>
              
              <!-- Card content -->
              <div class="p-6 flex-grow flex flex-col">
                <div class="flex items-center mb-4">
                  <img src="<?php echo $app['icon']; ?>" alt="<?php echo $app['title']; ?>" class="h-10 w-10 mr-3">
                  <p class="font-semibold text-gray-900">
                    <?php echo $app['title']; ?>
                  </p>
                </div>
                <div class="text-gray-700">
                  <p>
                  <?php
                  // Eksempel på utvidet beskrivelse per app
                  if ($app['title'] === 'PlanIt') {
                    echo 'Plan and visualize your entire year with seamless Microsoft Teams integration.';
                  } elseif ($app['title'] === 'Agenda') {
                    echo 'Organize meetings, set agendas, and keep your team on track every week.';
                  } elseif ($app['title'] === 'Presence') {
                    echo 'Manage workforce schedules and ensure everyone is where they need to be.';
                  } elseif ($app['title'] === 'Templates') {
                    echo 'Access a library of ready-to-use templates for all your business needs.';
                  } elseif ($app['title'] === 'Central') {
                    echo 'Control governance and archiving for all your digital assets in one place.';
                  } elseif ($app['title'] === 'Guidance') {
                    echo 'Ensure quality and compliance with step-by-step guidance for your team.';
                  } elseif ($app['title'] === 'Employer') {
                    echo 'Simplify IT administration and empower managers with smart tools.';
                  } elseif ($app['title'] === 'Sites') {
                    echo 'Manage sites, permissions, and governance with ease and flexibility.';
                  } else {
                    echo $app['desc'];
                  }
                  ?>
                  </p>
                </div>
                <?php if(isset($app['url']) && !empty($app['url'])): ?>
                <span class="text-blue-600 text-sm font-medium mt-4 group-hover:underline">Learn more</span>
                <?php endif; ?>
              </div>
            <?php if(isset($app['url']) && !empty($app['url'])): ?>
            </a>
            <?php else: ?>
            </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
      <?php
    }
  ],
  [
    'key' => 'product',
    'label' => 'IT admin & governance',
    'apps' => $apps['product'],
    'content' => function($apps) {
      ?>
      <div x-show="activeTab === 'product'" data-tab-content="product" class="ani-clean">
        <style>
          .app-card a.learn-more {
            color: #AA1010;
          }
          /* Only force cards in this specific tab to be visible */
          [data-tab-content="product"] .sr-exclude-cards > div {
            visibility: visible !important;
            opacity: 1 !important;
            transform: none !important;
          }
        </style>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sr-exclude-cards ani-clean" style="visibility: visible !important; opacity: 1 !important;">
          <?php foreach($apps as $app): ?>
            <?php if(isset($app['url']) && !empty($app['url'])): ?>
            <a href="<?php echo $app['url']; ?>" class="block bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition group" style="visibility: visible !important; opacity: 1 !important; transform: none !important;">
            <?php else: ?>
            <div class="block bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between" style="visibility: visible !important; opacity: 1 !important; transform: none !important;">
            <?php endif; ?>
              <!-- Card image at the top -->
              <div class="w-full h-[290px] overflow-hidden bg-gray-100">
                <?php if(isset($app['image']) && !empty($app['image'])): ?>
                  <img src="<?php echo $app['image']; ?>" alt="<?php echo $app['title']; ?> screenshot" class="w-full h-full object-cover">
                <?php else: ?>
                  <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">No image</div>
                <?php endif; ?>
              </div>
              
              <!-- Card content -->
              <div class="p-6 flex-grow flex flex-col">
                <div class="flex items-center mb-4">
                  <img src="<?php echo $app['icon']; ?>" alt="<?php echo $app['title']; ?>" class="h-10 w-10 mr-3">
                  <p class="font-semibold text-lg text-gray-900">
                    <?php echo $app['title']; ?>
                  </p>
                </div>
                <div class="text-gray-700">
                  <p>
                  <?php
                  // Eksempel på utvidet beskrivelse per app
                  if ($app['title'] === 'PlanIt') {
                    echo 'Plan and visualize your entire year with seamless Microsoft Teams integration.';
                  } elseif ($app['title'] === 'Agenda') {
                    echo 'Organize meetings, set agendas, and keep your team on track every week.';
                  } elseif ($app['title'] === 'Presence') {
                    echo 'Manage workforce schedules and ensure everyone is where they need to be.';
                  } elseif ($app['title'] === 'Templates') {
                    echo 'Access a library of ready-to-use templates for all your business needs.';
                  } elseif ($app['title'] === 'Central') {
                    echo 'Control governance and archiving for all your digital assets in one place.';
                  } elseif ($app['title'] === 'Guidance') {
                    echo 'Ensure quality and compliance with step-by-step guidance for your team.';
                  } elseif ($app['title'] === 'Employer') {
                    echo 'Simplify IT administration and empower managers with smart tools.';
                  } elseif ($app['title'] === 'Sites') {
                    echo 'Manage sites, permissions, and governance with ease and flexibility.';
                  } else {
                    echo $app['desc'];
                  }
                  ?>
                  </p>
                </div>
                <?php if(isset($app['url']) && !empty($app['url'])): ?>
                <span class="text-blue-600 text-sm font-medium mt-4 group-hover:underline">Learn more</span>
                <?php endif; ?>
              </div>
            <?php if(isset($app['url']) && !empty($app['url'])): ?>
            </a>
            <?php else: ?>
            </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
      <?php
    }
  ]
];

// Only shuffle tabs for frontend
if (!$is_admin) {
  shuffle($tabs);
  // Always set the active tab to the first tab after shuffle (leftmost position)
  $activeTab = $tabs[0]['key'];
} else {
  // In admin, just use the first tab without shuffling
  $activeTab = $tabs[0]['key'];
}
?>

<?php if ($is_admin): ?>
<!-- ADMIN VIEW - Simple grid of all apps -->
<div class="admin-preview-block">
  <h3 style="margin-top:0;padding-top:0;">Application Grid</h3>
  <p>This block displays all applications in a simple grid:</p>
  
  <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));gap:15px;margin-bottom:15px;">
    <?php 
    // Show all apps without tabs
    foreach ($apps as $category => $category_apps): 
      foreach ($category_apps as $app):
    ?>
      <a href="<?php echo $app['url']; ?>" style="border:1px solid #ddd;border-radius:6px;padding:10px;background:#fff;text-decoration:none;color:inherit;display:block;transition:box-shadow 0.2s ease-in-out;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.1)';" onmouseout="this.style.boxShadow='none';">
        <div style="display:flex;align-items:center;margin-bottom:8px;">
          <div style="width:24px;height:24px;margin-right:8px;background:#eee;border-radius:4px;"></div>
          <strong><?php echo $app['title']; ?></strong>
        </div>
        <p style="margin:0;font-size:12px;color:#555;"><?php echo $app['desc']; ?></p>
        <div style="margin-top:5px;font-size:10px;color:#888;display:inline-block;padding:2px 6px;background:#f0f0f0;border-radius:3px;">
          <?php 
          echo ($category === 'software') ? 'Productivity' : 'IT Admin'; 
          ?>
        </div>
      </a>
    <?php 
      endforeach;
    endforeach; 
    ?>
  </div>
  
  <p style="margin-top:5px;color:#666;font-style:italic;">
    Admin preview: The frontend will display these apps in tabbed categories.
  </p>
</div>

<?php else: ?>
<!-- FRONTEND VIEW - Full functionality with Alpine.js -->
<style>
  .app-links a { color: #AA1010; }
  /* Make sure tab buttons are clearly interactive */
  .tab-button { cursor: pointer; }
  /* Don't use x-cloak as it can hide content before Alpine is initialized */
  /* [x-cloak] { display: none !important; } */
  /* Force card visibility even if ScrollReveal tries to hide them */
  .sr-exclude-cards > div {
    visibility: visible !important;
    opacity: 1 !important;
    transform: none !important;
  }
</style>
<!-- Selectively protect only our tab cards from ScrollReveal -->
<script>
  // Early executing script to protect only our tab cards from ScrollReveal
  (function() {
    // METHOD 1: Apply targeted styles only to our components
    var style = document.createElement('style');
    style.textContent = `
      /* Target all important components in our tab system */
      .sr-exclude-cards > div,
      [data-tab-content="software"] .sr-exclude-cards > div,
      [data-tab-content="product"] .sr-exclude-cards > div,
      [data-tab-indicator],
      .ani-clean,
      .ani-clean * {
        visibility: visible !important;
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
      }
    `;
    document.head.appendChild(style);
    
    // METHOD 2: Set a flag for our components
    window._protectCardComponents = true;
    
    // METHOD 3: Force visibility of all important elements in our tab system
    function forceElementsVisible() {
      // Define all selectors we want to ensure are visible
      const importantSelectors = [
        '.sr-exclude-cards > div',                // Card elements
        '[data-tab-indicator]',                   // Tab indicators
        '[data-tab-indicator] *',                 // Tab indicator contents
        '.max-w-\\[1280px\\].bg-white.rounded-xl' // Content wrapper
      ];
      
      // Check and fix all these elements
      importantSelectors.forEach(selector => {
        document.querySelectorAll(selector).forEach(element => {
          if (element.style.visibility === 'hidden' || 
              element.style.opacity === '0' || 
              getComputedStyle(element).visibility === 'hidden' || 
              getComputedStyle(element).opacity === '0') {
            element.style.visibility = 'visible';
            element.style.opacity = '1';
            element.style.transform = 'none';
            element.style.transition = 'none';
          }
        });
      });
    }
    
    // Check when tabs become visible
    const observer = new MutationObserver(mutations => {
      mutations.forEach(mutation => {
        if (mutation.attributeName === 'style' || mutation.attributeName === 'class') {
          const target = mutation.target;
          if (target.getAttribute('data-tab-content')) {
            forceElementsVisible();
          }
        }
      });
    });
    
    // Start observing tab content for changes
    setTimeout(() => {
      document.querySelectorAll('[data-tab-content]').forEach(tab => {
        observer.observe(tab, { attributes: true });
      });
      forceElementsVisible();
    }, 100);
    
    // Only run periodic checks for a short time
    const interval = setInterval(forceElementsVisible, 500);
    setTimeout(() => clearInterval(interval), 2000);
  })();
</script>
<div class="app-links ani-clean"
     x-data="{ activeTab: '<?php echo $activeTab; ?>' }" 
     x-init="$nextTick(() => { 
       // Set active tab to the first tab in DOM order (leftmost in UI)
       const firstTabBtn = document.querySelector('.tab-button'); 
       if(firstTabBtn) { 
         activeTab = firstTabBtn.getAttribute('data-tab'); 
       } 
     })">
  <!-- Main content container -->
  <div class="max-w-[1280px] mx-auto">
    <header id="apps" class="mx-auto text-center mb-12">
      <h3 class="text-2xl lg:text-[32px] font-bold mb-6">Empower everyone, on every team</h3>
      <!-- Tabs -->
      <div class="flex flex-col items-center mb-8">
        <!--<p class="text-gray-500 text-sm mb-3">Choose a category to explore</p>-->
        <div class="inline-flex bg-gray-200 p-1.5 rounded-lg shadow-sm">
          <?php foreach ($tabs as $tab): ?>
            <button
              type="button"
              class="tab-button px-5 py-2.5 rounded-md font-medium transition-all relative cursor-pointer"
              :class="activeTab === '<?php echo $tab['key']; ?>' ? 
                'bg-white text-[#AA1010] shadow-sm z-10' : 
                'text-gray-700 hover:text-[#AA1010]'"
              x-on:click="activeTab = '<?php echo $tab['key']; ?>'"
              onclick="window.setActiveTab && window.setActiveTab('<?php echo $tab['key']; ?>')"
              data-tab="<?php echo $tab['key']; ?>"
            >
              <?php echo $tab['label']; ?>
              <div x-show="activeTab === '<?php echo $tab['key']; ?>'" class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-6 h-1 bg-[#AA1010] rounded-full"></div>
            </button>
          <?php endforeach; ?>
        </div>
      </div>
    </header>

    <!-- Content wrapper -->
    <div class="max-w-[1280px] mx-auto bg-white rounded-xl lg:p-8 shadow-sm lg:border lg:border-gray-200 ani-clean" style="visibility: visible !important; opacity: 1 !important; transform: none !important;">
      <!-- Tab indicator -->
      <div class="flex mb-6 ani-clean">
        <?php foreach ($tabs as $tab): ?>
          <div 
            x-show="activeTab === '<?php echo $tab['key']; ?>'"
            class="flex items-center ani-clean tab-indicator"
            data-tab-indicator="<?php echo $tab['key']; ?>"
            style="visibility: visible !important; opacity: 1 !important;"
          >
            <span class="text-xl font-semibold text-gray-900">
              <?php echo $tab['label']; ?>
            </span>
            <span class="text-gray-400 mx-2">/</span>
            <span class="text-gray-500 text-sm">Browse our <span class="text-[#AA1010]"><?php echo strtolower($tab['label']); ?></span> solutions</span>
          </div>
        <?php endforeach; ?>
      </div>
      
      <?php foreach ($tabs as $tab) { $tab['content']($tab['apps']); } ?>
    </div>
  </div>
</div>

<!-- Frontend scripts -->
<script>
  // Initialize tabs as early as possible
  window.activeTabDefault = '<?php echo $activeTab; ?>';
  
  // Fix for ScrollReveal hiding cards in tabs
  document.addEventListener('DOMContentLoaded', function() {
    // Make sure ScrollReveal doesn't hide our tab card content
    // 1. First approach: Directly force display of cards when tabs become active
    const fixScrollRevealElements = () => {
      const activeTab = document.querySelector('.tab-button.bg-white').getAttribute('data-tab');
      const activeTabContent = document.querySelector(`[data-tab-content="${activeTab}"]`);
      
      if (activeTabContent) {
        // Find all cards inside the active tab and ensure they're visible
        const cards = activeTabContent.querySelectorAll('.sr-exclude-cards > div');
        cards.forEach(card => {
          card.style.visibility = 'visible';
          card.style.opacity = '1';
          card.style.transform = 'none';
          card.style.transition = 'none';
        });
      }
    };
    
    // Fix for ScrollReveal on tab indicators specifically
    const fixTabIndicators = () => {
      // Fix all tab indicators to ensure they're properly responsive
      document.querySelectorAll('[data-tab-indicator]').forEach(indicator => {
        // Ensure visibility properties are set but not display properties
        indicator.style.visibility = 'visible';
        indicator.style.opacity = '1';
        
        // Remove any inline display style that might conflict with our CSS
        if (indicator.style.display) {
          indicator.style.removeProperty('display');
        }
      });
    };
    
    // Run our fixes after ScrollReveal has initialized
    setTimeout(fixScrollRevealElements, 100);
    setTimeout(fixTabIndicators, 100);
    
    // 2. Second approach: Add our card containers to ScrollReveal's clean list
    if (window.ScrollReveal) {
      setTimeout(() => {
        try {
          // Clean our cards containers from ScrollReveal effects
          ScrollReveal().clean('.sr-exclude-cards, .sr-exclude-cards > *');
          // Force visibility on all cards
          document.querySelectorAll('.sr-exclude-cards > div').forEach(card => {
            card.style.visibility = 'visible';
            card.style.opacity = '1';
          });
        } catch (e) {
          console.warn('Error applying ScrollReveal fix', e);
        }
      }, 200);
    }
    
    // Make sure we fix the visibility whenever tabs change
    document.querySelectorAll('.tab-button').forEach(btn => {
      btn.addEventListener('click', () => {
        setTimeout(fixScrollRevealElements, 10);
      });
    });
  });
  
  // Ensure Alpine.js is properly initialized
  document.addEventListener('DOMContentLoaded', () => {
    // Pure JavaScript fallback for tab functionality
    window.setActiveTab = function(tabKey) {
      console.log('Switching to tab:', tabKey);
      
      // Handle tab buttons
      document.querySelectorAll('.tab-button').forEach(btn => {
        const isActive = btn.getAttribute('data-tab') === tabKey;
        if (isActive) {
          btn.classList.add('bg-white', 'text-[#AA1010]', 'shadow-sm', 'z-10');
          btn.classList.remove('text-gray-700');
        } else {
          btn.classList.remove('bg-white', 'text-[#AA1010]', 'shadow-sm', 'z-10');
          btn.classList.add('text-gray-700');
        }
      });
      
      // Handle content sections
      document.querySelectorAll('[data-tab-content]').forEach(content => {
        const isActive = content.getAttribute('data-tab-content') === tabKey;
        console.log('Content section:', content.getAttribute('data-tab-content'), 'isActive:', isActive);
        
        // Make sure to remove any potential Alpine.js hiding
        if (isActive) {
          content.style.display = 'block';
          content.removeAttribute('hidden');
          content.classList.remove('hidden');
          
          // Fix ScrollReveal hiding elements - but only in our tabs
          const cards = content.querySelectorAll('.sr-exclude-cards > div');
          cards.forEach(card => {
            card.style.visibility = 'visible';
            card.style.opacity = '1';
            card.style.transform = 'none';
          });
        } else {
          content.style.display = 'none';
        }
      });
      
      // Handle tab indicators
      document.querySelectorAll('[data-tab-indicator]').forEach(indicator => {
        const isActive = indicator.getAttribute('data-tab-indicator') === tabKey;
        // Make active indicator visible and ensure it's not affected by ScrollReveal
        if (isActive) {
          // Don't override the display property directly to respect responsive CSS
          indicator.style.visibility = 'visible';
          indicator.style.opacity = '1';
          indicator.style.transform = 'none';
          
          // Add active class that our CSS will handle
          indicator.classList.add('active-tab-indicator');
        } else {
          // Just add a hidden class rather than setting display directly
          indicator.classList.remove('active-tab-indicator');
          indicator.classList.add('hidden-tab-indicator');
        }
      });
    };
    
    // Function to initialize tabs
    const initTabs = () => {
      console.log('Initializing tabs');
      
      // Get all tab buttons
      const tabButtons = Array.from(document.querySelectorAll('.tab-button'));
      
      // Initialize tab indicators with correct classes
      document.querySelectorAll('[data-tab-indicator]').forEach(indicator => {
        // Remove any inline display styles that might override our CSS
        if (indicator.style.display) {
          indicator.style.removeProperty('display');
        }
      });
      
      // Always set the first tab (leftmost) as active regardless of its key
      if (tabButtons.length > 0) {
        // Get the first button's tab key
        const firstButton = tabButtons[0];
        const firstTabKey = firstButton.getAttribute('data-tab');
        console.log('Setting first tab active:', firstTabKey);
        
        // Use the setActiveTab function to activate the first tab
        window.setActiveTab(firstTabKey);
        
        // Update Alpine.js data model if it exists
        if (window.Alpine) {
          const appLinksElement = document.querySelector('.app-links[x-data]');
          if (appLinksElement && appLinksElement.__x) {
            appLinksElement.__x.updateData('activeTab', firstTabKey);
          }
        }
        
        // Extra measure: Force all cards to be visible
        setTimeout(() => {
          document.querySelectorAll('.sr-exclude-cards > div').forEach(card => {
            card.style.visibility = 'visible';
            card.style.opacity = '1';
            card.style.transform = 'none';
            card.style.transition = 'none';
          });
          
          // Disable ScrollReveal completely on our content
          if (window.ScrollReveal) {
            ScrollReveal().clean('.app-links, .sr-exclude-cards, .sr-exclude-cards > *');
          }
        }, 50);
      }
    };
    
    // Force initialization immediately
    initTabs();
    
    // Check if Alpine is available
    if (typeof Alpine === 'undefined') {
      console.warn('Alpine.js is not loaded. Using fallback tab functionality.');
      
      // Manually load Alpine.js if not available
      const script = document.createElement('script');
      script.src = '<?php echo get_template_directory_uri(); ?>/assets/scripts/alpine.min.js';
      script.defer = true;
      script.onload = () => {
        console.log('Alpine.js loaded dynamically');
        // Re-init tabs when Alpine loads
        setTimeout(initTabs, 100);
      };
      document.head.appendChild(script);
    } else {
      console.log('Alpine.js is loaded and tab system ready');
      // Still run our init to make sure things are visible
      setTimeout(initTabs, 100); // Small delay to let Alpine initialize
    }
  });
  
  // Run initialization after a small delay even if DOMContentLoaded hasn't fired yet
  setTimeout(() => {
    if (document.querySelector('.tab-button')) {
      // Get the first tab button
      const firstButton = document.querySelector('.tab-button');
      const firstTabKey = firstButton ? firstButton.getAttribute('data-tab') : window.activeTabDefault;
      
      if (window.setActiveTab && firstTabKey) {
        window.setActiveTab(firstTabKey);
      }
    }
  }, 100);
  
  // Check for screen size and fix tab indicators accordingly
  const fixResponsiveDisplay = () => {
    const isLargeScreen = window.matchMedia('(min-width: 1024px)').matches;
    const activeTab = document.querySelector('.tab-button.bg-white')?.getAttribute('data-tab');
    
    if (!activeTab) return;
    
    document.querySelectorAll('[data-tab-indicator]').forEach(indicator => {
      const isActiveIndicator = indicator.getAttribute('data-tab-indicator') === activeTab;
      
      if (isActiveIndicator) {
        // Always ensure visibility properties are set for ScrollReveal
        indicator.style.visibility = 'visible';
        indicator.style.opacity = '1';
        
        // Let CSS handle display based on screen size
        if (indicator.style.display) {
          indicator.style.removeProperty('display');
        }
      } else {
        // Non-active indicators should always be hidden
        indicator.classList.add('hidden-tab-indicator');
      }
    });
  };
  
  // Run this check on page load and resize
  fixResponsiveDisplay();
  window.addEventListener('resize', fixResponsiveDisplay);
</script>
<!-- Alpine.js is already loaded via functions.php, this is just a fallback -->
<?php endif; // End of frontend view (else part of is_admin condition) ?>
