<style>
	/* Remove bullets from all footer lists */
	footer ul {
		list-style: none !important;
		padding-left: 0 !important;
	}
	footer li {
		list-style: none !important;
	}
</style>

<footer class="bg-[#191715] text-white py-16 mt-auto">
	<div class="max-w-[1280px] mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-24">
		<!-- Logo and Description Section -->
		<div class="flex flex-col lg:col-span-1 order-2 lg:order-1">
			<img class="w-48 mb-4" alt="" src="<?php bloginfo('template_directory'); ?>/assets/gfx/enable-logo-light.svg">
			<div class="space-y-4 mb-10">
				<p class="text-sm text-white/80">Our native apps are designed to work seamlessly with Microsoft Teams and Microsoft 365, so you can access your data securely and efficiently. Boost productivity, enhance collaboration, and streamline workflows from anywhere!</p>
				<p class="text-sm text-white/80">Copyright 2025 - Enable 365</p>
				
				<!-- Social Links -->
				<ul class="flex gap-4 mt-4 list-none">
					<li>
						<a href="https://www.linkedin.com/company/enable-365/" target="_blank" class="hover:opacity-80 transition-opacity">
							<img alt="LinkedIn" src="<?php bloginfo('template_directory'); ?>/assets/gfx/linkedin.svg">
						</a>
					</li>
					<li>
						<a href="https://www.facebook.com/enable365/" target="_blank" class="hover:opacity-80 transition-opacity">
							<img alt="Facebook" src="<?php bloginfo('template_directory'); ?>/assets/gfx/facebook.svg">
						</a>
					</li>
					<li>
						<a href="https://www.youtube.com/@enable365" target="_blank" class="hover:opacity-80 transition-opacity">
							<img alt="Youtube" src="<?php bloginfo('template_directory'); ?>/assets/gfx/youtube.svg">
						</a>
					</li>
				</ul>
			</div>
			<!-- Microsoft Partner Section -->
			<div class="flex gap-4">
				<div>
					<img class="w-40 h-auto" alt="" src="<?php bloginfo('template_directory'); ?>/assets/gfx/specialist-ms.png">
				</div>
				<div>
					<img class="w-40 h-auto" alt="" src="<?php bloginfo('template_directory'); ?>/assets/gfx/ms-azure-badge.png">
				</div>
			</div>
		</div>
		
		<!-- Navigation Menus Section -->
		<nav class="flex flex-col lg:flex-row lg:justify-around gap-8 lg:gap-6 lg:col-span-2 order-1 lg:order-2">
			<div class="space-y-2">
				<h4 class="text-base font-normal mb-2">Products</h4>
				<?php wp_nav_menu( array( 
					'container' => '', 
					'items_wrap' => '<ul class="space-y-2 list-none p-0">%3$s</ul>',  
					'theme_location' => 'footer-menu-one',
					'link_before' => '<span class="text-sm text-white/80 font-semibold capitalize underline py-2 inline-flex hover:text-white transition-colors">',
					'link_after' => '</span>'
				) ); ?>
			</div>
			<div class="space-y-2">
				<h4 class="text-base font-normal mb-2">Sales and Support</h4>
				<?php wp_nav_menu( array( 
					'container' => '', 
					'items_wrap' => '<ul class="space-y-2 list-none p-0">%3$s</ul>',  
					'theme_location' => 'footer-menu-two',
					'link_before' => '<span class="text-sm text-white/80 font-semibold capitalize underline py-2 inline-flex hover:text-white transition-colors">',
					'link_after' => '</span>'
				) ); ?>
			</div>
			<div class="space-y-2">
				<h4 class="text-base font-normal mb-2">Company</h4>
				<?php wp_nav_menu( array( 
					'container' => '', 
					'items_wrap' => '<ul class="space-y-2 list-none p-0">%3$s</ul>',  
					'theme_location' => 'footer-menu-three',
					'link_before' => '<span class="text-sm text-white/80 font-semibold capitalize underline py-2 inline-flex hover:text-white transition-colors">',
					'link_after' => '</span>'
				) ); ?>
			</div>
		</nav>

	</div>
	

	<script type="text/javascript">
		/* LINKEDIN INSIGHT TAG CODE */
_linkedin_partner_id = "2539348";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript">
(function(l) {
if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
window.lintrk.q=[]}
var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})(window.lintrk);
/* END OF LINKEDIN INSIGHT TAG CODE */
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=2539348&fmt=gif" />
</noscript>

</footer>

<?php wp_footer(); ?>


<script>
		ScrollReveal({ duration: 1000, distance: '5px', delay: 30})
		ScrollReveal().reveal('.ani-container *', { interval: 0 });
		ScrollReveal().clean('.ani-clean, .wp-block-embed__wrapper, iframe');
	
	
	const clickx= document.getElementById('mm-toggle');
	  const mm_menu= document.getElementById('mm-menu');
	  
	  clickx.addEventListener('click', function(){
		
		if(mm_menu.classList.contains('mm-active')) {
		  mm_menu.classList.remove('mm-active');
		  clickx.classList.remove('mm-open');
		  bodyScrollLock.enableBodyScroll(mm_menu);
		}      
		else {
		  mm_menu.classList.add('mm-active');
		  clickx.classList.add('mm-open');
	
		  bodyScrollLock.disableBodyScroll(mm_menu);
	
		}      
	
	  });
	  
	  
	  // Headroom initialization moved to separate JS file
	  
	  
	
	  jQuery(document).ready(function($) { jQuery('#input_3_4_3').focus(); });
	  
	  
</script>

<?php get_template_part( '/assets/scripts/gtm' ); ?>

	
</body>
</html>