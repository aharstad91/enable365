<!DOCTYPE html>
<html>
<head>
	
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script>document.createElement('main'); /* IE-Fix */</script>
	<?php get_template_part( '/assets/scripts/gtm' ); ?>
	<?php get_template_part( '/assets/favicons' ); ?>


<!-- Hjelper nettleseren å åpne forbindelser tidlig -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	
	<!-- Last font-CSS asynkront -->
	<link rel="preload"
		  href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;600&display=swap"
		  as="style"
		  onload="this.onload=null;this.rel='stylesheet'">
	
	<!-- Fallback hvis JS er av -->
	<noscript>
	  <link rel="stylesheet"
			href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;600&display=swap">
	</noscript>



	
	<link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo('template_directory'); ?>/assets/gfx/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo('template_directory'); ?>/assets/gfx/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo('template_directory'); ?>/assets/gfx/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?php bloginfo('template_directory'); ?>/assets/gfx/favicon/site.webmanifest">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
	
	
	<?php wp_head(); ?>	
<meta name="google-site-verification" content="ksrTxFt3oO2oraZDYCkTtjNOYJlNhPM_6Ca7kiK2pR8" />

<meta name="msvalidate.01" content="1E33816CE53856CC6E03C7B2C5000A7E" />
	<style>
		/* Navigation menu color override */
		.primary-header .primary-nav a {
			color: #0D2538 !important;
		}
	</style>
</head>
<body <?php body_class(); ?>>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M3TGPXJK"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php
// Resolve ACF-driven navigation labels per menu location
// Each label is stored on the nav_menu term as 'nav_item_label'
// Usage: get_field( 'nav_item_label', 'nav_menu_{term_id}' )
$enable365_nav_labels = [
	'products'       => 'Products',
	'apps_productivity' => 'Productivity & management',
	'apps_it_admin'  => 'IT admin & governance',
];

if ( function_exists( 'get_nav_menu_locations' ) ) {
	$locations = get_nav_menu_locations();
	
	// Get current language for WPML compatibility
	$current_lang = function_exists('icl_get_current_language') ? icl_get_current_language() : null;

	// Primary menu (top-level Products trigger)
	if ( isset( $locations['primary-menu'] ) && function_exists( 'get_field' ) ) {
		$menu_id = $locations['primary-menu'];
		
		// Get translated menu ID for current language
		if ($current_lang && function_exists('icl_object_id')) {
			$translated_menu_id = icl_object_id($menu_id, 'nav_menu', false, $current_lang);
			if ($translated_menu_id) {
				$menu_id = $translated_menu_id;
			}
		}
		
		$label = get_field( 'nav_item_label', 'nav_menu_' . $menu_id );
		if ( ! empty( $label ) ) {
			$enable365_nav_labels['products'] = $label;
		}
	}

	// Megamenu left column: apps-productivity
	if ( isset( $locations['apps-productivity'] ) && function_exists( 'get_field' ) ) {
		$menu_id = $locations['apps-productivity'];
		
		// Get translated menu ID for current language
		if ($current_lang && function_exists('icl_object_id')) {
			$translated_menu_id = icl_object_id($menu_id, 'nav_menu', false, $current_lang);
			if ($translated_menu_id) {
				$menu_id = $translated_menu_id;
			}
		}
		
		$label = get_field( 'nav_item_label', 'nav_menu_' . $menu_id );
		if ( ! empty( $label ) ) {
			$enable365_nav_labels['apps_productivity'] = $label;
		}
	}

	// Megamenu right column: apps-it-admin
	if ( isset( $locations['apps-it-admin'] ) && function_exists( 'get_field' ) ) {
		$menu_id = $locations['apps-it-admin'];
		
		// Get translated menu ID for current language
		if ($current_lang && function_exists('icl_object_id')) {
			$translated_menu_id = icl_object_id($menu_id, 'nav_menu', false, $current_lang);
			if ($translated_menu_id) {
				$menu_id = $translated_menu_id;
			}
		}
		
		$label = get_field( 'nav_item_label', 'nav_menu_' . $menu_id );
		if ( ! empty( $label ) ) {
			$enable365_nav_labels['apps_it_admin'] = $label;
		}
	}
}
?>
	
	
	<!--<div class="globalMessageContainer">
		<div class="globalMessage container-xl">
			<p>The New Microsoft Teams (currently in Preview) does not yet support all programming frameworks affecting our apps ability to assign Planner tasks and app administrators in settings. Switch back to old Teams or to Teams in web browser to work around the issue. Sorry for the inconvenience, and we expect Microsoft to resolve the issue soon.</p>
		</div>
	</div>-->
	
	<!-- Hello World via git!-->
	
	<header class="primary-header" id="site-header">
		<div class="container relative">
			<a href="<?php bloginfo('url'); ?>"><img class="logo w-[160px]" alt="" src="<?php bloginfo('template_directory'); ?>/assets/gfx/enable-right-logo.svg"></a>
			<div class="header-right-area">
				<nav class="primary-nav font-medium text-[17px]">
					<ul class="flex gap-4">
						<li class="has-megamenu flex items-center"><?php echo esc_html( $enable365_nav_labels['products'] ); ?> <svg style="vertical-align:middle; margin-left:4px;" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 5L6 8L9 5" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
							<div class="megamenu flex gap-8 px-8 py-8">
								<ul>
									<span class="mb-2 block pl-4 text-gray-800"><?php echo esc_html( $enable365_nav_labels['apps_productivity'] ); ?></span>
									<?php wp_nav_menu( array( 'container' => '', 'items_wrap' => '%3$s',  'theme_location' => 'apps-productivity', 'walker' => new Description_Walker_Nav_Menu() ) ); ?>
								</ul>
								<ul>
									<span class="mb-2 block pl-4 text-gray-800"><?php echo esc_html( $enable365_nav_labels['apps_it_admin'] ); ?></span>
									<?php wp_nav_menu( array( 'container' => '', 'items_wrap' => '%3$s',  'theme_location' => 'apps-it-admin', 'walker' => new Description_Walker_Nav_Menu() ) ); ?>
								</ul>

							</div>
						</li>
						<?php 
						// Modify the primary menu to support dropdown menus
						wp_nav_menu( 
							array( 
								'container' => '', 
								'items_wrap' => '%3$s',  
								'theme_location' => 'primary-menu', 
								'walker' => new Enable_Dropdown_Walker_Menu(),
							) 
						); 
						?>
					</ul>	
				</nav>
			</div>
			<div class="flex gap-4 items-center">
				<div class="lang-container">
					<div class="lang-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12 2C14.5013 4.73835 15.9228 8.29203 16 12C15.9228 15.708 14.5013 19.2616 12 22M12 2C9.49872 4.73835 8.07725 8.29203 8 12C8.07725 15.708 9.49872 19.2616 12 22M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22M2.50002 9H21.5M2.5 15H21.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
					<?php do_action('wpml_add_language_selector');?>
				</div>
				<a class="bg-[#AA1010] border-2 border-[#AA1010] rounded-lg px-3 py-[6px] text-white text-[15px] font-bold text-center" href="<?php bloginfo('url'); ?>/talk-to-sales/"><?php the_field('talk_to_sales', 'option');?></a>
			</div>
		</div>
	</header>
	
	<header class="mobile-header" id="mobile-site-header">
		<div class="mm-container">
			<a href="<?php bloginfo('url');?>" class="logo">
				<img class="logo" alt="Enable 365 logo" src="<?php bloginfo('template_directory'); ?>/assets/gfx/enable-right-logo.svg">
			</a>
			<div class="mm-right-area">
				<div class="lang-container">
					<div class="lang-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12 2C14.5013 4.73835 15.9228 8.29203 16 12C15.9228 15.708 14.5013 19.2616 12 22M12 2C9.49872 4.73835 8.07725 8.29203 8 12C8.07725 15.708 9.49872 19.2616 12 22M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22M2.50002 9H21.5M2.5 15H21.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
					<?php do_action('wpml_add_language_selector');?>
				</div>
				<div id="mm-toggle">
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
		</div>
		<div id="mm-menu" class="mm-menu">
			<nav class="main-nav" id="main-nav">
				<ul class="apps-links m-b-2">
					<li class="mobile-megamenu-title"><?php echo esc_html( $enable365_nav_labels['products'] ); ?></li>
					<?php wp_nav_menu( array( 'container' => '', 'items_wrap' => '%3$s',  'theme_location' => 'mobile-menu-apps', 'walker' => new Description_Walker_Nav_Menu() ) ); ?>
				</ul>
				<ul class="main-links">
					<?php wp_nav_menu( array( 'container' => '', 'items_wrap' => '%3$s',  'theme_location' => 'mobile-menu', 'walker' => new Description_Walker_Nav_Menu() ) ); ?>
				</ul>
			</nav>
			<a class="bg-[#AA1010] border-2 border-[#AA1010] rounded-lg px-3 py-[6px] text-white text-[15px] font-bold text-center" href="<?php bloginfo('url'); ?>/talk-to-sales/"><?php echo esc_html( get_field('talk_to_sales', 'option') ?: 'Contact sales' ); ?></a>

		</div>
	</header>
		<!-- Development: Toggle megamenu active class for CSS work
		Uncomment to enable persistent megamenu for styling
		document.addEventListener('DOMContentLoaded', function() {
		  var productsMenu = document.querySelector('.has-megamenu');
		  var megamenu = document.querySelector('.megamenu');
		  if (productsMenu && megamenu) {
			productsMenu.addEventListener('click', function(e) {
			  e.preventDefault();
			  megamenu.classList.toggle('active');
			});
		  }
		});
		-->