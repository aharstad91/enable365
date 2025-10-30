<?php
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_editor_style('editor-style.css');
	add_image_size( 'ansatte-thumb', 25, 25, array( 'left', 'top' ) );

	function gutenbergtheme_editor_styles() { wp_enqueue_style( 'gutenbergthemeblocks-style', get_template_directory_uri() . '/blocks.css'); }
	
	add_action( 'enqueue_block_editor_assets', 'gutenbergtheme_editor_styles' );


	function register_theme_menus() {
	register_nav_menus(
		array(
			'primary-menu' 	    => __( 'Hovedmeny'),
			'apps-it-admin' 	=> __( 'Apps - IT admin & governance '),
			'apps-productivity' => __( 'Apps - Productivity & Management'),
			'footer-menu-one' 	=> __( 'Bunnmeny: Products'),
			'footer-menu-two' 	=> __( 'Bunnmeny: Sales & Support'),
			'footer-menu-three' => __( 'Bunnmeny: Company'),
			'mobile-menu' 	    => __( 'Mobilmeny'),
			'mobile-menu-apps' 	=> __( 'Mobilmeny: Apps'),

		)
		);
	}
	add_action( 'init', 'register_theme_menus' );


/* ------------------------------------------------------------------------------------- *
 * Setup admin
 * ------------------------------------------------------------------------------------- */
	
	
	function wd_admin_enqueues() {
		
			 // custom block styles
			 wp_enqueue_script(
				  'wd-editor',
				  get_stylesheet_directory_uri() . '/assets/scripts/editor-min.js',
				  [ 'wp-blocks', 'wp-dom' ],
				  filemtime( get_stylesheet_directory() . '/assets/scripts/editor-min.js' ),
				  true
			 );
		}
		add_action( 'enqueue_block_editor_assets', 'wd_admin_enqueues' );
		
/* ------------------------------------------------------------------------------------- *
 * Tailwind CSS Integration
 * ------------------------------------------------------------------------------------- */
	
/**
 * Enqueue Tailwind CSS and ensure WordPress core styles take precedence
 */
function enable365_enqueue_tailwind() {
	// Only enqueue Tailwind CSS if the file exists
	if (file_exists(get_template_directory() . '/style.tailwind.css')) {
		// Enqueue Tailwind CSS after WordPress core styles to ensure proper override priority
		wp_enqueue_style(
			'enable365-tailwind',
			get_template_directory_uri() . '/style.tailwind.css',
			array(), // No dependencies to ensure it loads after core WordPress styles
			filemtime(get_template_directory() . '/style.tailwind.css')
		);

	}
}
add_action('wp_enqueue_scripts', 'enable365_enqueue_tailwind', 20); // Priority 20 to load after WordPress core styles

/**
 * Ensure block editor styles don't conflict with Tailwind
 */
function enable365_block_editor_styles() {
	// Add editor styles that ensure WordPress classes take precedence
	$custom_editor_css = '
		.alignnone, .alignleft, .alignright, .aligncenter,
		.size-thumbnail, .size-medium, .size-large, .size-full,
		.wp-caption {
			max-width: 100%;
			height: auto;
		}
		.size-full {
			width: auto !important;
		}
		
		/* Heading style resets - prevent Tailwind from resetting heading styles */
		h1, h2, h3, h4, h5, h6 {
			font-size: revert !important;
			font-weight: revert !important;
			margin: revert !important;
			line-height: revert !important;
		}
	';
	wp_add_inline_style('wp-edit-blocks', $custom_editor_css);
}
add_action('enqueue_block_editor_assets', 'enable365_block_editor_styles');


	//Resize av bilder som har for lav størrelse
	add_filter('image_resize_dimensions', function($default, $ow, $oh, $nw, $nh, $crop){
	 if(!$crop)return $default; //let the wordpress default function handle this
	 $sx = floor(($ow - ($cw = round($nw / max($nw/$ow, $nh/$oh)))) / 2);
	 $sy = floor(($oh - ($ch = round($nh / max($nw/$ow, $nh/$oh)))) / 2);
	 return array(0, 0, (int)$sx, (int)$sy, (int)$nw, (int)$nh, (int)$cw, (int)$ch);
	}, 10, 6);

	//SVG support through media uploader
	function cc_mime_types($mimes) {$mimes['svg'] = 'image/svg+xml';return $mimes;}
	add_filter('upload_mimes', 'cc_mime_types');	



add_action('init', function(){					//Kjør stuff når WP er startet opp
	add_post_type_support('page', 'excerpt');	//Support for excerpt på pages
	$args = array(
		'public'		=> true,
		'has_archive'	=> true,
		'hierarchical'	=> true,
	    'show_in_rest' => true,
		'supports'		=> array('title', 'editor', 'thumbnail', 'revisions', 'excerpt', 'author'),
	);
	$args_nopage = array(
		'public'		=> true,
		'has_archive'	=> false,
		'hierarchical'	=> false,
		'show_in_rest' => true,
		'supports'		=> array('title', 'editor', 'thumbnail', 'revisions', 'excerpt', 'author'),
	);
	register_post_type('demo', wp_parse_args('label=Demo&menu_icon=dashicons-lightbulb', $args));
	register_post_type('register', wp_parse_args('label=Register&menu_icon=dashicons-lightbulb', $args));
	register_post_type('newsroom', wp_parse_args('label=Presserom&menu_icon=dashicons-lightbulb', $args_nopage));
	register_post_type('support', wp_parse_args('label=Support&menu_icon=dashicons-lightbulb', $args_nopage));
	register_post_type('blog', wp_parse_args('label=Blogg&menu_icon=dashicons-lightbulb', $args));
	register_post_type('pricing', wp_parse_args('label=Pricing&menu_icon=dashicons-lightbulb', $args_nopage));
	
});


	register_taxonomy( 'newsroom-categories', array('newsroom'), array(
		'labels' => 'Kategorier',
		'hierarchical' => false, 
		'label' => 'Kategorier', 
		'singular_label' => 'kategori', 
		'rewrite' => array( 'slug' => 'newsroom/category', 'with_front'=> false )
		)
	);
	register_taxonomy( 'blog-categories', array('blog'), array(
		'labels' => 'Kategorier',
		'hierarchical' => false, 
		'label' => 'Kategorier', 
		'singular_label' => 'kategori', 
		'rewrite' => array( 'slug' => 'blog/category', 'with_front'=> false )
		)
	);

/* ------------------------------------------------------------------------------------- *
 * Theme markup
 * ------------------------------------------------------------------------------------- */
	
	/* Fjern <p> element på bilder */
	function filter_ptags_on_images($content){
	   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}
	
	add_filter('the_content', 'filter_ptags_on_images');
	
	
	/* Enqueue scripts and styles. */
	function wpdocs_theme_name_scripts() {

	    wp_enqueue_style( 'framework.css', get_template_directory_uri() . '/framework.css');
	    wp_enqueue_style( 'style', get_stylesheet_uri());  
		wp_enqueue_style( 'blocks-styles.css', get_template_directory_uri() . '/template-parts/block/block-styles.css');
		wp_enqueue_style( 'general-components.css', get_template_directory_uri() . '/general-components.css');
		
		



		wp_enqueue_script( 'jquery', get_template_directory_uri() . '/wp-includes/js/jquery/jquery.js', array(), '1.0.0', true );
		wp_enqueue_script( 'scrollreveal.min.js', get_template_directory_uri() . '/assets/scripts/scrollreveal.min.js', array(), '1.0.0', false);
		wp_enqueue_script( 'scrollock', get_template_directory_uri() . '/assets/scripts/bodyScrollLock.js', array(), '1.0.0', true );
		wp_enqueue_script( 'headroom.js', get_template_directory_uri() . '/assets/scripts/headroom.js', array(), '1.0.0', true );
		wp_enqueue_script( 'headroom-init.js', get_template_directory_uri() . '/assets/scripts/headroom-init.js', array('headroom.js'), '1.0.0', true );
		// wp_enqueue_script( 'megamenu.js', get_template_directory_uri() . '/assets/scripts/megamenu.js', array(), '1.0.0', true ); // Original megamenu script
		wp_enqueue_script( 'megamenu-viewport.js', get_template_directory_uri() . '/assets/scripts/megamenu-viewport.js', array(), '1.0.0', true ); // Enhanced megamenu with viewport centering
		
		
        
		
		
		
	}
	add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );

	
	
	
	
	function load_custom_wp_admin_style() {
        wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/template-parts/block/block-styles.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
	}
	add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );
	
	
	




	function theme_slug_setup() {
   add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'theme_slug_setup' );

	
	
	
/**
 * Registers support for Gutenberg wide images in Writy.
 */
function writy_setup() {
  add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'writy_setup' );

add_filter( 'edit_post_link', function( $link, $post_id, $text )
{
    // Add the target attribute 
    if( false === strpos( $link, 'target=' ) )
        $link = str_replace( '<a ', '<a target="_blank" ', $link );

    return $link;
}, 10, 3 );



add_action('init', function(){					//Kjør stuff når WP er startet opp
	add_post_type_support('page', 'excerpt');	//Support for excerpt på pages
	$args = array(
		'public'		=> true,
		'has_archive'	=> false,
		'hierarchical'	=> true,
		'show_in_rest' => true,
		'supports'		=> array('title', 'editor', 'thumbnail', 'revisions', 'excerpt'),
	);
	register_post_type('apps', wp_parse_args('label=Apps&menu_icon=dashicons-lightbulb', $args));
	register_taxonomy('try-now', 'apps', array(
	   'label'				=> 'Try now',	//Menneskeleslig navn
	   'hierarchical'		=> true,		//Aktiver foreldre/barn
	   'show_admin_column'  => true,		//Vis for admin i liste
	   /*'rewrite'           => array('slug'=>'referanse-kategori')*/
	));
	register_post_type('apps', wp_parse_args('label=Apps&menu_icon=dashicons-lightbulb', $args));
	register_taxonomy('categories', 'apps', array(
	   'label'				=> 'Kategorier',	//Menneskeleslig navn
	   'hierarchical'		=> true,		//Aktiver foreldre/barn
	   'show_admin_column'  => true,		//Vis for admin i liste
	   /*'rewrite'           => array('slug'=>'referanse-kategori')*/
	));
});


if( function_exists('acf_add_options_page') ) {
		
		acf_add_options_page(array(
			'page_title' 	=> 'Dynamiske felter',
			'menu_title'	=> 'Dynamiske felter',
			'menu_slug' 	=> 'theme-general-settings',
			'capability'	=> 'edit_posts',
			'redirect'		=> false
		));
		
	}	



add_action('acf/init', 'my_acf_init');
function my_acf_init() {
	// check function exists
	if( function_exists('acf_register_block') ) {
		
		
		// register a intro-section block
		acf_register_block(array(
			'name'				=> 'bulletlist-checkmark',
			'title'				=> __('Liste element med checkmark'),
			'description'		=> __('List item with checkmark.'),
			'render_callback'	=> 'my_acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'admin-comments',
			'keywords'			=> array( 'list' ),
		));
		// register a intro-section block
		acf_register_block(array(
			'name'				=> 'testimonial-block',
			'title'				=> __('Testimonial block'),
			'description'		=> __('Testimonial block.'),
			'render_callback'	=> 'my_acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'admin-comments',
			'keywords'			=> array( 'list' ),
		));
		// register a intro-section block
		acf_register_block(array(
			'name'				=> 'staff-card',
			'title'				=> __('Staff block'),
			'description'		=> __('Staff block.'),
			'render_callback'	=> 'my_acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'admin-comments',
			'keywords'			=> array( 'list' ),
		));
		// register a intro-section block
		acf_register_block(array(
			'name'				=> 'getstarted-section',
			'title'				=> __('Get started section'),
			'description'		=> __('Get started section'),
			'render_callback'	=> 'my_acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'admin-comments',
			'keywords'			=> array( 'list' ),
		));
		// register a intro-section block
		acf_register_block(array(
			'name'				=> 'chooseposts_loop',
			'title'				=> __('Choose posts'),
			'description'		=> __('Choose posts'),
			'render_callback'	=> 'my_acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'admin-comments',
			'keywords'			=> array( 'list' ),
		));
		// register a intro-section block
		acf_register_block(array(
			'name'				=> 'applications_showcase',
			'title'				=> __('App showcase'),
			'description'		=> __('Choose posts'),
			'render_callback'	=> 'my_acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'admin-comments',
			'keywords'			=> array( 'list' ),
		));
		// register a intro-section block
		// Old block registration removed and replaced by the JSON-based registration in register_acf_blocks()
		// acf_register_block(array(
		// 	'name'				=> 'apps-by-category',
		// 	'title'				=> __('Applications by Category'),
		// 	'description'		=> __('Choose posts'),
		// 	'render_callback'	=> 'my_acf_block_render_callback',
		// 	'category'			=> 'formatting',
		// 	'icon'				=> 'admin-comments',
		// 	'keywords'			=> array( 'list' ),
		// ));
	}
}


function my_acf_block_render_callback( $block ) {
	
	// convert name ("acf/testimonial") into path friendly slug ("testimonial")
	$slug = str_replace('acf/', '', $block['name']);
	
	// First check if the file exists in the blocks directory
	if( file_exists( get_theme_file_path("/blocks/{$slug}/template.php") ) ) {
		include( get_theme_file_path("/blocks/{$slug}/template.php") );
	} 
	// If not, check the original location (template-parts/block)
	else if( file_exists( get_theme_file_path("/template-parts/block/{$slug}.php") ) ) {
		include( get_theme_file_path("/template-parts/block/{$slug}.php") );
	}
}




// Make sure ACF is loaded before registering blocks
add_action('acf/init', 'register_acf_blocks');
function register_acf_blocks() {
	if( function_exists('acf_register_block_type') ) {
		$blocks = [
			'video-image-overlay' => '/blocks/video-image-overlay/block.json',
			'application-by-category' => '/blocks/application-by-category/block.json',
			'top-section-author' => '/blocks/top-section-author/block.json',
		];
		foreach ($blocks as $block_name => $json_path) {
			register_block_type(__DIR__ . $json_path);
		}
	}
}



// Add this to your functions.php
function enqueue_video_overlay_block_styles() {
	wp_enqueue_style(
		'video-overlay-block-styles',
		get_template_directory_uri() . '/blocks/video-image-overlay/style.css',
		array(),
		filemtime(get_template_directory() . '/blocks/video-image-overlay/style.css')
	);
}
add_action('wp_enqueue_scripts', 'enqueue_video_overlay_block_styles');
add_action('admin_enqueue_scripts', 'enqueue_video_overlay_block_styles');

// Register Alpine.js for the application-by-category block
function enqueue_alpine_js() {
    wp_register_script(
        'alpine-js',
        get_template_directory_uri() . '/assets/scripts/alpine.min.js',
        array(),
        '3.x',
        true
    );
    
    wp_enqueue_script('alpine-js');
}
add_action('wp_enqueue_scripts', 'enqueue_alpine_js');
add_action('admin_enqueue_scripts', 'enqueue_alpine_js');

// Custom Walker to show menu item descriptions
class Description_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $output .= '<li' . $class_names . '>';
        
        // Check if this item is marked as "coming soon"
        $is_coming = get_field('coming', $item);
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        $item_output = $args->before;
        
        // Create a non-clickable div instead of an anchor if the item is marked as "coming"
        if ($is_coming) {
            // Use the menu location to determine styling (different styles for mobile vs desktop)
            $menu_location = isset($args->theme_location) ? $args->theme_location : '';
            $is_mobile = (strpos($menu_location, 'mobile') !== false);
            
            if ($is_mobile) {
                // Mobile style
                $item_output .= '<div class="w-full flex items-center cursor-default">';
                
                // Add ACF icon if it exists
                $icon = get_field('menu_icon', $item);
                if ($icon) {
                    $item_output .= '<img src="' . esc_url($icon['url']) . '" alt="" class="h-[24px] w-[24px] mr-2">';
                }
                
                $item_output .= '<div class="flex flex-col">';
                $item_output .= '<div class="flex items-center text-base font-medium text-black">' . 
                                $args->link_before . $title . ' <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-gray-200 text-gray-600 rounded">Coming soon</span>' . $args->link_after . 
                                '</div>';
                
                if (!empty($item->description)) {
                    $item_output .= '<div class="text-sm text-gray-500">' . esc_html($item->description) . '</div>';
                }
                
                $item_output .= '</div>';
                $item_output .= '</div>';
            } else {
                // Desktop style (megamenu)
                $item_output .= '<div class="px-4 py-4 w-full flex gap-3 rounded-lg items-center group cursor-default">';
                
                // Add ACF icon if it exists
                $icon = get_field('menu_icon', $item);
                if ($icon) {
                    $item_output .= '<img src="' . esc_url($icon['url']) . '" alt="" class="h-[40px] w-[40px]">';
                }
                
                $item_output .= '<div class="flex flex-col space-y-1">';
                $item_output .= '<div class="text-base font-medium text-black leading-none flex items-center">' . 
                                $args->link_before . $title . ' <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-gray-200 text-gray-600 rounded">Coming soon</span>' . $args->link_after . 
                                '</div>';
                
                if (!empty($item->description)) {
                    $item_output .= '<div class="text-base menu-item-description text-sm text-gray-500 leading-none">' . esc_html($item->description) . '</div>';
                }
                
                $item_output .= '</div>';
                $item_output .= '</div>';
            }
        } else {
            // For regular items, keep the original anchor behavior
            $atts = array();
            $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
            $atts['target'] = !empty($item->target)     ? $item->target     : '';
            $atts['rel']    = !empty($item->xfn)        ? $item->xfn        : '';
            $atts['href']   = !empty($item->url)        ? $item->url        : '';
            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }
            
            // Use the menu location to determine styling (different styles for mobile vs desktop)
            $menu_location = isset($args->theme_location) ? $args->theme_location : '';
            $is_mobile = (strpos($menu_location, 'mobile') !== false);
            
            if ($is_mobile) {
                // Mobile menu item style
                $item_output .= '<a'. $attributes . ' class="w-full flex items-center">';
                
                // Add ACF icon if it exists
                $icon = get_field('menu_icon', $item);
                if ($icon) {
                    $item_output .= '<img src="' . esc_url($icon['url']) . '" alt="" class="h-[24px] w-[24px] mr-2">';
                }
                
                $item_output .= '<div class="flex flex-col">';
                $item_output .= '<div class="text-base font-medium text-black">' . $args->link_before . $title . $args->link_after . '</div>';
                
                if (!empty($item->description)) {
                    $item_output .= '<div class="text-sm text-gray-500">' . esc_html($item->description) . '</div>';
                }
                
                $item_output .= '</div>';
            } else {
                // Desktop megamenu item style
                $item_output .= '<a'. $attributes . ' class="px-4 py-4 w-full flex gap-3 hover:bg-slate-100 transition-colors duration-100 ease-in-out rounded-lg items-center group">';
                
                // Add ACF icon if it exists
                $icon = get_field('menu_icon', $item); 
                if ($icon) {
                    $item_output .= '<img src="' . esc_url($icon['url']) . '" alt="" class="h-[40px] w-[40px]">';
                }
                
                $item_output .= '<div class="flex flex-col space-y-1">';
                $item_output .= '<div class="text-base font-medium text-black leading-none">' . $args->link_before . $title . $args->link_after . '</div>';
                
                if (!empty($item->description)) {
                    $item_output .= '<div class="text-base menu-item-description text-sm text-gray-500 leading-none group-hover:text-gray-700">' . esc_html($item->description) . '</div>';
                }
                
                $item_output .= '</div>';
            }
            
            $item_output .= '</a>';
        }
        
        $item_output .= $args->after;
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

// Custom Walker to support dropdown menus
class Enable_Dropdown_Walker_Menu extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = array()) {
        // Start dropdown container
        $indent = str_repeat("\t", $depth);
        if ($depth === 0) {
            $output .= "\n$indent<ul class=\"dropdown-menu py-4 px-4\">\n";
        } else {
            // Tredje nivå (og dypere) vises ikke
            if ($depth >= 1) {
                return;
            }
            $output .= "\n$indent<ul class=\"sub-dropdown-menu\">\n";
        }
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        // End dropdown container
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        
        // Add appropriate classes based on depth and children
        if ($depth === 0 && $has_children) {
            $class_names .= ' has-dropdown flex items-center';
        } else if ($depth > 0 && $has_children) {
            $class_names .= ' has-sub-dropdown';
        }
        
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $output .= '<li' . $class_names . '>';
        
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target)     ? $item->target     : '';
        $atts['rel']    = !empty($item->xfn)        ? $item->xfn        : '';
        $atts['href']   = !empty($item->url)        ? $item->url        : '';
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        $item_output = $args->before;
        
        // For top level items
        if ($depth === 0) {
            $item_output .= '<a'. $attributes . ' style="display: flex; align-items: center;">';
            $item_output .= $args->link_before . $title . $args->link_after;
            
            // Add dropdown arrow for parent items
            if ($has_children) {
                $item_output .= ' <svg style="margin-left:4px;" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 5L6 8L9 5" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            }
            
            $item_output .= '</a>';
        } 
        // For dropdown items
        else {
            $item_output .= '<a'. $attributes . ' class="px-4 py-3 w-full flex hover:bg-slate-100 transition-colors duration-100 ease-in-out rounded-lg items-center group">';
            $item_output .= '<div class="flex flex-col">';
            $item_output .= '<div class="text-base font-medium text-black leading-tight">' . $args->link_before . $title . $args->link_after . '</div>';
            
            if (!empty($item->description)) {
                $item_output .= '<div class="text-sm text-gray-500 leading-tight mt-1 group-hover:text-gray-700">' . esc_html($item->description) . '</div>';
            }
            
            $item_output .= '</div>';
            
            // Add dropdown arrow for submenu items with children
            if ($has_children) {
                $item_output .= ' <svg style="margin-left:auto;" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 3L8 6L5 9" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            }
            
            $item_output .= '</a>';
        }
        
        $item_output .= $args->after;
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}




